<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 *
 * Print Forms Module
 *
 */

$this->moduleData = new stdClass();
$this->moduleData->title = $this->settings->site->title.' : Печатная форма';
$this->moduleData->errors = [];
$this->moduleData->success = [];