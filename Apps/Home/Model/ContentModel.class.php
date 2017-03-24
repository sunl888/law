<?php
namespace Home\Model;
use Think\Model;

class ContentModel extends Model {


	/**
	 * 根据栏目ID，获取指定数量的新闻列表
	 *
	 * @param $class
	 * @param $amount
	 * @return $newsList
	 */
	public function getContent($class,$amount, $exceptContentId = null) {

        $classify = D('Class');
	    if($class['father_id']==0){
            $childIds = array_column($classify->getChildClassArr($class['class_id']), 'class_id');
            array_push($childIds, $class['class_id']);
            $condition['class_id'] = ['in', $childIds];

        }else{
            $condition['class_id'] = $class['class_id'];
        }

		if(!is_null($exceptContentId))
		{
            $condition['content_id'] = ['NEQ', intval($exceptContentId)];
        }

		$newsList = $this->where($condition)->order('sort_index asc, addtime desc')->limit($amount)->select();
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

    /**
     * 搜索
     * @param $keyword
     * @return mixed
     */
    public function search($keyword)
    {
        $condition['state'] = ['eq','publish'];
        $condition['title'] = array('like',"%$keyword%");
        return $this->where($condition)->select();
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