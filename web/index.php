<?php
/**
 * Created by Peshalov Sergey https://github.com/desfpc
 * iceCMS - PHP framework and CMS based on it
 * https://github.com/desfpc/iceCMS
 */

$gstarttime = microtime(true);

error_reporting(E_ALL);
ini_set('display_errors', 1);

//подключаем нужные классы
require_once ('../vendor/autoload.php');
//require_once('../classes/redka_remote/redka.php');//класс для работы с redis - перенесено в composer
//require_once('../classes/visualijoper_remote/visualijoper.php');//призываем библиотеку визуализации Visualijoper - перенесено в composer
//require_once('../classes/pechkin_remote/pechkin.php');//класс для отправки email - перенесено в composer
$iceDir = '../vendor/desfpc/ice';//директория с классами CMS
$modelsDir = '../classes';//директория пользовательских моделей и прочих классов
require_once('../bootstrap.php');//подключение классов ice CMS

//подключаем настройки, если их нет - редиректим на setup.php
$settings_path = '../settings/settings.php';
if (!file_exists($settings_path)) {
    //echo 'Сайт не настроен';
    header('Location: /setup.php');
    exit;
}
include_once($settings_path);

use ice\Web\Render;

//сессия
session_start();

//создаем сайт
$site = new Render($setup, true);

$sitetime = microtime(true) - $gstarttime;

$site->parseURL();
$parsetime = microtime(true) - $gstarttime;

$site->loadModule();
$moduletime = microtime(true) - $gstarttime;

$site->loadTemplate();
$templatetime = microtime(true) - $gstarttime;

$site->printSite();
$printtime = microtime(true) - $gstarttime;


if ($site->settings->dev) {
    echo '<div class="normalblock" style="display: block;"><div class="developer_block">';

//выводим объект сайта
    visualijoper\visualijoper::visualijop($site, 'Объект сайта');
    visualijoper\visualijoper::visualijop($_SESSION, 'Сессия');

//phpinfo();

    $extime = microtime(true) - $gstarttime;

    echo '<br>Время создания объекта сайта: ' . round($sitetime, 5);
    echo '<br>Время парсинга URL: ' . round(($parsetime - $sitetime), 5);
    echo '<br>Время отработки модуля: ' . round(($moduletime - $parsetime), 5);
    echo '<br>Время отработки шаблона: ' . round(($templatetime - $moduletime), 5);
    echo '<br>Время печати сайта: ' . round(($printtime - $templatetime), 5);
    echo '<br>Общее время выполнения: ' . round($extime, 5);

    echo '</div></div>';
}

//удаляем все из памяти
$site->destroy();