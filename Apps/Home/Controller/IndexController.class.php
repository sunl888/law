<?php
namespace Home\Controller;
use Boris\Config;
use Think\Controller;

/**
 * 网站首页
 * 
 * @author webdd
 *
 */

class IndexController extends BaseController {

    private $prePage;

    public function __construct()
    {
        $this->prePage = is_null(C('prePage')) ? 8:C('prePage');
        return parent::__construct();
    }

    public function index(){

		$Content = D ('Content');

		//通知公告
        $classId = $this->getClassIdByName('通知公告');
		$noticeList = $Content->getContent($classId,30);
		$this->assign('noticeList',$noticeList);

		//教学管理
        $classId = $this->getClassIdByName('教学管理');
        $teaching = $Content->getContent($classId, $this->prePage);
        $this->assign('teaching', $teaching);

        //科学研究
        $science = $this->getClassIdByName('科学研究');
        $scientific = $Content->getContent($science, $this->prePage);
        $this->assign('scientific',$scientific);

        //政法要闻
        $news = $this->getClassIdByName('政法要闻');
        $newsList = $Content->getContent($news,$this->prePage);
        $this->assign('newsList',$newsList);

        //政法要闻里面的有图新闻
        $hasImgNews = $this->getClassIdByName('政法要闻');
        $imgNews = $Content->getImgNews($hasImgNews, 1);
        $article = $Content->getArticleById($imgNews['class_id']);
        $this->assign('article', $article);
        $this->assign('imgNews', $imgNews);

        //
        /*
		//获取焦点图
		$jdtList = $Content->getJdt(3,4);
		$this->assign('jdtList',$jdtList);

		//专题专栏
		$zhuantiList = $Content->getContent(5,11);
		$this->assign('zhuantiList',$zhuantiList);
		//获取专题专栏下面的子栏目
		$Class = D ('Class');
		$zhuantiClass = $Class->getChildClassArr(5);

		//获取专题专栏图片
		$Flink = M('Flink');
		$condition['type_id'] = 7;
		$zhuantiImg = $Flink->where($condition)->select();

		//循环加入专题专栏对应图片
		foreach ($zhuantiClass as $k=>$v) {
			$zhuantiClass[$k]['img'] = $zhuantiImg[$k]['logo'];
		}
		//p($zhuantiClass);
		$this->assign('zhuantiClass',$zhuantiClass);
        */

		$this->display();
    }
    
}