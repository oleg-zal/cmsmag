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
            'order' => 'RAND()'
        ]);
        parent::inputData();
    }

}