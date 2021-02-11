<?php
/**
 * Created by Peshalov Sergey https://github.com/desfpc
 * iceCMS - PHP framework and CMS based on it
 * https://github.com/desfpc/iceCMS
 */

ini_set('max_execution_time', '360');

//проверяем файл найтроек settings.php - если он есть, то переходим в index.php
$defaultDir = __DIR__;

if(file_exists($defaultDir.'/settings/settings.php')){
    //echo 'Настройка выполнена';
    header('Location: /');
    exit;
}

//ошибки
$errors = [];
$errorValues=[];

//подключение дефолтного файла настроек
require_once($defaultDir.'/settings/default.php');

//заменяем путь по умолчанию на $defaultDir
$setup['path'] = $defaultDir;

//визуализатор переменных для отладки
require_once($defaultDir.'/classes/visualijoper_remote/visualijoper.php');

//обработка формы настроек
if(isset($_POST['doSetup'])){

    //проверка пути к серверу, проверка наличия классов
    if(isset($_POST['path']) && !is_dir($_POST['path'])){
        $errors[]='Директории "'.$_POST['path'].'" не существует!';
        $errorValues['path'] = true;
    }
    elseif(!is_dir($_POST['path'].'/classes/ice')){
        $errors[]='Не обнаружено директории "'.$_POST['path'].'/classes/ice'.'"';
        $errorValues['path'] = true;
    }

    function makeBoolFromStr($str){
        if($str == 'true'){
            $str = true;
        }
        elseif($str == 'false'){
            $str = false;
        }
        return $str;
    }

    //формируем массив $setup
    function makeSetup($arr,$parent = null, $default){

        $setup = [];

        foreach ($arr as $key => $value){
            if(is_array($value)){
                $keyArr = makeSetup($value, $key, $default);;
                $setup = array_merge($setup, $keyArr);
            }
            else {
                if(is_null($parent)){
                    if(isset($_POST[$key])){
                        $_POST[$key] = makeBoolFromStr($_POST[$key]);
                        $setup[$key] = $_POST[$key];
                    }
                    else {
                        $setup[$key] = $default[$key];
                    }
                }else{
                    if(isset($_POST[$parent.'_'.$key])){
                        $_POST[$parent.'_'.$key] = makeBoolFromStr($_POST[$parent.'_'.$key]);
                        $setup[$parent][$key] = $_POST[$parent.'_'.$key];
                    }
                    else {
                        $setup[$parent][$key] = $default[$parent][$key];
                    }
                }
            }
        }

        return $setup;
    }

    $newSetup = makeSetup($setup, null, $setup);
    //visualijop($newSetup, 'Новый массив с настройками');

    //подключаем нужные классы
    require_once($defaultDir.'/classes/ice/iceSettings.php');
    require_once($defaultDir.'/classes/ice/iceDB.php');

    $iceSetup = new ice\iceSettings($newSetup);
    //visualijop($iceSetup,'Класс настроек');

    //проверка и соединение с БД
    $iceDB = new ice\iceDB($iceSetup);
    //visualijop($iceDB,'Класс БД');

    if($iceDB->connected == 0){
        $errors[]='Нет возможности установить соединение с БД!';
        $errorValues['db']['host']=true;
        $errorValues['db']['port']=true;
        $errorValues['db']['login']=true;
        $errorValues['db']['pass']=true;
    }
    elseif($iceDB->status->flag == 0){
        //соединение с сервером есть, но невозможно выбрать БД - пробуем БД создать
        $dbName = $iceDB->mysqli->real_escape_string($newSetup['db']['name']);
        if($iceDB->query('CREATE DATABASE '.$dbName, true, false, true)){
            $iceDB = new ice\iceDB($iceSetup);
            if($iceDB->connected == 1 && $iceDB->status->flag == 0){
                $errors[]='Нет БД "'.$dbName.'" и нет возможности ее создать!';
                $errorValues['db']['name']=true;
            }
        }
    }

    //проверка соединения с Redis
    if($newSetup['cache']['use_redis']){
        require_once($defaultDir.'/classes/redka_remote/redka.php');
        require_once($defaultDir.'/classes/ice/iceCacher.php');

        $iceCacher = new ice\iceCacher($iceSetup->cache->redis_host, $iceSetup->cache->redis_port);
        //visualijop($iceCacher, 'Кэширование');

        if($iceCacher->status == 0){
            $errors[] = 'Нет возможности установить соединение с Rredis';
            $errorValues['cache']['redis_host']=true;
            $errorValues['cache']['redis_port']=true;
        }

    }

    //Разворачивание SQL
    if(count($errors) == 0){
        if($sqlFile = file_get_contents($defaultDir.'/sql/ice.sql')){
            if(!$iceDB->multiQuery($sqlFile)){
                $errors[] = 'Не получилось развернуть БД';
            }
        }
        else{
            $errors[] = 'Не могу прочитать SQL файл для разворачивания БД';
        }
        //visualijop($sqlFile);
    }

    //генерация и сохранение файла settings.php
    if(count($errors) == 0){
        if(!$iceSetup->save()){
            $errors[]='Не удается сохранить файл настроек';
        }
    }

    //$errors[]='Ошибка, что бы не было редиректа, пока весь скрипт не дописан';

    //если есть ошибки - рисуем переданные с $_POST значения для правки
    if(count($errors) > 0){
        $setup = $newSetup;
    }
    //Если нет ошибок - перегружаем на index.php
    else{
        header('Location: /');
        exit;
    }

}

