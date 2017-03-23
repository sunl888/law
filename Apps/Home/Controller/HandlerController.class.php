<?php
namespace Home\Controller;
use Think\Controller;


class HandlerController extends BaseController {

	public function handler() {

		$Content = M ('Content');
		$res = $Content->select();

		foreach ($res as $key => $value) {
			
			$img = $value['picurl'];
			echo $img;
			echo "<hr>";

		}

	}

}