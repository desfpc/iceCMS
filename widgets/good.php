<?php


$mtype = new iceMatType($this->DB, $this->params['material_type_id']);
$mtype->getRecord();
$mtype->getURL();

$this->styles->addStyle('/css/widgets/good.css');

$url = $mtype->url.'/'.$this->params['id_char'];

if(isset($this->params['favicon']) && $this->params['favicon'] != ''){

    $fileObj = new iceFile($this->DB, $this->params['favicon']);
    $fileObj->getRecord();

    $img='<img src="'.$fileObj->getFileCacheUrl(200,200).'" alt="'.$fileObj->params['name'].'" width="188" height="188" />';

}
else {
    $img = '<img src="/img/simracingseat/noimage.png" alt="нет изображения" width="188" height="188" />';
}

echo '<a class="good" href="'.$url.'">
    <div class="good__img">'.$img.'</div>
    <p class="good__name">'.$this->params['name'].'</p>
    <p class="good__anons">'.$this->params['anons'].'</p>
    <p class="good__price">'.iceMat::price($this->params['price']).'</p>
</a>';

