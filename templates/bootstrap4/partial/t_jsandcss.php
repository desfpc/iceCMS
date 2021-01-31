<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * iceCMS - PHP framework and CMS based on it
 * https://github.com/desfpc/iceCMS
 * @var ice\iceRender $this
 */
//подключаемые стили
$template_styles=array(
    'https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css',
    'https://fonts.googleapis.com/icon?family=Material+Icons',
    'https://fonts.googleapis.com/css?family=Rubik:300,400,700,900&amp;subset=cyrillic',
    '/css/slimmenu.min.css',
    '/js/lightbox/ekko-lightbox.css',
    '/css/site.css');

$this->styles->addStyles($template_styles);

//подключаемые js скрипты
$template_scripts=array(
    'https://code.jquery.com/jquery-3.3.1.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js',
    'https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js',
    'https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js',
    '/js/jquery.slimmenu.min.js',
    '/js/lightbox/ekko-lightbox.min.js',
    '/js/site.js');

$this->jscripts->addScripts($template_scripts);

//подключение css и js от Visualijoper-а TODO подключать только при включенном дебаге
$this->styles->addStyle('/classes/visualijoper_remote/visualijoper.css');
$this->jscripts->addScript('/classes/visualijoper_remote/jquery.visualijoper.js');