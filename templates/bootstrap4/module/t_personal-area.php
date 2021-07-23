<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 */

use ice\Web\Widget;
use ice\Helpers\Strings;

$template_folder = $this->settings->path . '/templates/' . $this->settings->template . '';

//подключаем стили и скрипты
include_once($template_folder . '/partial/t_jsandcss.php');
//$this->styles->addStyle('');
//$this->jscripts->addScript('');

//js document.load
include_once($template_folder . '/partial/t_jsreadyglobal.php');
$this->jsready .= '';

include_once($template_folder . '/partial/t_header.php');


?>
    <div class="container sitebody">
        <div class="row">
            <div class="col">
                <?php
                //выводим ошибки
                include_once($template_folder . '/partial/t_alert.php');
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="hMenu">
                    <a href="/personal-area" class="hMenu_link hMenu_link_active" aria-current="true">
                        Настройки
                    </a>
                    <a href="/personal-area/orderlist" class="hMenu_link">Список заказов</a>
                </div>
            </div>
            <div class="col-md-9">
                <table class="table table-striped">
                    <tr>
                        <td>E-mail: </td>
                        <td><strong><?= $this->authorize->user->params['login_email'] ?></strong></td>
                    </tr>
                    <tr>
                        <td>Дата регистрации: </td>
                        <td><strong><?= Strings::formatDate($this->authorize->user->params['date_add']) ?></strong></td>
                    </tr>
                </table>
                <hr />
                <?php
                $form = new Widget($this->DB, 'form', $this->settings);
                $form->show($this->moduleData->formArr); ?>
            </div>
        </div>
    </div>
<?php include_once($template_folder . '/partial/t_footer.php');