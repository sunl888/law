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
    protected $result = null;
    protected $limitPage = 10;
    public function search($keyword = '')
    {
        $content = D('Content');
        //这里待会验证
        $kewword = I('get.keyword', '', 'strip_tags,stripslashes');

        // 分页处理,获取数据
        $this->result = $content->search($keyword);
        $count = count($this->result);
        $Page = new MyPage( $count, $this->limitPage );
        $newResult = array_slice($this->result,$Page->firstRow,$Page->listRows);
        $show = $Page->show ();

        $this->assign ('page', $show);
        $this->assign('searchResult',$newResult);
        $this->display('list');
    }
}