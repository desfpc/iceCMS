<?php
/**
 * iceCMS v0.1
 * Created by Peshalov Sergey https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 */
$setup=Array();

//общие настройки
$setup['path']='/var/www/sites/iceCMS';

//настройки шаблонизатора (папка с шаблонами)
$setup['template']='bootstrap4';

//настройка релиз/разработка
$setup['dev']=true;

//настройки БД
$setup['db']=Array();
$setup['db']['type']='mysql';
$setup['db']['name']='ice';
$setup['db']['host']='127.0.0.1';
$setup['db']['port']='3306';
$setup['db']['login']='ice';
$setup['db']['pass']='ice';
$setup['db']['encoding']='UTF8';

//настройки системы рассылки
$setup['email']=Array();
$setup['email']['mail']='noreply@ice4service.ru';
$setup['email']['port']='120';
$setup['email']['signature']='Система рассылки ice4service.ru';
$setup['email']['pass']='password';
$setup['email']['smtp']='smtp.server';

//настройки сайта
$setup['site']=Array();
$setup['site']['title']='cms.ice4service.ru';
$setup['site']['primary_domain'] = 'cms.ice4service.ru';
$setup['site']['redirect_to_primary_domain'] = false;
$setup['site']['language_subdomain'] = true;

//настройки кэширования
$setup['cache']=Array();
$setup['cache']['use_redis']=true;
$setup['cache']['redis_host']='0.0.0.0';
$setup['cache']['redis_port']=6379;