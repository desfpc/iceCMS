<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 */

$template_folder=$this->settings->path.'/templates/'.$this->settings->template.'';

//подключаем стили и скрипты
include_once ($template_folder.'/partial/t_jsandcss.php');
//$this->styles->addStyle('');
//$this->jscripts->addScript('');

//js document.load
include_once ($template_folder.'/partial/t_jsreadyglobal.php');
//$this->jsready.='';

include_once ($template_folder.'/partial/t_header.php');

?>
    <div class="container sitebody">
        <div class="row">
            <div class="col">
                <?php
                //выводим ошибки
                include_once ($template_folder.'/partial/t_alert.php');
                ?>
            </div>
        </div>
        <?php

        switch($this->values->mode){

            //форма добавления
            case 'add':
                include_once ($template_folder.'/partial/material_types_admin/add.php');
                break;

            //форма изменения
            case 'edit':
                include_once ($template_folder.'/partial/material_types_admin/edit.php');
                break;

            //удаление
            case 'delete':
                include_once ($template_folder.'/partial/material_types_admin/delete.php');
                break;

            //списвок структуры материалов
            default:
                include_once ($template_folder.'/partial/material_types_admin/list.php');
                break;

        }

        ?>
    </div>
<?php include_once ($template_folder.'/partial/t_footer.php');