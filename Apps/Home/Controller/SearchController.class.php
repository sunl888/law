<?php
/**
 * Created by PhpStorm.
 * User: Sunlong
 * Date: 2017/3/23
 * Time: 23:58
 */

namespace Home\Controller;


class SearchController extends BaseController
{
    public function search($keyword = '')
    {
        $content = D('Content');
        //这里待会验证
        $result = $content->search($keyword);
        $this->assign('searchResult',$result);
        $this->display('list');
    }
}