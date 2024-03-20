<?php

namespace core\user\controllers;

use core\user\model\Model;

class SearchController extends BaseUser
{
    protected function inputData()
    {
        parent::inputData(); // TODO: Change the autogenerated stub
        $goods = $this->search();
        $pages = $this->model->getPagination();
        $this->template = TEMPLATE . 'catalog';
        $data['name'] = 'Результаты поиска ' . (!empty($_GET['search']) ? 'по запросу <i>' . $_GET['search'] . '</i>' : '');
        $dontShowAside = true;
        return  compact('goods', 'data', 'dontShowAside', 'pages');
    }
    public function search() {
        !$this->model && $this->model = Model::instance();
        $search = $this->clearStr( $_GET['search'] ?? '' );
        $data = [];
        if ($search) {
            $goodsIds = $this->model->searchGoodsIds($search);
            $page = $this->clearNum($_GET['page'] ?? 1) ?:1;
            $pagination = [
                'qty' => $_SESSION['quantities'] ?? QTY,
                'page' => $page
            ];
            if ($goodsIds) {
                $data = $this->model->getGoods([
                    'where' => ['id' => $goodsIds, 'visible' => 1],
                    'operand' => ['IN'],
                    'pagination' => $pagination
                ], ...[false, false]);
            }
        }
        return $data;
    }
}