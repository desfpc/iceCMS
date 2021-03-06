<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Material Extra Parameter Values Class
 *
 */

namespace ice\Models;

use ice\DB\DB;

class MatExtraValues extends Obj
{

    public function __construct(DB $DB, $id = null, $settings = null)
    {
        $this->doConstruct($DB, 'material_extra_values', $id, $settings);
    }

    //подменяем создание объекта - прописываем железно целевую таблицу

    public function moreQuery()
    {
        $query = ', (SELECT m.name FROM materials m WHERE m.id = dbtable.value_mat) value_mat_name,
                (SELECT e.name FROM material_extra_params e WHERE e.id = dbtable.param_id) param_name,
                (SELECT e1.value_type FROM material_extra_params e1 WHERE e1.id = dbtable.param_id) value_type,
                (SELECT e2.value_mtype FROM material_extra_params e2 WHERE e2.id = dbtable.param_id) value_mtype';
        return $query;
    }

    public function afterCreateRecord()
    {

        //удаляем кэш типа материала
        if ($this->params['value_type'] == 'value_mat') {
            $mat = new Mat($this->DB, $this->params['value_mat']);
            if ($mat->getRecord()) {
                $mat->uncacheRecord();
            }
        }

        return true;
    }

}