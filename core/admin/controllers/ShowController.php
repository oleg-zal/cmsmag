<?php

namespace core\admin\controllers;

class ShowController extends BaseAdmin
{
    protected function inputData() {
        $this->execBase();
        $this->createTableData();
        $this->createData(['fields' => ['content', 'menu_position']]);
        //print_arr($this->data);
        exit();
    }

    protected function outputData() {

    }
}