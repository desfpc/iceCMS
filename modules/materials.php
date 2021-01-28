<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var \iceRender $this
 */

$this->moduleData=new stdClass();

$this->moduleData->title=$this->settings->site->title;
$this->moduleData->H1='';
$this->moduleData->errors=array();
$this->moduleData->success=array();

//парсим URL, определяем текущий тип материала и материал
$this->parser->parseURL($this->path_info['call_parts']);

//формируем данные для отображения типа материала или материала
$mtype = end($this->parser->mtypes);
$mtype = new iceMatType($this->DB, $mtype['id']);
$mtype->getRecord();
$this->moduleData->mtype = $mtype;
$this->moduleData->material = $this->parser->material;

//формирование данных для типа материала
if(is_null($this->moduleData->material)){
    $this->moduleData->H1 = $this->moduleData->mtype->params['name'];

    //подготовка данных (если нужно)
    if($this->moduleData->mtype->params['prepare_list'] == '1'){

        //выборка
        $conditions[] = [
            'string' => false,
            'type' => '=',
            'col' => 'material_type_id',
            'val' => $this->moduleData->mtype->params['id']
        ];
        $conditions[] = [
            'string' => false,
            'type' => '=',
            'col' => 'status_id',
            'val' => 1
        ];

        //сортировки
        $sort[] = ['col' => 'ordernum', 'sort' => 'ASC'];
        $sort[] = ['col' => 'id', 'sort' => 'DESC'];

        //пейджинация
        if(!isset($this->values->page)){
            $this->values->page = 1;
        }

        $page = (int)$this->values->page;
        if($page < 1){
            $page = 1;
        }

        $perpage = (int)$this->moduleData->mtype->params['list_items'];
        if($perpage == 0){
            $perpage = 10;
        }

        $mlist = new iceMatList($this->DB, $conditions, $sort, $page, $perpage);
        $this->moduleData->mlist = $mlist->getRecords();

    }
    else {
        $this->moduleData->mlist = null;
    }

}
//формирование данных для материала
else {
    $this->moduleData->H1 = $this->moduleData->material->params['name'];
}