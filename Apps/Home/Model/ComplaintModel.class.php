<?php
namespace Home\Model;
use Think\Model;

class ComplaintModel extends Model {
	
	//定义自动验证规则
	public $sendValidate = array (
			array (
					'occupation',
					'require',
					'职业不能为空',
					1 
			) ,
			array (
					'title',
					'require',
					'信件标题不能为空',
					1
			) ,
			array (
					'content',
					'require',
					'信件内容不能为空',
					1 
			)
	);
	
}