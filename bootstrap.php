<?php

if (!isset($iceDir)) {
    $iceDir = './vendor/desfpc/ice';
}

//Загрузка файлов CMS в нужном порядке
$iceClasses = [
    'Web/Authorize',
    'DB/Cacher',
    'DB/DB',
    'Models/Obj',
    'Models/ObjectList',
    'Models/File',
    'Models/FileList',
    'Web/FlashVars',
    'Web/HeaderBuilder',
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
    'Messages/Message',
    'Routes/ParseModule',
    'Routes/PathParser',
    'DB/QueryBuilder',
    'Web/Redirect',
    'Web/RequestValues',
    'Settings/Settings',
    'Web/StylesBuilder',
    'Models/Template',
    'Models/TemplateList',
    'Models/User',
    'Models/UserList',
    'Models/StoreRequest',
    'Models/StoreRequestGood',
    'Models/StoreRequestList',
    'Web/Widget',
    'Web/Render',
    'Web/Form',
    'Models/Enum',
    'Models/RequestStatuses',
    'Models/RequestPayments'
];

foreach ($iceClasses as $iceClass) {
    include_once($iceDir . '/' . $iceClass . '.php');
}

//Загрузка хэлперов
$helpersDir = $iceDir . '/Helpers';
$files = scandir($helpersDir);
if (is_array($files) && count($files) > 0) {
    foreach ($files as $file) {
        if (($file !== '.') && ($file !== '..')) {
            include_once($helpersDir . '/' . $file);
        }
    }
}

//Загрузка пользовательских классов
if (!isset($modelsDir)) {
    $modelsDir = './classes';
}

if (!file_exists($modelsDir) && !mkdir($modelsDir, 0750) && !is_dir($modelsDir)) {
    throw new \RuntimeException(sprintf('Directory "%s" was not created', $modelsDir));
}

/**
 * подключает пользовательские классы
 *
 * @param string $dir
 * @return void
 */
function includeUserClasses(string $dir) {
    if (is_dir($dir)) {
        $files = scandir($dir);
        if (is_array($files) && count($files) > 0) {
            foreach ($files as $file) {
                if (($file !== '.') && ($file !== '..')) {
                    if(is_dir($dir . '/' . $file)) {
                        includeUserClasses($dir . '/' . $file);
                    }
                    else {
                        include_once($dir . '/' . $file);
                    }
                }
            }
        }
    }
}
includeUserClasses($modelsDir);