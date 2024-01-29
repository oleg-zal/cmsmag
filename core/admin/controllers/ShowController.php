<?php

namespace core\admin\controllers;

class ShowController extends BaseAdmin
{
    protected function inputData() {
        $this->execBase();
        $this->createTableData();
        $this->createData(['fields' => ['content']]);

        exit();
    }

    protected function outputData() {

    }
}