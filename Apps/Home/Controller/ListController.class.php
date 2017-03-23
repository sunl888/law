<?php
namespace Home\Controller;

class ListController extends BaseController {

	//列表页
	public function show() {

		if (empty($_GET['id'])) {
			$this->error("缺少指定参数!");
		}
        $id = $_GET['id'];
		$Class = M ('Class');
		$class = $Class->find($id);
		if (is_null($class)) {
			$this->error("栏目不存在!");
		}

		$classIdArr = array();
		//查找该id对应的栏目是否有子栏目
		$Class = D ('Class');
		$classIdArr = $Class->getChildClass($id);
		//查询条件
		$condition['class_id'] = array('in',$classIdArr);

		$Content = M ('Content');

		// 分页处理,获取数据
		$count = $Content->where ( $condition )->count ();
		$Page = new \Think\Page ( $count, 17 );
		$show = $Page->show ();
		$newsList = $Content->where ( $condition )->limit ( $Page->firstRow . ',' . $Page->listRows )->order('addtime desc,is_stick desc')->select ();
		//p($newsList);

		//面包屑生成
		$class = $Class->find($id);
        dd($class);
		$mbx = '当前位置：<a href="'.U('Home/Index/index').'">首页</a> >> <a href="'. U('Home/'.$classType.'/show' ,['id'=>$classId]) .'">'.$className.'</a>';
		$this->assign('class', $class);
		$this->assign ( 'page', $show);
		$this->assign('newsList',$newsList);
		$this->display ();
	}

	//正文页
    public function show11()
    {
        $id = $_GET['id'];
        if (is_null($id)) {
            $this->error("缺少必要参数!");
        }

        $Content = D ('Content');
        $news = $Content->find($id);

        if (is_null($news)) {
            $this->error("您要访问的内容不存在!");
        }

        //更新文章访问量 +1
        $Content->where('content_id='.$id)->setInc('views');

        //获取文章详细内容
        $Article = M ('ConArticle');
        $a = $Article->find($id);
        $news['body'] = $a['body'];
        $this->assign('news',$news);

        //面包屑生成
        $Class = M ('Class');
        $class = $Class->find($news['class_id']);
        $className = $class['name'];
        $classId = $class['class_id'];
        $classType = $class['type'];
        $mbx = "当前位置：<a href='" . __APP__ ."/Home/Index/index'>首页</a> >> <a href='" . __APP__ . "/Home/" . $classType . "/news/id/" . $classId . "'>". $className ."</a> >> 新闻正文";
        $this->assign('mbx',$mbx);

        //处理上一篇、下一篇
        $condition['content_id'] = array('gt',$id);
        $condition['class_id'] = array('eq',$news['class_id']);
        //p($condition);
        $nextNews = $Content->where($condition)->order('addtime desc')->find();
        //p($Content->getLastSql());
        unset($condition);
        $condition['content_id'] = array('lt',$id);
        $condition['class_id'] = array('eq',$news['class_id']);
        $lastNews = $Content->where($condition)->order('addtime desc')->find();

        if (empty($nextNews)) {
            $nextNewsHtml = "<a href='#'></a>";
        } else {
            $nextNewsHtml = "<a href='"  . __APP__ . "/Home/Content/article/id/" . $nextNews['content_id'] . "'>上一篇</a>";
        }

        if (empty($lastNews)) {
            $lastNewsHtml = "<a href='#'></a>";
        } else {
            $lastNewsHtml = "<a href='"  . __APP__ . "/Home/Content/article/id/" . $lastNews['content_id'] . "'>下一篇</a>";
        }


        $this->assign('nextNews',$nextNewsHtml);
        $this->assign('lastNews',$lastNewsHtml);

        $this->display();

    }
}