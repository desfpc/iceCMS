<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 */

use ice\Models\Mat;
use ice\Web\Widget;

$material = $this->moduleData->material->params; ?>
<div class="row">
    <div class="col-sm-12">
        <div class="newsItem__date"><?= Mat::formatDate($material['date_event']) ?></div>
        <div class="newsItem__content">
            <?php

            $images = new Widget($this->DB, 'images', $this->settings);
            $images->show($this->moduleData->material->files);
            $this->styles->addStyles($images->styles->styles);
            $this->jscripts->addScripts($images->jscripts->scripts);

            ?>
            <?= htmlspecialchars_decode($material['content']) ?>
        </div>
    </div>
</div>