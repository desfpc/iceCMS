<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\iceWidget $this
 */

use ice\Routes\PathParser;

$PathParser = new PathParser($this->DB, $this->params, $this->settings);

echo '<div class="col-md"><ul id="navigation" class="slimmenu">';

if(isset($this->params) && count($this->params) > 0){

    $menuTypes = $this->params['childs'][0];

    //главные разделы
    foreach ($menuTypes as $mtype){

        if($mtype['sitemenu'] == 1){
            mtTree($this->params, $mtype, $PathParser);
        }

    }

}

echo '</div>';

function mtTree($alltypes, $mtype, $PathParser){

    $url = $PathParser->getMatTypeURL($mtype['id']);

    if($mtype['name'] != 'Главная'){
        echo '<li><a href="'.$url.'">'.$mtype['name'].'</a>';
    }

    if(isset($alltypes['childs'][$mtype['id']])){
        if(count($alltypes['childs'][$mtype['id']]) > 0){
            echo '<ul>';
            foreach ($alltypes['childs'][$mtype['id']] as $item) {
                if($item['sitemenu'] == 1){
                    mtTree($alltypes, $item, $PathParser);
                }
            }
            echo '</ul>';
        }
    }

    echo '</li>';

}

//krumo($this->params);