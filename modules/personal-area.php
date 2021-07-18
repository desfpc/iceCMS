<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 *
 * User Personal Area module
 *
 */

use ice\Web\Redirect;

//проверка прав пользователя
if (!$this->authorize->autorized) {
    new Redirect('/authorize');
}

$this->moduleData = new stdClass();

$this->moduleData->title = 'Личный кабинет';
$this->moduleData->H1 = 'Личный кабинет';
$this->moduleData->errors = [];
$this->moduleData->success = [];