<?php

namespace core\user\controllers;

use core\admin\controllers\CreatesitemapController;
use core\base\controllers\BaseAjax;
use libraries\FilesEdit;

class AjaxController extends BaseUser
{
    public function ajax(): string
    {
        if (isset($this->ajaxData['ajax'])) {
            $this->inputData();
            foreach ($this->ajaxData as $key => $item) {
                $this->ajaxData[$key] = $this->clearStr($item);
            }
            switch ($this->ajaxData['ajax']) {
                case 'catalog_quantities':
                    $qty = $this->clearNum($this->ajaxData['qty'] ?? 0);
                    $qty && $_SESSION['quantities'] = $qty;
                    break;
            }
        }
        return json_encode(['success' => 0, 'message' => 'No Ajax variable']);
    }
}