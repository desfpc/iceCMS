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
$this->jsready.='';

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
        <div class="row">
            <div class="col">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th rowspan="2">Ширина</th>
                            <th rowspan="2">Высота</th>
                            <th rowspan="2">Водяной знак</th>
                            <th colspan="2">Смещение водяного знака</th>
                            <th rowspan="2">Действия</th>
                        </tr>
                        <tr>
                            <th>X</th>
                            <th>Y</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php

                    if(is_array($this->moduleData->iCaches) && count($this->moduleData->iCaches) > 0) {
                        foreach ($this->moduleData->iCaches as $row){

                            if($row['watermark_name'] == ''){
                                $row['watermark_name'] = 'нет';
                            }

                            echo '
                        <tr>
                            <td>'.$row['width'].'</td>
                            <td>'.$row['height'].'</td>
                            <td>'.$row['watermark_name'].' (id'.$row['watermark'].')</td>
                            <td>'.$row['w_x'].'</td>
                            <td>'.$row['w_y'].'</td>
                            <td></td>
                        </tr>';
                        }
                    }

                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col">
                <h2>Создать новый:</h2>
                <form method="post" action=""><table class="table">
                        <input type="hidden" name="mode" value="add">
                    <thead class="thead-dark">
                    <tr>
                        <th rowspan="2">Ширина</th>
                        <th rowspan="2">Высота</th>
                        <th rowspan="2">Водяной знак</th>
                        <th colspan="2">Смещение водяного знака</th>
                    </tr>
                    <tr>
                        <th>X</th>
                        <th>Y</th>
                    </tr>
                    </thead>
                    <tr>
                        <td>
                            <input type="text" class="form-control" id="width" name="width" aria-describedby="widthHelp" placeholder="Ширина кэша (px)" required value="">
                        </td>
                        <td>
                            <input type="text" class="form-control" id="height" name="height" aria-describedby="heightHelp" placeholder="Высота кэша (px)" required value="">
                        </td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    </table>
                    <button type="submit" class="btn btn-success"><i class="material-icons md-24 md-light">add_box</i> Создать</button></form>
            </div>
        </div>
    </div>
<?php include_once ($template_folder.'/partial/t_footer.php');