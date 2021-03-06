<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Template Class
 *
 */

namespace ice\Models;

use ice\DB\DB;

class Template extends Obj
{
    //подменяем создание объекта - прописываем железно целевую таблицу
    public function __construct(DB $DB, $id = null, $settings = null)
    {
        $this->doConstruct($DB, 'templates', $id, $settings);
    }

    //получение название колонки в типе материала в зависимости от типа шаблона

    public function fullRecord()
    {
        $this->getMatTypes();
        $this->getTypeName();
    }

    //получение названия типа

    public function getMatTypes()
    {

        $query = 'SELECT * FROM material_types WHERE ' . $this->getColName() . ' = ' . $this->params['id'];

        if ($res = $this->DB->query($query)) {
            $this->params['mat_types'] = $res;
        } else {
            $this->params['mat_types'] = [];
        }
    }

    //получение типов материалов шаблона

    public function getColName()
    {
        $colName = false;
        switch ($this->params['type']) {
            case '1':
                $colName = 'template_item';
                break;
            case '2':
                $colName = 'template_list';
                break;
            case '3':
                $colName = 'template_admin';
                break;
        }
        return $colName;
    }

    //расширяем стандартный метод - к полям БД добавляем связанные данные

    public function getTypeName()
    {

        $colName = false;
        switch ($this->params['type']) {
            case '1':
                $colName = 'Шаблон материала';
                break;
            case '2':
                $colName = 'Шаблон списка материалов';
                break;
            case '3':
                $colName = 'Шаблон формы редактирования материала';
                break;
        }

        $this->params['type_name'] = $colName;

    }

}