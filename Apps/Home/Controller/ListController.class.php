<?php
namespace Home\Controller;

class ListController extends BaseController {

	//列表页
	public function index() {

		if (empty($_GET['id'])) {
			return $this->error("缺少指定参数!");
		}

        $id = $_GET['id'];
		$classModel = D('Class');
		$class = $classModel->find($id);
		if (is_null($class) || $class['type']!='List') {
			return $this->error("栏目不存在!");
		}

        if($class['father_id'] == 0)//一级栏目
        {
            $allChildClass = $classModel->getChildClassArr($class['class_id']);
            $fatherClass = null;
        }else{
            $allChildClass = $classModel->getChildClassArr($class['father_id']);
            $fatherClass = $classModel->where(['class_id'=>$class['father_id']])->find();
            $classModel->templateId2Info($fatherClass);
        }

		//查询条件
        $condition['class_id'] = $class['class_id'];
        $condition['state'] = 'publish';
		$contentModel = M ('Content');
		// 分页处理,获取数据
		$count = $contentModel->where ( $condition )->count ();
		$Page = new \Think\Page ( $count, 17 );
		$show = $Page->show ();

		$list = $contentModel->where ( $condition )->limit ( $Page->firstRow . ',' . $Page->listRows )->order('is_stick desc, addtime desc')->select ();


        $classModel->templateId2Info($class);
		$this->assign('class', $class);
        $this->assign('fatherClass', $fatherClass);
        $this->assign('allClass', $allChildClass);
		$this->assign ('page', $show);
		$this->assign('list', $list);

		$this->display ();
	}

	//正文页
    public function show()
    {
        if (empty($_GET['id'])) {
            return $this->error("缺少指定参数!");
        }

        $id = $_GET['id'];
        $classModel = D('Class');
        $class = $classModel->find($id);
        if (is_null($class) || $class['type']!='Index') {
            return $this->error("栏目不存在!");
        }

        if($class['father_id'] == 0)//一级栏目
        {
            $allChildClass = $classModel->getChildClassArr($class['class_id']);
            $fatherClass = null;
        }else{
            $allChildClass = $classModel->getChildClassArr($class['father_id']);
            $fatherClass = $classModel->where(['class_id'=>$class['father_id']])->find();
            $classModel->templateId2Info($fatherClass);
        }
        $this->assign('class', $class);
        $this->assign('fatherClass', $fatherClass);
        $this->assign('allClass', $allChildClass);
        $this->display();

    }
}