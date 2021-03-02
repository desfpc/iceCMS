<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 */

use ice\Models\File;
use ice\Models\Mat;

//выводим список материалов
if (isset($this->moduleData->mlist) && is_array($this->moduleData->mlist) && count($this->moduleData->mlist) > 0) {
    foreach ($this->moduleData->mlist as $material) {

        $materialUrl = $this->moduleData->mtype->url . '/' . $material['id_char'];

        if (isset($material['favicon']) && !is_null($material['favicon']) && $material['favicon'] != '') {
            $fileObj = new File($this->DB, $material['favicon']);
            $fileObj->getRecord();
            $img = '<img src="' . $fileObj->getFileCacheUrl(150, 150) . '" alt="' . $fileObj->params['name'] . '" class="rounded float-left" width="140" height="140">';
        } else {
            $img = '';
        }

        ?>
        <div class="row row-margin-horizontal">
            <div class="col-sm-2"><a href="<?= $materialUrl ?>"><?= $img ?></a></div>
            <div class="col-sm-10">
                <p class="newsList__date"><?= Mat::formatDate($material['date_event']) ?></p>
                <p class="newsList__name"><a href="<?= $materialUrl ?>"><?= $material['name'] ?></a></p>
                <p class="newsList__anons"><?= $material['anons'] ?></p>
                <p class="newsList__link"><a href="<?= $materialUrl ?>">подробнее</a></p>
            </div>
        </div>
        <?php

    }
}

//TODO пейджинация