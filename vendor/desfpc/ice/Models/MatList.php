<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Material List Class
 *
 */

namespace ice\Models;

use ice\DB\DB;

class MatList extends ObjectList
{
    /**
     * MatList constructor.
     *
     * @param DB $DB
     * @param array|null $conditions
     * @param array|null $sort
     * @param int $page
     * @param int $perpage
     * @param int $cachetime
     * @param array|null $settings
     */
    public function __construct(DB $DB, ?array $conditions = null, ?array $sort = null, $page = 1, $perpage = 20, $cachetime = 0, $settings = null)
    {
        $this->doConstruct($DB, 'materials', $conditions, $sort, $page, $perpage, $cachetime, $settings);
    }

    /**
     * @inheritDoc
     *
     * @return string
     */
    public function moreQuery()
    {
        $query = ',   (SELECT mt.name FROM material_types mt WHERE mt.id = dbtable.material_type_id) material_type_name,
                    (SELECT fm.file_id FROM material_files fm, files f 
                        WHERE f.filetype = \'image\' AND f.id = fm.file_id AND fm.material_id = dbtable.id 
                        ORDER BY fm.ordernum ASC, f.id ASC
                        LIMIT 1) favicon
        ';
        return $query;
    }

    /**
     * Выводит все активные записи-материалы по имени типа материала
     *
     * @param string $type
     * @return false
     */
    public function getTypeActiveRecords(string $type)
    {
        //получение ID типа материала
        $type = $this->DB->mysqli->real_escape_string($type);
        $query = "SELECT id FROM material_types WHERE id_char = '$type'";
        if (!$res = $this->DB->query($query)){
            return false;
        }
        $typeId = $res[0]['id'];

        //выборка списка товаров в заказе
        $this->conditions = [
            [
                'string' => false,
                'type' => '=',
                'col' => 'material_type_id',
                'val' => $typeId
            ],
            [
                'string' => false,
                'type' => '=',
                'col' => 'status_id',
                'val' => 1
            ]
        ];
        //сортировка результата запроса
        $this->sort = [
            ['col' => 'material_type_name', 'sort' => 'ASC'],
            ['col' => 'name', 'sort' => 'ASC']
        ];
        $this->page = 1;
        $this->perpage = null;
        return $this->getRecords();
    }
}