<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Extra Parameters for Material Class
 *
 */

namespace ice\Models;

use ice\iceObject;
use ice\iceDB;

class MatExtraParams extends iceObject {

    //подменяем создание объекта - прописываем железно целевую таблицу
    public function __construct(iceDB $DB, $id=null, $settings=null)
    {
        $this->doConstruct($DB, 'material_extra_params', $id, $settings);
    }

    public function moreQuery(){
        $query=', (SELECT p.name FROM material_types p WHERE p.id = dbtable.value_mtype) value_mtype_name';
        return $query;
    }

    public function afterCreateRecord(){

        //удаляем кэш типа материала
        $mType = new matType($this->DB, $this->params['mtype_id']);
        if($mType->getRecord()){
            $mType->uncacheRecord();
        }

        return true;
    }

}