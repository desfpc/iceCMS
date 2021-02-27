<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Material Extra Parameter Values List Class
 *
 */

namespace ice\Models;

use ice;

class iceMatExtraValuesList extends ice\iceObjectList {

    public function moreQuery(){
        $query=', (SELECT m.name FROM materials m WHERE m.id = dbtable.value_mat) value_mat_name,
                (SELECT e.name FROM material_extra_params e WHERE e.id = dbtable.param_id) param_name,
                (SELECT e1.value_type FROM material_extra_params e1 WHERE e1.id = dbtable.param_id) value_type,
                (SELECT e2.value_mtype FROM material_extra_params e2 WHERE e2.id = dbtable.param_id) value_mtype';
        return $query;
    }

    public function __construct(ice\iceDB $DB, $conditions=null, $sort=null, $page=1, $perpage=20, $cachetime=0, $settings=null)
    {
        $this->doConstruct($DB, 'material_extra_values', $conditions, $sort, $page, $perpage, $cachetime, $settings);
    }

}