<?php
/**
 * Created by Peshalov Sergey https://github.com/desfpc
 * iceCMS - PHP framework and CMS based on it
 * https://github.com/desfpc/iceCMS
 */

//проверяем файл найтроек settings.php - если он есть, то переходим в index.php
$defaultDir = __DIR__;

if(file_exists($defaultDir.'/settings/settings.php')){
    echo 'Настройка выполнена';
    header('Location: /');
    exit;
}

//обработка формы настроек
if(isset($_POST['doSetup'])){

    //TODO проверка пути к серверу, проверка наличия классов

    //TODO проверка и соединение с БД

    //TODO Разворачивание SQL

    //TODO генерация и сохранение файла settings.php

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
	<title>iceCMS Setup</title>
</head>
<body>
<div class="container sitebody">
    <div class="row">
        <div class="col">
            <h1>iceCMS Setup</h1>
        </div>
    </div>
    <form id="matEditForm" action="/setup.php" method="post"><input type="hidden" name="doSetup value="1">
    <?php

    //подключение дефолтного файла настроек
    require_once($defaultDir.'/settings/default.php');

    //массив с наименованиями настроек
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
    foreach ($setup as $key => $arr) {
        echo '<br>&nbsp;';
        //если список значений - выводим заголовок и перебираем значения
        if(is_array($arr)){

        }
        else{
            echo '<div class="form-group row">';

            echo '</div>';
        }
    }
    ?>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="/js/ice.js"></script>
</body>
</html>

