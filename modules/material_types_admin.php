<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 *
 * Module for Material Types administration
 *
 */

use ice\Models\MatExtraParams;
use ice\Models\MatType;
use ice\Models\MatTypeList;
use ice\Models\TemplateList;
use ice\Routes\PathParser;

//секурность
if (!$this->moduleAccess()) {
    return;
}

$this->moduleData = new stdClass();

$this->moduleData->title = $this->settings->site->title;
$this->moduleData->H1 = 'Материалы - структура';
$this->moduleData->errors = array();
$this->moduleData->success = array();

$this->getRequestValues(['id', 'mode', 'action', 'name', 'name_en', 'name_de', 'id_char', 'parent_id_name', 'parent_id', 'ordernum', 'sitemenu',
    'template_list', 'template_item', 'template_admin', 'prepare_list', 'prepare_item', 'list_items', 'shop_ifgood', 'shop_ifstore']);

//списки шаблонов для редактирования и добавления
$listTemplates = new TemplateList($this->DB, [[
    'col' => 'type',
    'type' => '=',
    'val' => 2,
    'string' => false
]], [['col' => 'name', 'sort' => 'ASC']]);
$listTemplates->getRecords();
$this->moduleData->listTemplates = $listTemplates->records;

$detailTemplates = new TemplateList($this->DB, [[
    'col' => 'type',
    'type' => '=',
    'val' => 1,
    'string' => false
]], [['col' => 'name', 'sort' => 'ASC']]);
$detailTemplates->getRecords();
$this->moduleData->detailTemplates = $detailTemplates->records;

$adminTemplates = new TemplateList($this->DB, [[
    'col' => 'type',
    'type' => '=',
    'val' => 3,
    'string' => false
]], [['col' => 'name', 'sort' => 'ASC']]);
$adminTemplates->getRecords();
$this->moduleData->adminTemplates = $adminTemplates->records;


switch ($this->values->mode) {

    //добавление дополнительного поля
    case 'addExtraParam':

        //формируем переменные для добавления поля
        $this->getRequestValues(['mtype_id', 'name', 'value_type', 'value_mtype']);

        //пробуем создать дополнительное поле
        $extra = new MatExtraParams($this->DB);

        if ($extra->createRecord($extra->paramsFromValues($this->values))) {
            $this->setFlash('success', ['Дополнительное поле <strong>' . $this->values->name . '</strong> успешно создано']);
        } else {
            $this->setFlash('errors', ['Ошибка сохранения дополнительного поля']);
        }

        //visualijop($this);

        $id = $this->values->mtype_id;
        $this->unsetValues();
        $this->redirect('/admin/material_types_admin/?mode=edit&id=' . $id);

        break;

    //добавление
    case 'add':

        $this->moduleData->H1 .= ' - добавить';

        //проверяем, что форма отправлена
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            //пробуем создать тип материала
            $matType = new MatType($this->DB);

            if ($matType->createRecord($matType->paramsFromValues($this->values))) {
                $this->moduleData->success[] = 'Тип материала <strong>' . $this->values->name . '</strong> успешно создан.';
                $this->unsetValues();
                $this->setFlash('success', ['Тип материала <strong>' . $this->values->name . '</strong> успешно создан']);
                $this->redirect('/admin/material_types_admin/?mode=edit&id=' . $matType->id);
            } else {
                $this->moduleData->errors[] = 'Не удалось сохранить тип материала';
            }

        }


        break;

    //изменение
    case 'edit':

        if ($this->values->id == '') {
            $this->module['name'] = '404';
            $this->loadModule();
        }

        $this->moduleData->H1 .= ' - изменить';

        //получаем тип материала по id
        $matType = new MatType($this->DB, intval($this->values->id));
        if (!$matType->getRecord(intval($this->values->id))) {
            $this->module['name'] = '404';
            $this->loadModule();
        }

        $this->moduleData->matType = $matType;

        //сохранение формы
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($matType->updateRecord($matType->paramsFromValues($this->values))) {

                //чистими кэши URL-ов типов материала
                $parser = new PathParser($this->DB, [], $this->settings);

                $query = 'SELECT id FROM material_types';
                if ($res = $this->DB->query($query)) {
                    foreach ($res as $row) {
                        $parser->delMTUCache($row['id']);
                    }
                }

                //чистим кэш списка материалов
                $parser->delMTTCache();

                $this->moduleData->success[] = 'Тип материала <strong>' . $this->values->name . '</strong> успешно изменен.';
            } else {
                $this->moduleData->errors[] = 'Не удалось сохранить тип материала';
            }
        }

        if (!is_null($matType->params['parent_id']) && $matType->params['parent_id'] != '' && $matType->params['parent_id'] != 0) {
            $parentType = new MatType($this->DB, $matType->params['parent_id']);
            $parentType->getRecord();
            $this->moduleData->parentName = $parentType->params['name'];
        } else {
            $this->moduleData->parentName = 'Корень';
        }


        break;

    //удаление
    case 'delete':

        $this->moduleData->H1 .= ' - удалить';

        break;

    //список типов материалов сайта
    default:

        //получаем дерево типов материалов (всех)
        $materialTypes = new MatTypeList($this->DB, null, null, 1, null, 0, null);
        $this->moduleData->materialTypes = $materialTypes->getRecordsTree('all');

        break;

}