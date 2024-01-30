<?php

namespace core\admin\controllers;

use core\base\controllers\BaseController;
use core\base\exceptions\RouteException;
use core\base\settings\Settings;
use core\user\model\Model;

abstract class BaseAdmin extends BaseController
{
    protected $model;

    protected $table;
    protected $columns;
    protected $data;

    protected $menu;
    protected $title;

    protected function inputData() {
        $this->init(true);
        $this->title = 'VG engine';
        if (!$this->model) {
            $this->model = Model::instance();
        }
        if (!$this->menu) {
            $this->menu = Settings::get('projectTables');
        }
        $this->sendNoCacheHeaders();
    }
    protected function outputData() {

    }
    protected function sendNoCacheHeaders() {
        header("Last-Modified: " . gmdate("D, d m Y H:i:s"));
        header("Cache-Control: no-cache, must-revalidate");
        header("Cache-Control: max-age=0");
        header("Cache-Control: post-check=0,pre-check=0");
    }
    protected function execBase() {
        self::inputData();
    }
    protected function createTableData() {
        if (!$this->table) {
            if ($this->parameters) {
                $this->parameters = array_keys($this->parameters)[0];
            } else {
                $this->table = Settings::get('defaultTable');
            }
        }
        $this->columns = $this->model->showColumns($this->table);
        if(!$this->columns) {
            new RouteException('Не найдены поля в таблице - ' . $this->table, 2);
        }

    }
    protected function createData($arr=[], $add=true) {
        $fields = [];
        $order = [];
        $order_direction = [];
        if ($add) {
            if(!$this->columns['id_row']) {
                $this->data = [];
                return;
            }
            $fields[] = $this->columns['id_row'] . ' as id';
            if ($this->columns['name']) $fields['name'] = 'name';
            if ($this->columns['img']) $fields['img'] = 'img';
            if (count($fields) < 3) {
                foreach ($this->columns as $key => $item) {
                    if (!$fields['name'] && strpos($key, 'name') !==false) {
                        $fields['name'] = $key . ' as name';
                    }
                    if (!$fields['img'] && strpos($key, 'img') === 0) {
                        $fields['img'] = $key . ' as img';
                    }
                }
            }
            if (!empty($arr['fields'])) {
                if (is_array($arr['fields'])) {
                    $fields = Settings::instance()->arrayMergenRecusive($fields, $arr['fields']);
                } else {
                    $fields[] = $arr['fields'];
                }
            }
            if ($this->columns['parent_id']) {
                if (!in_array('parent_id', $fields)) $fields[] = 'parent_id';
                $order[] = 'parent_id';
            }
            if ($this->columns['menu_position']) {
                $order[] = 'menu_position';
            } elseif ($this->columns['date']) {
                if ($order) {
                    $order_direction = ['ASC', 'DESC'];
                } else {
                    $order_direction = ['DESC'];
                }
                $order[] = 'date';
            }
            if (!empty($arr['order'])) {
                if (is_array($arr['order'])) {
                    $order = Settings::instance()->arrayMergenRecusive($order, $arr['order']);
                } else {
                    $order[] = $arr['order'];
                }
            }
            if (!empty($arr['order_direction'])) {
                if (is_array($arr['order_direction'])) {
                    $order_direction = Settings::instance()->arrayMergenRecusive($order_direction, $arr['order_direction']);
                } else {
                    $order_direction[] = $arr['order_direction'];
                }
            }

        } else {
            if (!$arr) {
                $this->data = [];
                return;
            }
            $fields = $arr['fields'];
            $order = $arr['order'];
            $order_direction = $arr['order_direction'];
        }
        $this->data = $this->model->get($this->table, [
            'fields' => $fields,
            'order' => $order,
            'order_direction' => $order_direction
        ]);

    }
    protected function expansion($args=[]) {
        $fileName = explode('_', $this->table);
        $className = '';
        foreach ( $fileName as $item) {
            $className .= ucfirst($item);
        }
        $class = Settings::get('expansion') . $className . 'Expansion';
        if (is_readable($_SERVER['DOCUMENT_ROOT'] . PATH . "{$class}.php" )) {
            $class = str_replace('/', '\\', $class);
            $exp = $class::instance();
            $res = $exp->expansion($args);
        }
    }
}