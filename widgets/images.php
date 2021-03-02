<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Widget $this
 */

use ice\Models\File;

$images = [];

if (is_array($this->params) && count($this->params) > 0) {

    foreach ($this->params as $file) {
        if ($file['filetype'] == 'image') {
            $images[] = $file;
        }
    }

}

//krumo($images);

$imagesText = '';

if (count($images) > 0) {

    $this->styles->addStyle('/css/widgets/widgetImages.css');

    $i = 0;
    foreach ($images as $image) {
        ++$i;

        $fileObj = new File($this->DB, $image['id']);
        $fileObj->getRecord();

        //первая картинка
        if ($i == 1) {
            $imagesText .= '<div class="widgetImages__main">';
            $imagesText .= '<a href="' . $fileObj->getFileCacheUrl(800, 0) . '" data-toggle="lightbox" data-gallery="material"><img src="' . $fileObj->getFileCacheUrl(200, 200) . '" alt="' . $fileObj->params['name'] . '" /></a>';
            $imagesText .= '</div>';
        } //виджет-карусель с картинками
        else {
            if ($i == 2) {
                $imagesText .= '<div class="widgetImages__carousel"><div class="widgetImages__carousel-wrapper">';
            }
            $imagesText .= '<div class="widgetImages__carousel-item"><a href="' . $fileObj->getFileCacheUrl(800, 0) . '" data-toggle="lightbox" data-gallery="material"><img src="' . $fileObj->getFileCacheUrl(48, 48) . '" alt="' . $fileObj->params['name'] . '" /></a></div>';
        }
    }
    if ($i > 1) {
        $imagesText .= '</div></div>';
        if ($i > 5) {
            $imagesText .= '<div class="widgetImages__carousel-control-right"></div><div class="widgetImages__carousel-control-left"></div>';
            $this->jscripts->addScript('/js/widgets/widgetImages.js');
            $imagesText .= '<script>var widgetImagesCnt = ' . $i . ';</script>';
        }
    }

    echo '<div class="widgetImages">
    ' . $imagesText . '
</div>';

}