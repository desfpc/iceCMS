<?php

$icePathParser = new icePathParser($this->DB, $this->params, $this->settings);

echo '<div class="col-md"><ul id="navigation" class="slimmenu">';

if(isset($this->params) && count($this->params) > 0){

    $menuTypes = $this->params['childs'][0];

    //главные разделы
    foreach ($menuTypes as $mtype){

        if($mtype['sitemenu'] == 1){
            mtTree($this->params, $mtype, $icePathParser);
        }

    }

}

echo '</div>';

function mtTree($alltypes, $mtype, $icePathParser){

    $url = $icePathParser->getMatTypeURL($mtype['id']);

    if($mtype['name'] != 'Главная'){
        echo '<li><a href="'.$url.'">'.$mtype['name'].'</a>';
    }

    if(isset($alltypes['childs'][$mtype['id']])){
        if(count($alltypes['childs'][$mtype['id']]) > 0){
            echo '<ul>';
            foreach ($alltypes['childs'][$mtype['id']] as $item) {
                if($item['sitemenu'] == 1){
                    mtTree($alltypes, $item, $icePathParser);
                }
            }
            echo '</ul>';
        }
    }

    echo '</li>';

}

//krumo($this->params);