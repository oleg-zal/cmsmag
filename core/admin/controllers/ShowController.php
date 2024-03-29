<?php

namespace core\admin\controllers;

use core\base\settings\Settings;
use core\base\settings\ShopSettings;

class ShowController extends BaseAdmin
{
    protected function inputData() {
        if (!$this->userId) $this->execBase();
        $this->createTableData();
        $this->createData(['fields' => []]);
        //print_arr($this->data);
        return $this->expansion(get_defined_vars());
    }

    protected function createData($arr=[]) {
        $fields = [];
        $order = [];
        $order_direction = [];

        if(!$this->columns['id_row']) {
            $this->data = [];
            return;
        }
        $fields[] = $this->columns['id_row'] . ' as id';
        if ( !empty($this->columns['name']) ) $fields['name'] = 'name';
        if ( !empty($this->columns['img'] )) $fields['img'] = 'img';
        if (count($fields) < 3) {
            foreach ($this->columns as $key => $item) {
                if (empty($fields['name']) && strpos($key, 'name') !==false) {
                    $fields['name'] = $key . ' as name';
                }
                if (empty($fields['img']) && strpos($key, 'img') === 0) {
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
                $order_direction[] = 'DESC';
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

        $this->data = $this->model->get($this->table, [
            'fields' => $fields,
            'order' => $order,
            'order_direction' => $order_direction
        ]);

    }
}