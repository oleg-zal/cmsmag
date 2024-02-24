<?php

namespace core\user\controllers;

use core\base\exceptions\RouteException;

class CatalogController extends BaseUser
{
    protected function inputData() {
        parent::inputData();

        $data = [];
        if (!empty($this->parameters['alias'])) {
            $data = $this->model->get('category', [
                'where' => ['alias' => $this->parameters['alias'], 'visible' => 1],
                'limit' => 1
            ]);
            if (!$data) {
                throw new RouteException("Не найдены записи  в таблице 
                catalog по ссылке {$this->parameters['alias']}");
            }
            $data = $data[0];

        }
        $where = ['visible' => 1];
        if ($data) {
            $where = ['parent_id' => $data['id']];
        }
        else {
            $data['name'] = 'Каталог';
        }
        $catalogFilters = $catalogPrices = $orderDb = null;
        $order = $this->createCatalogOrder($orderDb);
        $goods = $this->model->getGoods([
            'where' => $where,
            'order' => $orderDb['order'],
            'order_direction' => $orderDb['order_direction']
        ], $catalogFilters, $catalogPrices);
        return compact('data', 'goods', 'catalogFilters', 'catalogPrices', 'order');
    }
    protected function createCatalogOrder(&$orderDb) {
        $order = [
            'Цене' => 'price_asc',
            'Названию' => 'name_asc'
        ];
        $orderDb = ['order' => null, 'order_direction' => null];
        if ( !empty($_GET['order']) ) {
            $orderArr = preg_split('/_+/', $_GET['order'], 0, PREG_SPLIT_NO_EMPTY);
            $goodsColumns = $this->model->showColumns('goods');
            if (!empty($goodsColumns[$orderArr[0]])) {
                $orderDb['order'] = $orderArr[0];
                $orderDb['order_direction'] = $orderArr[1] ?? 'asc';
                foreach ($order as $key => $item) {
                    if (strpos($item, $orderDb['order']) === 0) {
                        $direction = $orderDb['order_direction'] === 'asc' ? 'desc' : 'asc';
                        $order[$key] = $orderDb['order'] . '_' . $direction;
                        break;
                    }
                }
            }
        }
        return $order;
    }
}