<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var \iceRender $this
 */

$template_folder=$this->settings->path.'/templates/'.$this->settings->template.'';

//подключаем стили и скрипты
include_once ($template_folder.'/partial/t_jsandcss.php');
//$this->styles->addStyle('');
//$this->jscripts->addScript('');

//js document.load
include_once ($template_folder.'/partial/t_jsreadyglobal.php');
$this->jsready.='';

include_once ($template_folder.'/partial/t_header.php');

?><div class="container sitebody"><?php


switch ($this->values->mode){

    //создание нового материала - форма
    case 'add':
        include_once ($template_folder.'/partial/materials_admin/add.php');
        break;

    //редактирование материала
    case 'edit':
        include_once ($template_folder.'/partial/materials_admin/edit.php');
        break;

    //списвок материалов
    default:
        include_once ($template_folder.'/partial/materials_admin/list.php');
        break;

}

?></div><?php


include_once ($template_folder.'/partial/t_footer.php');