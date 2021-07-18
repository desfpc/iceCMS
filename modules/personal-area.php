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

$this->moduleData->title = 'Личный кабинет - '.$this->settings->site->title;
$this->moduleData->H1 = 'Личный кабинет '.$this->authorize->user->params['login_email'];
$this->moduleData->errors = [];
$this->moduleData->success = [];