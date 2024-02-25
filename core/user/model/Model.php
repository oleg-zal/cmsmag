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
                                    'operand' => $set['operand'] ?? null,
                                    'return_query' => true
                                ])
                            ],
                            'operand' => ['IN'],
                        ]
                    ]
                ]);
                if ($goodsColumns['discount']) {
                    foreach ($goods as $key => $item) {
                        $this->applyDiscount($goods[$key], $item['discount']);
                    }
                }
                if ($filters) {
                    $filterIds = implode(',', array_unique(array_column($filters, 'id')) );
                    $goodIds = implode(',', array_unique(array_column($filters, 'goods_id')) );
                    $query = "SELECT `filters_id` as id, COUNT(goods_id) as count FROM goods_filters
                                    where filters_id IN ($filterIds) AND goods_id IN ($goodIds) GROUP by filters_id";
                    $goodsCountDB = $this->query($query);
                    $goodsCount = [];
                    if ($goodsCountDB) {
                        foreach ($goodsCountDB as $item) {
                            $goodsCount[$item['id']] = $item;
                        }
                    }
                    $catalogFilters = [];
                    foreach ($filters as $item) {
                        $parent = [];
                        $child = [];
                        foreach ($item as $row => $rowValue) {
                            if (strpos($row, 'f_') === 0 ) {
                                $name = preg_replace('/^f_/', '', $row);
                                $parent[$name] = $rowValue;
                            }
                            else {
                                $child[$row] = $rowValue;
                            }
                        }
                        if ( isset( $goodsCount[$child['id']]['count'] ) ) {
                            $child['count'] = $goodsCount[$child['id']]['count'];
                        }
                        if ( empty( $catalogFilters[$parent['id']] )) {
                            $catalogFilters[$parent['id']] = $parent;
                            $catalogFilters[$parent['id']]['values'] = [];
                        }
                        $catalogFilters[$parent['id']]['values'][$child['id']] = $child;
                        if ( isset( $goods[$item['goods_id']] ) ) {
                            if ( empty( $goods[$item['goods_id']]['filters'][$parent['id']] )) {
                                $goods[$item['goods_id']]['filters'][$parent['id']] = $parent;
                                $goods[$item['goods_id']]['filters'][$parent['id']]['values'] = [];
                            }
                            $goods[$item['goods_id']]['filters'][$parent['id']]['values'][$item['id']] = $child;
                        }
                    }
                }
            }
        }
        return $goods ?? null;
    }
    public function applyDiscount(&$data, $discount) {
        if ($discount) {
            $data['old_price'] = $data['price'];
            $data['discount'] = $discount;
            $data['price'] = $data['old_price'] - $data['old_price'] / 100 * $discount;
        }
    }
}