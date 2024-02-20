<?php
namespace core\user\model;
use core\base\controllers\Singleton;
use core\base\models\BaseModel;

class Model extends BaseModel
{
    use Singleton;
    public function getGoods($set = [], &$catalogFilters=null, &$catalogPrices=null ) {
        if (empty($set['join_structure'])) {
            $set['join_structure'] = true;
        }
        if (empty($set['where'])) {
            $set['where'] = [];
        }
        $goodsColumns = $this->showColumns('goods');
        if (empty($set['order'])) {
            $set['order'] = [];
            if ( !empty($goodsColumns['parent_id']) ) {
                $set['order'][] = 'parent_id';
            }
            if ( !empty($goodsColumns['price']) ) {
                $set['order'][] = 'price';
            }
        }
        $goods = $this->get('goods', $set);
        if ($goods) {
            unset($set['join'], $set['join_structure'], $set['pagination']);
            if ($catalogPrices !== false && !empty($goodsColumns['price'])){
                $set['fields'] = ['MIN(price) as min_price', 'MAX(price) as max_price'];
                $catalogPrices = $this->get('goods', $set);
                if (!empty($catalogPrices)) {
                    $catalogPrices = $catalogPrices[0];
                }
            }
            if ($catalogPrices !== false && in_array('filters', $this->showTables())){
                $parentFiltersFields = [];
                $filtersWhere = [];
                $filtersOder = [];
                $filterColumns = $this->showColumns('filters');
                foreach ($filterColumns as $name => $item) {
                    if (!empty($item) && is_array($item)) {
                        $parentFiltersFields[] = "$name as f_{$name}";
                    }
                }
                if ( !empty($filterColumns['visible']) ) {
                    $filtersWhere['visible'] = 1;
                }
                if ( !empty($filterColumns['menu_position']) ) {
                    $filtersOder[] = 'menu_position';
                }
                $filters = $this->get('filters', [
                    'where' => $filtersWhere,
                    'join' => [
                        'filters f_name' => [
                            'type' => 'INNER',
                            'fields' => $parentFiltersFields,
                            'where' => $filtersWhere,
                            'on' => ['parent_id', 'id']
                        ],
                        'goods_filters' => [
                            'on' => [
                                'table' => 'filters',
                                'fields' => ['id', 'filters_id']
                            ],
                            'where' => [
                                'goods_id' => $this->get('goods', [
                                    'fields' => [$goodsColumns['id_row']],
                                    'where' => $set['where'] ?? null,
                                    'return_query' => true
                                ])
                            ],
                            'operand' => ['IN'],
                        ]
                    ]
                ]);
            }
        }
        $a = 1;

    }
}