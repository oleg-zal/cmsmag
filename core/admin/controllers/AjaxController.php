<?php

namespace core\admin\controllers;

use core\base\controllers\BaseAjax;

class AjaxController extends BaseAdmin
{
    public function ajax(): string {
        if (isset($this->ajaxData['ajax'])) {
            $this->execBase();
            switch ($this->ajaxData['ajax']) {
                case 'sitemap':
                    return (new CreatesitemapController())->inputData($this->ajaxData['links_counter'], false);
                    break;
                case 'editData':
                    $_POST['return_id'] = true;
                    $this->checkPost();
                    return json_encode(['success' => 1]);
                    break;
            }
        }
        return json_encode(['success' => 0, 'message' => 'No Ajax variable']);
    }
}