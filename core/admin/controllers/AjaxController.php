<?php

namespace core\admin\controllers;

use core\base\controllers\BaseAjax;

class AjaxController extends BaseAjax
{
    protected function ajax(): string {
        if (isset($this->ajaxData['ajax'])) {
            switch ($this->ajaxData['ajax']) {
                case 'sitemap':
                    return (new CreatesitemapController())->inputData($this->ajaxData['links_counter'], false);
            }
        }
        return json_encode(['success' => 0, 'message' => 'No Ajax variable']);
    }
}