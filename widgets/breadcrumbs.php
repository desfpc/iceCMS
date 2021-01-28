<?php

//visualijop($this->params);

$lastActive = false;
$text = '';

if(is_null($this->params['material'])){
    $lastActive = true;
}

//цикл по типам материалов

$types = array_merge([0 => ['name' => 'Главная', 'id_char' => '']], $this->params['types']);

$ctypes = count($types);



if($ctypes > 0){
    $i=0;
    $url='';
    foreach ($types as $type){
        ++$i;
        if($type['id_char'] != ''){
            $url.='/'.$type['id_char'];
        }
        if($lastActive && $i == $ctypes){
            $text.='<li class="breadcrumb-item active" aria-current="page">'.$type['name'].'</li>';
        }
        else {
            $text.='<li class="breadcrumb-item"><a href="'.$url.'/'.'">'.$type['name'].'</a></li>';
        }
    }
}

if(!is_null($this->params['material'])){
    $material = $this->params['material'];
    $text.='<li class="breadcrumb-item active" aria-current="page">'.$material->params['name'].'</li>';
}

//выводим
if($text != ''){
    echo '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
    echo $text;
    echo '</ol></nav>';
}