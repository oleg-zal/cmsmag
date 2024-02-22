<?php
namespace core\user\controllers;
use core\base\controllers\BaseController;
use core\user\model\Model;

class IndexController extends BaseUser
{
    /**
     * @throws \core\base\exceptions\RouteException
     */
    protected function inputData(){
        parent::inputData();
        $sales = $this->model->get('sales', [
            'where' => ['visible' => 1],
            'order' => ['menu_position']
        ]);
        $advantages = $this->model->get('advantages', [
            'where' => ['visible' => 1],
            'order' => ['menu_position'],
            'limit' => 6
        ]);
        $news = $this->model->get('news', [
            'where' => ['visible' => 1],
            'order' => ['date'],
            'order_direction' => 'DESC',
            'limit' => 3
        ]);

        $arrHits = [
            'hit' => [
                'name' => 'Хиты продаж',
                'icon' => '<svg><use xlink:href="' . PATH . TEMPLATE . 'assets/img/icons.svg#hit"></use></svg>'
            ],
            'hot' => [
                'name' => 'Горячие предложения',
                'icon' => '<svg><use xlink:href="' . PATH . TEMPLATE . 'assets/img/icons.svg#hot"></use></svg>'
            ],
            'sale' => [
                'name' => 'Распродажа',
                'icon' => '%'
            ],
            'new' => [
                'name' => 'Новинки',
                'icon' => 'new'
            ],

        ];
        $goods = [];
        foreach ($arrHits as $type => $item) {
            $goods[$type] = $this->model->getGoods([
                'where' => [$type => 1, 'visible' => 1],
                'limit' => 6
            ]);
        }
        return compact('sales', 'arrHits', 'goods', 'advantages', 'news');
    }
    protected function testRequest() {
        $model = Model::instance();
        $res = $model->get('goods', [
            'where' => ['id' => '1,2'],
            'operand' => ['IN'],
            'join' => [
                'goods_filter' => ['on' => ['id', 'goods_id']],
                'filters f' => [
                    'fields' => ['name as filter_name', 'content'],
                    'on' => ['filter_id', 'id']
                ],
                [
                    'table' => 'filters',
                    'on' => ['parent_id', 'id']
                ]
            ],
            'join_structure' => true,
            'order' => 'id',
            'order_direction' => 'ASC'
        ]);
        return $res;
    }

}