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
require_once ('./classes/redka_remote/redka.php');//класс для работы с redis
require_once('./classes/visualijoper_remote/visualijoper.php');//призываем библиотеку визуализации Visualijoper
$iceDir = './classes/ice';//директория с классами CMS
$modelsDir = './models';//директория пользовательских моделей
require_once('bootstrap.php');//подключение классов ice CMS

//подключаем настройки
$settings_path='./settings/settings.php';
include_once ($settings_path);

use ice\iceRender;

//сессия
session_start();

//создаем сайт
$site = new iceRender($setup, true);

$sitetime=microtime(true)-$gstarttime;

$site->parseURL();
$parsetime=microtime(true)-$gstarttime;

$site->loadModule();
$moduletime=microtime(true)-$gstarttime;

$site->loadTemplate();
$templatetime=microtime(true)-$gstarttime;

$site->printSite();
$printtime=microtime(true)-$gstarttime;


if($site->settings->dev)
{
    echo '<div class="normalblock" style="display: block;"><div class="developer_block">';

//выводим объект сайта
    visualijop($site, 'Объект сайта');
    visualijop($_SESSION, 'Сессия');

//phpinfo();

    $extime=microtime(true)-$gstarttime;

    echo '<br>Время создания объекта сайта: '.round($sitetime,5);
    echo '<br>Время парсинга URL: '.round(($parsetime-$sitetime),5);
    echo '<br>Время отработки модуля: '.round(($moduletime-$parsetime),5);
    echo '<br>Время отработки шаблона: '.round(($templatetime-$moduletime),5);
    echo '<br>Время печати сайта: '.round(($printtime-$templatetime),5);
    echo '<br>Общее время выполнения: '.round($extime,5);

    echo '</div></div>';
}

//удаляем все из памяти
$site->destroy();