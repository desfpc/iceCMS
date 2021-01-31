<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\iceRender $this
 */

use ice\iceWidget;
use ice\iceMat;

$material = $this->moduleData->material->params; ?><div class="row">
    <div class="col-lg-3" style="min-width: 200px;">
        <?php

        $images = new iceWidget($this->DB, 'images', $this->settings);
        $images->show($this->moduleData->material->files);
        if(!is_null($images->styles->styles)){
            $this->styles->addStyles($images->styles->styles);
        }
        if(!is_null($images->jscripts->scripts)){
            $this->jscripts->addScripts($images->jscripts->scripts);
        }

        ?>
        <p>&nbsp;</p>
        <p class="goodItem__price">стоимость: <span><?=iceMat::price($this->moduleData->material->params['price'])?></span></p>
    </div>
    <div class="col-lg-9">
        <div class="newsItem__content">
            <?= htmlspecialchars_decode($material['content']) ?>
        </div>
    </div>
</div>