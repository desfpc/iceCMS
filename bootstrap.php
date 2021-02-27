<?php

if(!isset($iceDir)){
    $iceDir = './classes/ice';
}

//Загрузка файлов CMS в нужном порядке
$iceClasses = [
    'iceAuthorize',
    'iceCacher',
    'DB/DB',
    'iceObject',
    'iceObjectList',
    'Models/File',
    'Models/FileList',
    'iceFlashVars',
    'iceHeaderBuilder',
    'iceImageCache',
    'iceImageCacheList',
    'iceJScriptBuilder',
    'Models/LanguageList',
    'iceLogger',
    'Models/Mat',
    'Models/MatExtraParams',
    'Models/MatExtraParamsList',
    'Models/MatExtraValues',
    'Models/MatExtraValuesList',
    'Models/MatList',
    'Models/MatType',
    'Models/MatTypeList',
    'iceMessage',
    'iceParseModule',
    'icePathParser',
    'DB/QueryBuilder',
    'iceRedirect',
    'iceRequestValues',
    'iceSettings',
    'iceStylesBuilder',
    'Models/Template',
    'Models/TemplateList',
    'iceTranslator',
    'Models/User',
    'Models/UserList',
    'iceWidget',
    'iceRender'
];

foreach ($iceClasses as $iceClass) {
    include_once($iceDir .'/'. $iceClass.'.php');
}

//Загрузка хэлперов
$helpersDir = $iceDir.'/Helpers';
$files = scandir($helpersDir);
if(is_array($files) && count($files) > 0){
    foreach($files as $file){
        if(($file !== '.') && ($file !== '..')){
            include_once($helpersDir .'/'. $file);
        }
    }
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