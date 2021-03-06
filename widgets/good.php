<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Widget $this
 */

use ice\Models\File;
use ice\Models\Mat;
use ice\Models\MatType;
use ice\Web\Widget;

$mtype = new MatType($this->DB, $this->params['material_type_id']);
$mtype->getRecord();
$mtype->getURL();

$this->styles->addStyle('/css/widgets/good.css');

$url = $mtype->url . '/' . $this->params['id_char'];

if (isset($this->params['favicon']) && $this->params['favicon'] != '') {

    $fileObj = new File($this->DB, $this->params['favicon']);
    $fileObj->getRecord();

    $img = '<img src="' . $fileObj->getFileCacheUrl(200, 200) . '" alt="' . $fileObj->params['name'] . '" width="188" height="188" />';

} else {
    $img = '<img src="/img/simracingseat/noimage.png" alt="нет изображения" width="188" height="188" />';
}

//если параметр cart == true, то выводим кнопку "в корзину"
if(isset($this->params['cart']) && $this->params['cart']){

    $printCartBtn = true;

}
else {
    $printCartBtn = false;
}

echo '<a class="good" href="' . $url . '">
    <div class="good__img">' . $img . '</div>
    <p class="good__name">' . $this->params['name'] . '</p>
    <p class="good__anons">' . $this->params['anons'] . '</p>
    <p class="good__price">' . Mat::price($this->params['price']);

if($printCartBtn){

    $btn = new Widget($this->DB, 'cartBtn', $this->settings);
    $btn->show($this->params);

}

echo '</p>
</a>';

