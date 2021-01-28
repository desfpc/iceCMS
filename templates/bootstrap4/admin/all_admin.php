<?php

//вес и тэги
include_once ($template_folder.'/admin/_ordernumTags.php');

//стоимость, колличество, код товара
include_once ($template_folder.'/admin/_priceCountGoodcode.php');

?>
<br />
<?php

//Дата начала и окончания события, важный материал
include_once ($template_folder.'/admin/_datesImportant.php');

?>
<br />
<?php

//Анонс
include_once ($template_folder.'/admin/_anons.php');

//Контент
include_once ($template_folder.'/admin/_content.php');


//Дополнительные поля
include_once($template_folder . '/admin/_extraValues.php');


//TODO родительский материал