//рисуем форму настроек сайта
header('Expires: mon, 26 jul 2000 05:00:00 GMT'); //Дата в прошлом
header('Cache-Control: no-cache, must-revalidate'); // http/1.1
header('Pragma: no-cache'); // http/1.1
header('last-modified: '.gmdate('d, d m y h:i:s').' GMT');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block;');
header('X-Content-Type-Options: nosniff');
?><!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="ice">
	<meta name="author" content="Sergey Peshalov">
	<meta name="keyword" content="">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link href="/css/site.css" rel="stylesheet">
    <link href="/classes/visualijoper_remote/visualijoper.css" rel="stylesheet">
	<title>iceCMS Setup</title>
</head>
<body>
<div class="container sitebody">
    <div class="row">
        <div class="col">
            <h1>iceCMS Setup</h1>
        </div>
    </div>
    <?php

    //вывод ошибок
    if(count($errors) > 0){
        ?>
        <div class="row">
            <div class="col">
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">Ошибки выполнения настройки iceCMS:</h4>
                    <?php

                    $out = '';

                    foreach ($errors as $error){
                        $out.='<hr><p>'.$error.'</p>';
                    }

                    echo $out;

                    ?>
                </div>
            </div>
        </div>
        <?php
    }

    ?>
    <form id="matEditForm" action="/setup.php" method="post"><input type="hidden" name="doSetup" value="1">
    <?php

    //массив с наименованиями настроек Ru //TODO En
    $names = [
        'patch' => 'Путь к корню проекта',
        'template' => 'Папка с шаблонами',
        'dev' => 'Режим разработки',
        'db' => 'База данных',
        'type' => 'Тип',
        'name' => 'Наименование',
        'host' => 'Хост',
        'port' => 'Порт',
        'login' => 'Логин',
        'pass' => 'Пароль',
        'encoding' => 'Кодировка',
        'email' => 'Email',
        'mail' => 'Почта',
        'signature' => 'Подпись для рассылки',
        'smtp' => 'SMTP сервер',
        'site' => 'Настройки сайта',
        'title' => 'Заголовок',
        'primary_domain' => 'Основной домен',
        'redirect_to_primary_domain' => 'Редирект на основной домен',
        'language_subdomain' => 'Локализации на поддоменах',
        'cache' => 'Настройки кэширования',
        'use_redis' => 'Использовать Redis',
        'redis_host' => 'Хост Redis-а',
        'redis_port' => 'Порт Redis-а'
    ];

    function echoParam($parentKey, $array, $names, $errorValues){

        if(is_array($array) && count($array) > 0){

            foreach ($array as $key => $value){

                if(key_exists($key, $names)){
                    $name = $names[$key];
                }
                else {
                    $name = $key;
                }

                if(is_array($value)){
                    echo '<div class="form-group row">';
                    echo '
                <label for="name" class="col-sm-12 col-form-label text-left"><strong>'.$name.'</strong></label>';
                    echo '</div>';

                    echoParam($key, $value, $names, $errorValues);

                }
                else{

                    if($parentKey){
                        $paramName = $parentKey.'_'.$key;

                        if(isset($errorValues[$parentKey][$key])){
                            $allertClass='is-invalid';
                        }
                        else {
                            $allertClass='';
                        }

                    }
                    else {
                        $paramName = $key;

                        if(isset($errorValues[$key])){
                            $allertClass='is-invalid';
                        }
                        else {
                            $allertClass='';
                        }

                    }

                    if($value === true){
                        $value = 'true';
                    }
                    if($value === false){
                        $value = 'false';
                    }

                    echo '<div class="form-group row">';
                    echo '
    <label for="name" class="col-sm-2 col-form-label text-right">'.$name.'</label>
    <div class="col-sm-10">
        <input type="text" class="form-control '.$allertClass.'" id="'.$paramName.'" name="'.$paramName.'" aria-describedby="'.$paramName.'Help" placeholder="'.$name.'" required value="'.$value.'">
    </div>';
                    echo '</div>';
                }
            }

        }

    }

    echoParam(false, $setup, $names, $errorValues);
    ?>
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-10">
                <input class="btn btn-primary" type="submit" value="Отправить">
            </div>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="/js/ice.js"></script>
<script src="/classes/visualijoper_remote/jquery.visualijoper.js"></script>
</body>
</html>