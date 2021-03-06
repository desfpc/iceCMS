<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * List of Material Extra Parameters Class
 *
 */

namespace ice\Models;

use ice\DB\DB;

class MatExtraParamsList extends ObjectList
{

    public function __construct(DB $DB, $conditions = null, $sort = null, $page = 1, $perpage = 20, $cachetime = 0, $settings = null)
    {
        $this->doConstruct($DB, 'material_extra_params', $conditions, $sort, $page, $perpage, $cachetime, $settings);
    }

    public function moreQuery()
    {
        $query = ', (SELECT p.name FROM material_types p WHERE p.id = dbtable.value_mtype) value_mtype_name';
        return $query;
    }

}