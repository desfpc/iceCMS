<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 */
$setup=[];

//общие настройки
$setup['path']='/var/www/sites/iceCMS';

//настройки шаблонизатора (папка с шаблонами)
$setup['template']='bootstrap4';

//настройка релиз/разработка
$setup['dev']=1;

//настройки БД
$setup['db']=[];
$setup['db']['type']='mysql';
$setup['db']['name']='ice';
$setup['db']['host']='127.0.0.1';
$setup['db']['port']='3306';
$setup['db']['login']='ice';
$setup['db']['pass']='ice';
$setup['db']['encoding']='UTF8';

//настройки системы рассылки
$setup['email']=[];
$setup['email']['mail']='noreply@ice.cms';
$setup['email']['port']='120';
$setup['email']['signature']='Система рассылки ice.cms';
$setup['email']['pass']='password';
$setup['email']['smtp']='smtp.server';

//настройки сайта
$setup['site']=[];
$setup['site']['title']='ice.cms';
$setup['site']['primary_domain'] = 'ice.cms';
$setup['site']['redirect_to_primary_domain'] = false;
$setup['site']['language_subdomain'] = true;

//настройки кэширования
$setup['cache']=[];
$setup['cache']['use_redis']=true;
$setup['cache']['redis_host']='0.0.0.0';
$setup['cache']['redis_port']=6379;

//режим разработки (если работаем на Win64)
/*if(strpos($_SERVER['SERVER_SOFTWARE'],'Win64') !== false){
    $setup['path']='E:\work\Ampps\www\iceCMS';
    $setup['db']['login']='root';
    $setup['db']['pass']='mysql';
    $setup['dev']=true;
}*/