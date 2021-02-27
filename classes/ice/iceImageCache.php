<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Image Cache Class
 *
 */

namespace ice;

use ice\DB\DB;
use ice\DB\QueryBuilder;

class iceImageCache extends iceObject {
    //подменяем создание объекта - прописываем железно целевую таблицу
    public function __construct(DB $DB, $id=null, $settings=null)
    {
        $this->doConstruct($DB, 'image_caches', $id, $settings);
    }

    //создание новой записи
    public function createRecord($params = null){

        if(!is_null($params) && is_array($params))
        {
            $this->params = $params;
        }

        //формируем запрос для создания записи
        $qbuilder=new QueryBuilder($this->DB, $this->cols, $this->params, 'image_caches');
        $query=$qbuilder->insert();

        if($res=$this->DB->query($query))
        {
            //получаем и возвращаем идентификатор записи
            return true;
        }
        return false;

    }

    public function getWatermarkData() {

        if($this->params['watermark'] == 0){
            $this->params['watermark_data']=['name' => 'нет'];
        }
        else {
            $query = 'SELECT name FROM files WHERE id = '.$this->params['watermark'];
            if($res=$this->DB->query($query))
            {
                $this->params['watermark_data']=$res[0];
            }
        }
    }

    //расширяем стиандартный метод - к полям БД добавляем связанные данные
    public function fullRecord(){
        $this->getWatermarkData();
    }
}