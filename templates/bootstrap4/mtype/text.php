<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 */

use ice\Models\Mat;
use ice\Web\Widget;

//получаем последний активный материал
if ($this->moduleData->mlist) {
    $id = $this->moduleData->mlist[0]['id'];
    $this->moduleData->material = new Mat($this->DB, $id);
    $this->moduleData->material->getRecord();

    $material = $this->moduleData->material->params; ?>
    <div class="row">
    <div class="col-sm-12">
        <div class="newsItem__content">
            <p>&nbsp;</p>
            <?php

            $images = new Widget($this->DB, 'images', $this->settings);
            $images->show($this->moduleData->material->files);
            if (!is_null($images->styles->styles)) {
                $this->styles->addStyles($images->styles->styles);
            }
            if (!is_null($images->jscripts->scripts)) {
                $this->jscripts->addScripts($images->jscripts->scripts);
            }

            ?>
            <?= htmlspecialchars_decode($material['content']) ?>
        </div>
    </div>
    </div><?php

}