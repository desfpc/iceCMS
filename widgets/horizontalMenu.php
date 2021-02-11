<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\iceWidget $this
 */

use ice\icePathParser;

$icePathParser = new icePathParser($this->DB, $this->params, $this->settings);

$childs = $this->params['types']['childs'];
$parent = $this->params['parent'];
$active = $this->params['active'];

$this->styles->addStyle('/css/widgets/horizontalMenu.css');

echo '<div class="hMenu">';

//TODO при необходимости сделать вложенные разделы
if(isset($this->params) && count($this->params) > 0){

    if(isset($childs[$parent]) && count($childs[$parent]) > 0){

        $menuTypes = $childs[$parent];

        foreach ($menuTypes as $mtype){

            if($mtype['sitemenu'] == 1){

                if($active == $mtype['id_char']){
                    $activeClass = ' hMenu_link_active';
                }
                else {
                    $activeClass = '';
                }

                $url = $icePathParser->getMatTypeURL($mtype['id']);
                echo '<a class="hMenu_link'.$activeClass.'" href="'.$url.'">'.$mtype['name'].'</a>';

            }

        }
    }
}

echo '</div>';