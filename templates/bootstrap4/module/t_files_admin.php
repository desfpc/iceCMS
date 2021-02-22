<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\iceRender $this
 */

use ice\iceFile;

$template_folder=$this->settings->path.'/templates/'.$this->settings->template.'';

//подключаем стили и скрипты
include_once ($template_folder.'/partial/t_jsandcss.php');
//$this->styles->addStyle('');
//$this->jscripts->addScript('');

//js document.load
include_once ($template_folder.'/partial/t_jsreadyglobal.php');
$this->jsready.='';

include_once ($template_folder.'/partial/t_header.php');

?>
    <div class="container sitebody">
        <div class="row">
            <div class="col-sm-2">
                <?php
                //выводим дерево категорий (полное)
                include_once ($template_folder.'/partial/material_types_admin/menu_list.php');
                ?>
            </div>
            <div class="col-sm-10">
                <?php
                //выводим ошибки
                include_once ($template_folder.'/partial/t_alert.php');
                ?>
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 30px;">ID</th>
                            <th style="width: 48px;"></th>
                            <th>Имя</th>
                            <th>Имя файла</th>
                            <th style="width: 60px;">Дата создания</th>
                            <th style="width: 60px;">Размер</th>
                            <th style="width: 60px;">Материалы</th>
                            <th style="width: 60px;">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php

                    if(isset($this->moduleData->files) && is_array($this->moduleData->files) && count($this->moduleData->files) > 0){
                        foreach ($this->moduleData->files as $file) {
                            echo '<tr>
                                <td>'.$file['id'].'</td>
                                <td>'.iceFile::formatIcon($this->DB, $file, true).'</td>
                                <td>'.$file['name'].'</td>
                                <td>'.$file['filename'].'</td>
                                <td>'.iceFile::formatDate($file['date_add']).'</td>
                                <td>'.iceFile::formateSize($file['size']).'</td>
                                <td></td>
                                <td></td>
                            </tr>';
                        }
                    }

                    ?>
                    </tbody>
                </table>
                <hr />
                <h2>Загрузить файл:</h2>
                <form method="post" enctype="multipart/form-data" action="/admin/files">
                    <input type="hidden" name="menu" value="files_admin">
                    <input type="hidden" name="mtype" value="<?=$this->values->mtype?>">
                    <input type="hidden" name="page" value="1">
                    <input type="hidden" name="MAX_FILE_SIZE" value="20971520">
                    <input type="file" id="newFile" name="newFile">
                    <input type="hidden" name="action" value="addfile">
                    <button type="submit" class="btn btn-success"><i class="material-icons md-24 md-light">attach_file</i> Загрузить</button>
                </form>
            </div>
        </div>

    </div>
<?php include_once ($template_folder.'/partial/t_footer.php');