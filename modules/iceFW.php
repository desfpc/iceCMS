<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var \iceRender $this
 */

//секурность
if(!$this->moduleAccess())
{
    return;
};

$this->moduleData=new stdClass();

$this->moduleData->title=$this->settings->site->title;
$this->moduleData->H1='ice Framework';
$this->moduleData->errors=array();
$this->moduleData->success=array();
$this->moduleData->content='<b>ice Framework</b> - универсальный PHP 7 фреймворк для быстрой разработки сайтов и интернет приложений любой сложности. 
<br>Основная цель - предоставить удобный и легкий в освоении инструмент, который не гонится за новомодными веяниями в web разработке, а сконцентрирован на максимальном быстродействии.';
$this->moduleData->table=array();

$this->moduleData->table[] =
    array('name' => 'Наименование',
        'value' => 'ice framework');

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
