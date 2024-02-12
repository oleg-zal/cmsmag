<?php

namespace core\admin\controllers;

use core\base\controllers\BaseAjax;

class AjaxController extends BaseAdmin
{
    public function ajax(): string {
        if (isset($this->ajaxData['ajax'])) {
            $this->execBase();
            foreach ($this->ajaxData as $key => $item) {
                $this->ajaxData[$key] = $this->clearStr($item);
            }
            switch ($this->ajaxData['ajax']) {
                case 'sitemap':
                    return (new CreatesitemapController())->inputData($this->ajaxData['links_counter'], false);
                    break;
                case 'editData':
                    $_POST['return_id'] = true;
                    $this->checkPost();
                    return json_encode(['success' => 1]);
                    break;
                case 'change_parent':
                    return $this->changeParent();
                    break;
            }
        }
        return json_encode(['success' => 0, 'message' => 'No Ajax variable']);
    }
    protected function changeParent() {

        return $this->model->get($this->ajaxData['table'], [
            'fields' => ['COUNT(*) as count'],
            'where' => [ 'parent_id' => $this->ajaxData['parent_id'] ],
            'no_concat' => true
        ])[0]['count'] + $this->ajaxData['iteration'];
    }
}