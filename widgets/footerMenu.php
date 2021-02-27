<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\iceWidget $this
 */

use ice\Routes\PathParser;

$PathParser = new PathParser($this->DB, $this->params, $this->settings);

$childs = $this->params['types']['childs'];
$parent = $this->params['parent'];

if(isset($this->params) && count($this->params) > 0){

    $menuTypes = $childs[$parent];

    //главные разделы
    $cnt = 0;
    foreach ($menuTypes as $mtype){

        if($mtype['sitemenu'] == 1){

            ++$cnt;

            if($cnt < 6){
                $url = $PathParser->getMatTypeURL($mtype['id']);
                echo '<p><a class="footer_link" href="'.$url.'">'.$mtype['name'].'</a></p>';
            }

        }

    }

}