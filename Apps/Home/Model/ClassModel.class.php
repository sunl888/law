<?php
namespace Home\Model;
use Think\Model;

class ClassModel extends Model{

    protected $tableName = 'class';
    protected static $parents = [];

	//返回指定ID对应的所有子栏目
	public function getChildClass($id) {

		$classIdArr = array();
		$condition['father_id'] = $id;
		$classArr = $this->where($condition)->select();

		foreach ($classArr as $k=>$v) {
			$classIdArr[] = $v['class_id'];
		}

		if (empty($classIdArr)) {
			$classIdArr[] = $id;
		}
		return $classIdArr;
	}

    public function getNav()
    {
        $condition['is_show'] = 1;
        $condition['is_nav'] = 1;
        //$condition['father_id'] = 0;
        $nav = $this->where($condition)
            ->order('sort_index asc')
            ->alias('c')
            ->join("__TEMPLATE__ t ON c.index_template = t.template_id")
            ->field('c.class_id,c.father_id,c.name as class_name,t.type,c.channel_id,t.url,c.content_template,c.index_template,t.template_id,t.name as template_name')
            ->select();
        $nav = array_column($nav, null, 'class_id');
        foreach ($nav as $item=>&$val){
            $this->templateId2Info($val);
            if($val['father_id'] != 0){
                $nav[ $val['father_id'] ]['child'][] = $val;
                unset($nav[$item]);
            }

        }
        unset($val);

        return $nav;
     }

    /**
     * 获取所有的父级栏目
     * @return array
     */
	public function getParents(){
        if(count(self::$parents)<=0){
            //todo ['is_nav'=>1]
            self::$parents = $this->where([['father_id'=>0],['is_show'=>1]])->order('sort_index asc')->select();
        }
        return self::$parents;
    }

	public function getChildClassArr($classId) {
		$condition['father_id'] = $classId;
		$condition['is_show'] = 1;
		$classArr = $this->where($condition)->order('sort_index asc')->select();
		$templates = $this->getTemplates();
		foreach ($classArr as &$v){
            $this->templateId2Info($v);
        }
        unset($v);
        return array_column($classArr, null, 'class_id');
	}
	public function templateId2Info(&$class)
    {
        $templates = $this->getTemplates();
        $class['index_template'] = $templates[$class['index_template']];
        $class['content_template'] = $templates[$class['content_template']];
    }
	/*public function scope($scope = '', $args = NULL)
    {
        return parent::scope($scope, $args); // TODO: Change the autogenerated stub
    }*/
    /**
     * 获取模板表中的所有数据(template_id最为数组的键)
     * @return array|null
     */
	public function getTemplates()
    {
        static $templates = null;
        if(is_null($templates)){
            $templates = M('template')->select();
            $templates = array_column($templates, null, 'template_id');
        }
        return $templates;
    }

}