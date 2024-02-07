<?php

namespace core\admin\controllers;

use core\base\controllers\BaseAjax;

class AjaxController extends BaseAjax
{
    protected function ajax(): string {
        return 'ADMIN AJAX';
    }
}