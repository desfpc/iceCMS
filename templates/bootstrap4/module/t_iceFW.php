<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\iceRender $this
 */

$template_folder=$this->settings->path.'/templates/'.$this->settings->template.'';

//подключаем стили и скрипты
include_once ($template_folder.'/partial/t_jsandcss.php');
//$this->styles->addStyle('');
//$this->jscripts->addScript('');

//js document.load
include_once ($template_folder.'/partial/t_jsreadyglobal.php');
$this->jsready.='

    $(".form-group").has("input#regLogin").hide();

';

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
                <?= $this->moduleData->content ?><p>&nbsp;</p>
                <?php

                if(is_array($this->moduleData->table))
                {
                    echo '<table class="table table-hover table-dark"><tbody>';

                    foreach ($this->moduleData->table as $row)
                    {
                        echo '<tr>
    <td>'.$row['name'].'</td>
    <th>'.$row['value'].'</th>
</tr>';
                    }

                    echo '</tbody></table>';
                }

                ?>
            </div>
        </div>
    </div>
<?php include_once ($template_folder.'/partial/t_footer.php');