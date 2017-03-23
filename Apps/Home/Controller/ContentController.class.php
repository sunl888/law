<?php
namespace Home\Controller;
use Think\Controller;


class ContentController extends BaseController {


	public function handler() {

		$Flink = M ('Flink');
		$res = $Flink->select();

		foreach ($res as $key => $value) {
			
			$img = $value['logo'];
			$imgNew = str_replace('-', '', $img);

			echo $imgNew;

				$Flink->where('flink_id = ' .  $value['flink_id'])->setField('logo',$imgNew);
				echo $Flink->getLastSql();
				echo "<hr />";
		}

	}


	//显示文章内容
	public function article(){

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

		//获取文章列表右侧图片
		$Flink = M ('Flink');
		$ad = $Flink->find(36);
		$this->assign('ad',$ad);

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