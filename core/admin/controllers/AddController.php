<?php

namespace core\admin\controllers;

use core\base\settings\Settings;

class AddController extends BaseAdmin
{
    protected $action = "add";

    protected function inputData(){
        if (!$this->userId) $this->execBase();
        $this->checkPost();
        $this->createTableData();
        $this->createForeignData();
        $this->createMenuPosition();
        $this->createRadio();
        $this->createOutputData();
        $this->createManyToMany();
        $this->expansion();

    }
    protected function manyAdd() {
        $fields = [
            0 => ['name' => 'Карты', 'content' => 'Карты игральные', 'img' => '1.jpg'],
            1 => ['name' => 'Карты-2', 'content' => 'Карты игральные-2', 'img' => '2.jpg'],
        ];
        $this->model->add('goods', [
            'fields' =>$fields
        ]);
    }
}