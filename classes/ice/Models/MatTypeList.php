<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Material Type List Class
 *
 */

namespace ice\Models;

use ice\iceObjectList;
use ice\iceDB;

class MatTypeList extends iceObjectList {

    //дерево
    public function getRecordsTree($mode = 'all') {

        //режим отображения
        //all - все дерево
        //active - только видимые типы
        //mat - только видимые с активными материалами внутри
        switch ($mode) {
            case 'all':
                $this->conditions = null;
                break;
            case 'active':
                $this->conditions = [
                    0 => [
                        'string' => false,
                        'type' => '=',
                        'col' => 'sitemenu',
                        'val' => 1
                    ]
                ];
                break;
            case 'mat':

                //в типах материала проверки на язык не идет, так как для скорости работы, языки вписываются в поля таблицы...
                // Но - в данном случае проверяем материал, поэтому смотрим, есть ли язык в кондициях запроса
                $langQuery = '';
                if(is_array($this->conditions) && count($this->conditions) > 0) {
                    foreach ($this->conditions as $condition) {
                        if($condition['col'] == 'language'){
                            $langQuery = ' AND language = '.$condition['val'];
                        }
                    }
                }

                $this->conditions = [
                    0 => [
                        'string' => false,
                        'type' => '=',
                        'col' => 'sitemenu',
                        'val' => 1
                    ],
                    1 => [
                        'string' => false,
                        'type' => 'IN',
                        'col' => 'id',
                        'val' => 'SELECT material_type_id FROM materials WHERE status_id = 1'.$langQuery
                    ]
                ];
                break;
        }

        $this->sort = [
            0 => [
                'col' => 'parent_id',
                'sort' => 'ASC'
            ],
            1 => [
                'col' => 'ordernum',
                'sort' => 'ASC'
            ],
            2 => [
                'col' => 'id',
                'sort' => 'ASC'
            ]
        ];

        if($rows = $this->getRecords()) {

            if(count($rows) > 0) {

                //преобразуем массив записей в дерево записей
                $tree = [];
                foreach ($rows as $row) {

                    if(is_null($row['parent_id'])){
                        $row['parent_id'] = 'null';
                    }

                    $tree['types'][$row['id']] = $row;
                    $tree['childs'][$row['parent_id']][$row['id']] = $row;
                }
                return $tree;
            }

        }

        return false;
    }

    public function moreQuery(){
        $query=', (SELECT p.name FROM material_types p WHERE p.id = dbtable.parent_id) parent_name, 
        (SELECT ti.filename FROM templates ti WHERE ti.id = dbtable.template_item) template_item_name,
        (SELECT tl.filename FROM templates tl WHERE tl.id = dbtable.template_list) template_list_name,
        (SELECT ta.filename FROM templates ta WHERE ta.id = dbtable.template_admin) template_admin_name';
        return $query;
    }

    public function __construct(iceDB $DB, $conditions=null, $sort=null, $page=1, $perpage=20, $cachetime=0, $settings=null)
    {
        $this->doConstruct($DB, 'material_types', $conditions, $sort, $page, $perpage, $cachetime, $settings);
    }

}