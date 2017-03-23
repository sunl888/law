<?php
namespace Home\Controller;
use Think\Controller;

class ListController extends BaseController {

	//显示新闻列表
	public function news() {
		
		$id = $_GET['id'];
		if (is_null($id)) {
			$this->error("缺少指定参数!");
		}

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
		

		//获取文章列表右侧图片
		$Flink = M ('Flink');
		$ad = $Flink->find(35);
		$this->assign('ad',$ad);

		//面包屑生成
		$class = $Class->find($id);
		$className = $class['name'];
		$classId = $class['class_id'];
		$classType = $class['type'];
		$mbx = "当前位置：<a href='" . __APP__ ."/Home/Index/index'>首页</a> >> <a href='" . __APP__ . "/Home/" . $classType . "/news/id/" . $classId . "'>". $className ."</a> >> 列表页";
		$this->assign('f_class',$className);
		$this->assign('mbx',$mbx);
		
		$this->assign ( 'page', $show );
		$this->assign('newsList',$newsList);
		$this->display ();

	}

}