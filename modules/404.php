<?php
/**
 * Created by Peshalov Sergey https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var \iceRender $this
 */

$this->headers = new iceHeaderBuilder();
$this->headers->standartHeaders();
$this->headers->addHeader('HTTP/1.0 404 Not Found');
$this->headers->addHeader('Status: 404 Not Found');

$this->moduleData=new stdClass();

$this->moduleData->title=$this->settings->site->title.' - ошибка 404';
$this->moduleData->H1='404';
$this->moduleData->H3='ПОТЕРЯЛИСЬ В <span>КОСМОСЕ</span> '.$this->settings->site->title.'? Хмм, похоже, что такой страницы не существует.';
$this->moduleData->buttonHome='На главную';
$this->moduleData->buttonSiteMap='Карта сайта';