<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 *
 * TODO Module for online commerce shop sales report
 *
 */

//секурность
if (!$this->moduleAccess()) {
    return;
}

$this->moduleData = new stdClass();

$this->moduleData->title = $this->settings->site->title;
$this->moduleData->H1 = 'Магазин - отчет по продажам';
$this->moduleData->errors = array();
$this->moduleData->success = array();
