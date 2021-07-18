<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 */

$template_folder = $this->settings->path . '/templates/' . $this->settings->template . '';

//подключаем стили и скрипты
include_once($template_folder . '/partial/t_jsandcss.php');
//$this->styles->addStyle('');
//$this->jscripts->addScript('');

//js document.load
include_once($template_folder . '/partial/t_jsreadyglobal.php');
$this->jsready .= '';

$this->moduleData->breadcrumbs = [
    [
        'name' => 'Главная',
        'dir' => 'none'
    ],
    [
        'name' => 'Личный кабинет',
        'param' => 'menu',
        'value' => 'personal-area'
    ]
];

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
            <?php \visualijoper\visualijoper::visualijop($this->authorize->user); ?>
            <div class="col-md-3">
                <div class="hMenu">
                    <a href="/personal-area" class="hMenu_link hMenu_link_active" aria-current="true">
                        Настройки
                    </a>
                    <a href="/personal-area/orderlist" class="hMenu_link">Список заказов</a>
                </div>
            </div>
            <div class="col-md-9"></div>
        </div>
    </div>
<?php include_once($template_folder . '/partial/t_footer.php');