<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\iceRender $this
 *
 * Module for Materials administration
 *
 */

use ice\Models\iceMatType;
use ice\Models\iceMatTypeList;
use ice\Models\Mat;
use ice\Models\iceMatList;
use ice\iceFile;
use ice\Models\iceLanguageList;

//секурность
if(!$this->moduleAccess())
    {
        return;
    };

$this->moduleData=new stdClass();

$this->moduleData->title=$this->settings->site->title;
$this->moduleData->H1='Материалы администрирование';

//получение переменных
$this->getRequestValues(['mtype','action','mode','page']);

$this->moduleData->errors=[];
$this->moduleData->success=[];

//получаем дерево типов материалов (всех)
$materialTypes = new iceMatTypeList($this->DB, null, null, 1, null, 0, null);
$this->moduleData->materialTypes = $materialTypes->getRecordsTree('all');

$this->moduleData->materialTypes[0][] = [
    'id' => 'all',
    'name' => 'Все',
    'sitemenu' => 0,
    'parent_id' => 0,
    'list_items' => 0,

];

$this->moduleData->breadcrumbs = [];
$this->moduleData->breadcrumbs[] = [
    'name' => 'Материалы администрирование',
    'param' => 'menu',
    'value' => 'materials_admin',
    'dir' => 'admin/materials_admin'
];

