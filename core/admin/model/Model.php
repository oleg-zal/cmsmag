<?php

namespace core\admin\model;

use core\base\controllers\Singleton;
use core\base\models\BaseModel;

class Model extends BaseModel
{
    use Singleton;
    private function __construct() {
        $this->connect();
    }
}