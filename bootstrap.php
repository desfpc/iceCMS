<?php

if(!isset($iceDir)){
    $iceDir = './classes/ice';
}


//Загрузка файлов CMS в нужном порядке
$iceClasses = [
    'Authorize',
    'Cacher',
    'DB',
    'Object',
    'ObjectList',
    'File',
    'FileList',
    'FlashVars',
    'HeaderBuilder',
    'ImageCache',
    'ImageCacheList',
    'JScriptBuilder',
    'LanguageList',
    'Logger',
    'Mat',
    'MatExtraParams',
    'MatExtraParamsList',
    'MatExtraValues',
    'MatExtraValuesList',
    'MatList',
    'MatType',
    'MatTypeList',
    'ParseModule',
    'PathParser',
    'QueryBuilder',
    'Redirect',
    'RequestValues',
    'Settings',
    'StylesBuilder',
    'Template',
    'TemplateList',
    'TextFunctions',
    'Translator',
    'User',
    'UserList',
    'Widget',
    'Render'
];

foreach ($iceClasses as $iceClass) {
    include_once($iceDir .'/ice'. $iceClass.'.php');
}

//Загрузка пользовательских классов
if(!isset($modelsDir)){
    $modelsDir = './models';
}
$files = scandir($modelsDir);
if(is_array($files) && count($files) > 0){
    foreach($files as $file){
        if(($file !== '.') && ($file !== '..')){
            include_once($modelsDir .'/'. $file);
        }
    }
}