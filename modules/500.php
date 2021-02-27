<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 *
 * 500 Error module
 *
 */

use ice\Web\HeaderBuilder;

$this->headers = new HeaderBuilder();
$this->headers->standartHeaders();
$this->headers->addHeader('HTTP/1.0 500 Internal Server Error');
$this->headers->addHeader('Status: 500 Internal Server Error');

$this->moduleData=new stdClass();

$this->moduleData->title=$this->settings->site->title.' - ошибка 404';
$this->moduleData->H1='500';
$this->moduleData->H3='ЧТО ТО В <span>КОСМОСЕ</span> '.$this->settings->site->title.' НЕ ТАК? Хмм, похоже, что произошел какой-то сбой.';
$this->moduleData->buttonHome='На главную';
$this->moduleData->buttonSiteMap='Карта сайта';
$this->moduleData->errors=$this->errors;