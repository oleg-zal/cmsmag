<?php

namespace core\admin\controllers;

use core\base\settings\Settings;

class AddController extends BaseAdmin
{
    protected function inputData(){
        if (!$this->userId) $this->execBase();
        $this->createTableData();
        $this->createForeignData();
        $this->createOutputData();
        //$this->model->showForeignKeys($this->table);
    }
    protected function createForeignData($settings = false) {
        if (!$settings) {
            $settings = Settings::instance();
        }
        $rootItems = $settings::get('rootItems');
        $keys = $this->model->showForeignKeys($this->table);
        if ($keys) {
            foreach ($keys as $item) {
                if (in_array($this->table, $rootItems['tables'])) {
                    $this->foreignData[$item['COLUMN_NAME']][0]['id'] = 0;
                    $this->foreignData[$item['COLUMN_NAME']][0]['name'] = $rootItems['name'];
                }
                $columns = $this->model->showColumns($item['REFERENCED_TABLE_NAME']);
                $name = '';
                if ($columns['name']) {
                    $name = 'name';
                } else {
                    foreach($columns as $key => $value) {
                        if (strpos($key, 'name') !== 'name') {
                            $name = "$key as name";
                        }
                    }
                    if (!$name) {
                        $name = "{$columns['id_row']} as name";
                    }
                }
                if ($this->data) {
                    if ($item['REFERENCED_TABLE_NAME'] === $this->table) {
                        $where[$this->columns['id_row']] = $this->data[$this->columns['id_row']];
                        $operand[] = '<>';
                    }
                }
                $foreign[$item['COLUMN_NAME']] = $this->model->get($item['REFERENCED_TABLE_NAME'], [
                    'fields' => [$item['REFERENCED_COLUMN_NAME'] . ' as id', $name],
                    'where' => $where,
                    'operand' => $operand
                ]);
                if ($foreign[$item['COLUMN_NAME']]) {
                    if ($this->foreignData[$item['COLUMN_NAME']]) {
                        foreach ($foreign[$item['COLUMN_NAME']] as $value) {
                            $this->foreignData[$item['COLUMN_NAME']][] = $value;
                        }
                    } else {
                        $this->foreignData[$item['COLUMN_NAME']] = $foreign[$item['COLUMN_NAME']];
                    }
                }
            }
        } elseif($this->columns['parent_id']) {
            if (in_array($this->table, $rootItems['tables'])) {
                $this->foreignData['parent_id'][0]['id'] = 0;
                $this->foreignData['parent_id'][0]['name'] = $rootItems['name'];
            }
            $name = '';
            if ($this->columns['name']) {
                $name = 'name';
            } else {
                foreach($this->columns as $key => $value) {
                    if (strpos($key, 'name') !== false) {
                        $name = "$key as name";
                    }
                }
                if (!$name) {
                    $name = "{$this->columns['id_row']} as name";
                }
            }
            if ($this->data) {
                $where[$this->columns['id_row']] = $this->data[$this->columns['id_row']];
                $operand[] = '<>';
            }
            $foreign = $this->model->get($this->table, [
                'fields' => [$this->columns['id_row'] . ' as id', $name],
                'where' => $where,
                'operand' => $operand
            ]);
            if ($foreign) {
                if ($this->foreignData['parent_id']) {
                    foreach ($foreign as $value) {
                        $this->foreignData['parent_id'][] = $value;
                    }
                } else {
                    $this->foreignData['parent_id'] = $foreign;
                }
            }
        }
        return;
    }
}