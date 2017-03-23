<?php
namespace Home\Controller;
use Think\Controller;


class EmailController extends BaseController {

	//显示发送邮件页面
	public function index() {
		
		//获取文章列表右侧图片
		$Flink = M ('Flink');
		$ad = $Flink->find(37);
		$this->assign('ad',$ad);
		
		$this->display();
	}
	
	
	//响应表单发送邮件动作
	public function send() {
		
		if (IS_POST) {
			$Email = D ('Email');
			if (!$Email->validate($Email->sendValidate)->create()){     
				// 如果创建失败 表示验证没有通过 输出错误提示信息    
				 $this->error($Email->getError());
			}else{     
				// 验证通过 可以进行其他数据操作
				//判断是否是实名写信
				$sendType = $_POST['sendType'];
				$name = $_POST['name'];
				if ($sendType === "realyname") {
					if (empty($name)) {
						$this->error("实名写信发信人姓名不能为空!");
					}
				} else {
					if (empty($name)) 
						$Email->name = "匿名";
				}
				
				//上传附件
				if ($_FILES ['annex'] ['name'] != "") {
					$fileInfo = $this->upload ( 'file', true );
					$Email->annex = $fileInfo;
				}
				
				//写入当前时间
				$Email->addtime = time();
				if($Email->add() !== false) {
					$this->success("发送成功,感谢您的参与!",__APP__.'/Home/Index',1);
				} else {
					p($Email->getLastSql());
					$this->error("发送失败！");
				}
			}	
		}
		
	}

}