switch ($this->values->mode){

    //создание записи
    case 'add':

        $this->moduleData->breadcrumbs[] = [
            'name' => 'Добавление материала',
            'param' => 'mode',
            'value' => 'add',
            'param2' => 'material_type_id',
            'value2' => '12'
        ];

        //получаем переменные
        $this->getRequestValues(['name','id_char','material_type_id','anons','content','goodcode','tags','language']);

        if($this->values->material_type_id == ''){
            $this->values->material_type_id = 1;
        }
        else{
            $this->values->material_type_id = (int)$this->values->material_type_id;
        }

        $matType = new iceMatType($this->DB, intval($this->values->material_type_id));
        if(!$matType->getRecord(intval($this->values->material_type_id))){
            //$this->warnings[] = 'Для создания материала выберите тип';
            $this->setFlash('errors',['Для создания материала выберите его тип']);
            $this->redirect('/admin/materials_admin');
        }

        //TODO установка языка
        if($this->values->language == ''){
            $this->values->language = 1;
        }

        $this->values->user_id = $this->authorize->user->id;
        $this->values->status_id = 0;

        //проверяем, что форма отправлена
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $material = new Mat($this->DB);

            if($material->createRecord($material->paramsFromValues($this->values))){
                $this->moduleData->success[] = 'Материал <strong>'.$this->values->name.'</strong> успешно создан.';
                $this->unsetValues();
                $this->setFlash('success',['Материал <strong>'.$this->values->name.'</strong> успешно создан']);
                $this->redirect('/admin/materials_admin/?mode=edit&id='.$material->id);
            }
            else {
                $this->moduleData->errors[] = 'Не удалось сохранить материал';
            }

        }

        $selectedMatType = ['id' => $matType->params['id'],'name' => $matType->params['name']];
        $this->moduleData->selectedMatType = $selectedMatType;


        break;

    //редактирование записи
    case 'edit':

        //получаем переменные
        $this->getRequestValues(['id','name','id_char','material_type_id','anons','content','goodcode','tags','language','action','status_id','material_count','price','important','ordernum']);

        //получаем материал по id
        $this->values->id = (int)$this->values->id;
        if($this->values->id == 0){
            $this->module['name']='404';
            $this->loadModule();
        }
        else {
            $material = new Mat($this->DB, $this->values->id);
            if(!$material->getRecord(intval($this->values->id))){
                $this->module['name']='404';
                $this->loadModule();
            }
        }

        //получаем тип материала
        $matType = new iceMatType($this->DB, $material->params['material_type_id']);
        $matType->getRecord();
        $this->moduleData->mType = $matType;

        $this->moduleData->breadcrumbs[] = [
            'name' => $matType->params['name'],
            'param' => 'mtype',
            'value' => $matType->id
        ];
        $this->moduleData->breadcrumbs[] = [
            'name' => $material->params['name'],
            'param' => 'mode',
            'value' => 'edit',
            'param2' => 'id',
            'value2' => $material->id
        ];

        //изменение материала - обработка формы
        if($_SERVER['REQUEST_METHOD'] === 'POST' && $this->values->action == 'edit') {

            if($material->updateRecord($material->paramsFromValues($this->values))){
                $this->moduleData->success[] = 'Материал <strong>'.$this->values->name.'</strong> успешно изменен.';

                //сохранение экстрополей
                if(isset($matType->extraParams) && is_array($matType->extraParams) && count($matType->extraParams) > 0){

                    foreach ($matType->extraParams as $extraParam){

                        //проверяем на существование переменной
                        if(isset($_REQUEST['extraParam_'.$extraParam['id']])){
                            $value = $_REQUEST['extraParam_'.$extraParam['id']];
                            //обновляем или вставляем значение

                            $value_int = 'NULL';
                            $value_char = 'NULL';
                            $value_mat = 'NULL';
                            $value_text = 'NULL';
                            $value_flag = 'NULL';

                            $paramName = $extraParam['value_type'];

                            switch ($extraParam['value_type']){
                                case 'value_char':
                                case 'value_text':
                                    $$paramName = "'".$_REQUEST['extraParam_'.$extraParam['id']]."'";
                                    break;
                                default:
                                    $$paramName = (int)$_REQUEST['extraParam_'.$extraParam['id']];
                                    break;
                            }


                            $query = 'INSERT INTO material_extra_values (material_id, param_id, value_int, value_char, value_mat, value_text, value_flag) 
                                        VALUES('.$material->id.', '.$extraParam['id'].', '.$value_int.', '.$value_char.', '.$value_mat.', '.$value_text.', '.$value_flag.') 
                                        ON DUPLICATE KEY 
                                        UPDATE value_int='.$value_int.', value_char='.$value_char.', value_mat='.$value_mat.', value_text = '.$value_text.', value_flag = '.$value_flag;

                            $res = $this->DB->query($query);

                        }
                        else {
                            $query = 'DELETE FROM material_extra_values WHERE material_id = '.$material->id.' AND param_id = '.$extraParam['id'];
                            $res = $this->DB->query($query);
                        }
                    }

                    $material->uncacheRecord();
                    $material->getRecord();

                }

            }
            else {
                $this->moduleData->errors[] = 'Не удалось сохранить материал';
            }

        }


        //дополнительные действия
        if($this->values->action != ''){
            switch ($this->values->action){
                //очистка кэшей материала без изменения
                case 'clearcache':
                    if($material->uncacheRecord()){
                        $material->getRecord();
                        $this->moduleData->success[] = 'Кэш материала обновлен';
                    }
                    else {
                        $this->moduleData->errors[] = 'Не получилось очистить кэш материала';
                    }
                    break;

                //загрузка нового файла к материалу
                case 'addfile':

                    $file = new iceFile($this->DB, null, $this->settings);
                    if($file->upload('newFile', 'auto', false, $this->authorize->user->id, $material->params['id'])){
                        $material->uncacheRecord();
                        $stext = 'Файл <strong>'.$file->params['filename'].'</strong> успешно загружен.';
                        $this->moduleData->success[] = $stext;
                        $this->setFlash('success',[$stext]);
                        $this->unsetValues();
                        $this->redirect('/admin/materials_admin/?mode=edit&id='.$material->params['id']);
                    }
                    else{
                        $this->moduleData->errors = $file->errors;
                    }

                    break;
            }
        }

        $this->moduleData->material = $material;

        //список языков
        $languages = new iceLanguageList($this->DB, null, [['col' => 'id', 'sort' => 'ASC']]);
        $this->moduleData->languages = $languages->getRecords(null);

        //список статусов
        $this->moduleData->statuses = [
            ['id' => 0, 'name' => 'Скрытый'],
            ['id' => 1, 'name' => 'Активный'],
            ['id' => 2, 'name' => 'Архивный']
        ];

        break;

    //список материалов
    default:

        //страницы
        $page = (int)$this->values->page;
        if($page < 1){
            $page = 1;
        }
        $perpage = 20;

        //ограничиваем список в зависимости от переданного mtype
        $conditions=null;
        if($this->values->mtype == ''){
            $this->values->mtype = 'all';
        }
        elseif($this->values->mtype != 'all'){
            $conditions[] = [
                'string' => false,
                'type' => '=',
                'col' => 'material_type_id',
                'val' => $this->values->mtype
            ];
        }

        if($this->values->mtype != '' && $this->values->mtype != 'all'){

            $this->moduleData->breadcrumbs[] = [
                'name' => $this->materialTypes['types'][$this->values->mtype]['name'],
                'param' => 'mtype',
                'value' => $this->values->mtype
            ];
        }

        //список материалов
        $materials = new iceMatList($this->DB, $conditions, [['col' => 'id', 'sort' => 'DESC']], $page, $perpage);
        $this->moduleData->page = $page;
        $this->moduleData->perpage = $perpage;
        $this->moduleData->materialsCnt = $materials->getCnt();
        $this->moduleData->materials = $materials->getRecords(null);

        break;
}