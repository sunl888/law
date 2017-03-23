<?php
namespace Home\Model;
use Think\Model;

class ContentModel extends Model {


	/**
	 * 根据栏目ID，获取指定数量的新闻列表
	 *
	 * @param $id
	 * @param $amount
	 * @return $newsList
	 */
	public function getContent($id,$amount) {

		$condition['class_id'] = $id;
		$newsList = $this->where($condition)->order('addtime desc,sort_index desc')->limit($amount)->select();
		foreach ($newsList as $k=>$v) {
			$newsList[$k]['addtime'] = strtotime($v['addtime']);
		}
		return $newsList;
	}

    /**
     * 根据文章id获取文章内容
     * @param int $id
     * @return mixed
     */
	public function getArticleById($id =0){
	    $content = M('con_article');
	    return $content->find($id);
    }

	/**
	 * 获取指定栏目下相应数量的焦点图
	 *
	 * @param $id
	 * @param $amount
	 * @return $jdtList
	 */
	public function getJdt($id,$amount) {

		$condition['class_id'] = $id;
		$condition['picurl'] = array('neq','');
		$jdtList = $this->where($condition)->order('addtime desc')->limit($amount)->select();
		return $jdtList;
	}

	//获取指定数量的有缩略图的新闻
	public function getImgNews($id,$amount){
		$condition['class_id'] = $id;
		$condition['picurl'] = array('neq','');
		//todo 这里我把有缩略图的新闻按是否置顶进行排序
		$imgNews = $this->where($condition)->order('is_stick desc,addtime desc')->limit($amount)->select();
		return $imgNews;
	}

	//获取热门文章
	public function getHotNews() {
		return $this->order('views desc,is_stick desc,addtime desc')->limit(8)->select();
	}

	//获取最新文章
	public function getNewNews() {
		return $this->order('addtime desc')->limit(8)->select();
	}



}