<?php

namespace core\admin\controllers;

class ShowController extends BaseAdmin
{
    protected function inputData() {
        $this->execBase();
        $this->createTableData();

        exit();
    }

    protected function outputData() {

    }
}