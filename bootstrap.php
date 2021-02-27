<?php

if(!isset($iceDir)){
    $iceDir = './classes/ice';
}

//Загрузка файлов CMS в нужном порядке
$iceClasses = [
    'Web/Authorize',
    'iceCacher',
    'DB/DB',
    'iceObject',
    'iceObjectList',
    'Models/File',
    'Models/FileList',
    'iceFlashVars',
    'Web\HeaderBuilder',
    'Models/ImageCache',
    'Models/ImageCacheList',
    'Web/JScriptBuilder',
    'Models/LanguageList',
    'Tools/Logger',
    'Tools/CSRF',
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
    'Routes/PathParser',
    'DB/QueryBuilder',
    'Web/Redirect',
    'Web/RequestValues',
    'iceSettings',
    'Web/StylesBuilder',
    'Models/Template',
    'Models/TemplateList',
    'Models/User',
    'Models/UserList',
    'iceWidget',
    'Web/Render'
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