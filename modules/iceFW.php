<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 *
 * About project module
 *
 */

//секурность
if (!$this->moduleAccess()) {
    return;
}

$this->moduleData = new stdClass();

$this->moduleData->title = $this->settings->site->title;
$this->moduleData->H1 = 'ice CMS';
$this->moduleData->errors = array();
$this->moduleData->success = array();
$this->moduleData->content = '<b>ice Framework</b> - универсальный PHP 7 фреймворк и CMS для быстрой разработки сайтов и интернет приложений любой сложности.
<br>Проект на gitHub: <a target="_blank" href="https://github.com/desfpc/iceCMS">https://github.com/desfpc/iceCMS</a>';
$this->moduleData->table = array();

$this->moduleData->table[] =
    array('name' => 'Наименование',
        'value' => 'iceCMS');

$this->moduleData->table[] =
    array('name' => 'Версия',
        'value' => $this->version);

$this->moduleData->table[] =
    array('name' => 'Разработчик',
        'value' => '<a href="https://github.com/desfpc" target="_blank">Sergey Peshalov</a>');

$this->moduleData->table[] =
    array('name' => 'Язык разработки',
        'value' => 'php 7');

$this->moduleData->table[] =
    array('name' => 'База данных',
        'value' => 'MySql 8');
