<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 */

$templateFolder = $this->settings->path . '/templates/' . $this->settings->template . '/partial/print_forms/';

//подключаем стили и скрипты
//include_once($template_folder . '/partial/t_jsandcss.php');
//$this->styles->addStyle('');
//$this->jscripts->addScript('');
//$this->jsready .= '';
?><!doctype html>
<html lang="en">
<head>
    <base href="/">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <title><?= $this->moduleData->title ?></title>
    <style>
        @media screen, print {
            html {
                padding: 0;
                margin: 0;
            }
            body {
                color: #000;
                background-color: #fff;
                margin: .2in .55in; /*отступы от края страницы, для красоты*/
                padding: 0;
                font: 10pt "Helvetica Neue", Arial, Verdana;
                box-sizing: border-box;
            }
            .bordered {
                border-collapse: collapse;
            }
            .bordered th, .bordered td {
                border: 1px solid #000000;
                padding: 5px;
                font-size: 8pt;
            }
        }
    </style>
</head>
<body>
<?php
//получение переменных
$this->getRequestValues(['type', 'id']);

//подключаем шаблон формы переданного типа
if (!empty($this->values->type)) {
    $templateName = $templateFolder.$this->values->type.'.php';
    if (file_exists($templateName)) {
        include_once($templateName);
    }
}
?>
</body>
</html><?php die();