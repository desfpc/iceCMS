<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 *
 * User registration module
 *
 */

use ice\Helpers\Strings;
use ice\Models\User;

$this->moduleData = new stdClass();

$this->moduleData->title = $this->settings->site->title . ' - регистрация';
$this->moduleData->H1 = 'Регистрация';
$this->moduleData->user = new User($this->DB);
$this->moduleData->errors = array();
$this->moduleData->success = array();

$this->getRequestValues(['action', 'regEmail', 'regLogin', 'regPass', 'regPass2', 'regNik', 'regTel', 'regFIO', 'regPD']);

switch ($this->values->action) {
    //обработка создания пользователя
    case 'add':

        //проверка на наличае данных
        if (($this->values->regEmail == '' && $this->values->regTel == '') || $this->values->regPass == '' || $this->values->regPass2 == ''
            || $this->values->regPD == '') {
            $this->moduleData->errors[] = 'Введены не все обязательные поля';
        } //проверка паролей
        elseif ($this->values->regPass != $this->values->regPass2) {
            $this->moduleData->errors[] = 'Введенные пароли не совпадают';
        } //корректность email
        elseif ($this->values->regEmail != '' && !Strings::checkEmail($this->values->regEmail)) {
            $this->moduleData->errors[] = 'Введен не верный адрес электронной почты';
        } else {
            //генерируем, распихиваем переданные параметры в свойство params, заносим пользюка
            $params = [
                'id' => null,
                'login_email' => $this->values->regEmail,
                'login_phone' => $this->values->regTel,
                'nik_name' => $this->values->regNik,
                'full_name' => $this->values->regFIO,
                'passcode' => null,
                'status_id' => 1,
                'password_input' => $this->values->regPass,
                'password' => null,
                'date_add' => null,
                'contacts' => null,
                'user_state' => null,
                'user_role' => 1,
                'sex' => null
            ];

            $user = new user($this->DB);
            if ($user->registerUser($params)) {
                $this->moduleData->success[] = 'Пользователь успешно зарегистрирован! Войдите, используя свой email и пароль.';
                //$this->getRequestValues(array('action','regEmail','regLogin','regPass','regPass2','regNik','regTel','regFIO','regPD'));
                $this->values->action = '';
                $this->values->regEmail = '';
                $this->values->regLogin = '';
                $this->values->regPass = '';
                $this->values->regPass2 = '';
                $this->values->regNik = '';
                $this->values->regTel = '';
                $this->values->regFIO = '';
                $this->values->regPD = '';
            } else {
                $this->moduleData->errors = $user->errors;
            }

        }

        break;
}