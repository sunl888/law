<?php
namespace Home\Controller;
use Think\Controller;

class ComplaintController extends BaseController {
	
	
	public function index() {
		//获取投诉建议右侧图片
		$Flink = M ('Flink');
		$ad = $Flink->find(39);
		$this->assign('ad',$ad);
		$this->display();
	}


	//响应表单发送邮件动作
	public function send() {
	
		if (IS_POST) {
			$Complaint = D ('Message');
			if (!$Complaint->validate($Complaint->sendValidate)->create()){
				// 如果创建失败 表示验证没有通过 输出错误提示信息
				$this->error($Complaint->getError());
			}else{
				// 验证通过 可以进行其他数据操作
				//判断是否是实名写信
				$sendType = $_POST['sendType'];
				$name = $_POST['name'];
				$credentials = $_POST['credentials'];
				if ($sendType === "realyname") {
					if (empty($name)) {
						$this->error("实名写信发信人姓名不能为空!");
					}else if(empty($credentials)){
						$this->error("实名写信发信人证件号不能为空!");
					}
				} else {
					if (empty($name))
						$Complaint->name = "匿名";
				}
	
				//上传附件
				if ($_FILES ['annex'] ['name'] != "") {
					$fileInfo = $this->upload ( 'file', true );
					$Complaint->annex = $fileInfo;
				}
	
				//写入当前时间
				$Complaint->addtime = time();
				if($Complaint->add() !== false) {
					$this->success("发送成功,感谢您的参与!",__APP__.'/Home/Index',1);
				} else {
					p($Complaint->getLastSql());
					$this->error("发送失败！");
				}
			}
		}
	
	}

}