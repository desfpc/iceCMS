<?php

/*[
    'name' => 'Материалы',
    'param' => 'menu',
    'value' => 'materials'
]*/

$lastActive = false;
$text = '';
$url = '';

if(is_array($this->params) && count($this->params) > 0){
    $i=0;
    $cnt = count($this->params);
    foreach ($this->params as $param){
        ++$i;

        if($i == $cnt){
            $text.='<li class="breadcrumb-item active" aria-current="page">'.$param['name'].'</li>';
        }
        else {
            if($url == ''){
                $url .= '?';
            }
            else {
                $url .= '&';
            }
            $url .= $param['param'].'='.$param['value'];

            if(isset($param['param2'])){
                $url .= '&'.$param['param2'].'='.$param['value2'];
            }

            $text.='<li class="breadcrumb-item"><a href="/'.$url.'">'.$param['name'].'</a></li>';
        }
    }
}

//выводим
if($text != ''){
    echo '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
    echo $text;
    echo '</ol></nav>';
}