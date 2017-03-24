<?php
namespace Home\Controller;

class ContentController extends BaseController {

	public function index()
    {
        if (empty($_GET['id'])) {
            return $this->error("缺少指定参数!");
        }

        $id = intval($_GET['id']);
        $contentModel = D ('Content');
        $content = $contentModel->find($id);

        if (is_null($content)) {
            $this->error("您要访问的内容不存在!");
        }
        $this->display();
    }
}