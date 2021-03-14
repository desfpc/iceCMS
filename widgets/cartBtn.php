<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Widget $this
 */

if($this->params['material_count'] == 0){
    $class=' disabled';
    $text = 'Нет в наличие';
}
else {
    $text = 'В корзину';
    $class = '';
}

echo '<button type="button" data="'.$this->params['id'].'" class="btn btn-info btn-cart'.$class.'"><i class="material-icons md-24 md-light">shopping_basket</i>&nbsp;'.$text.'</button>';