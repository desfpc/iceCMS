<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 *
 * User password recovery module
 *
 */

use ice\Messages\Message;
use ice\Models\User;

$this->moduleData = new stdClass();

$this->moduleData->title = $this->settings->site->title . ' - восстановление пароля';
$this->moduleData->H1 = 'Восстановление пароля';
$this->moduleData->user = new User($this->DB);
$this->moduleData->errors = [];
$this->moduleData->success = [];

$this->getRequestValues(['auEmail']);

//обработка восстановления пароля
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($this->values->auEmail != '') {
        $user = User::getByEmail($this->DB, $this->values->auEmail);
        if (!is_null($user)) {
            if ($newPass = $user->setRandomPass()) {
                //объекты для рассылки уведомлений
                $message = new Message($this->settings, 'email');
                $telegram = new Message($this->settings, 'telegram');
                //отсылка уведомлений
                if($message->send(
                    $user->params['login_email'],
                    $user->params['full_name'],
                    'Восстановление пароля на сайте "' . $this->settings->site->title . '"',
                    '<h1>Восстановление пароля на сайте "' . $this->settings->site->title . '"</h1>
                     <p>&nbsp;</p>
                     <p>Уважаемый(ая), ' . $user->params['full_name'].'</p>
                     <p>Был сменен Ваш пароль:</p>
                     <p>&nbsp;</p>
                     <h2><strong>Ваши новые учетные данные:</strong></h2>
                     <p>email пользователя: ' . $user->params['login_email'].'</p>
                     <p>пароль: <strong>' . $newPass . '</strong></p>
                     <p>&nbsp;</p>'
                )) {
                    $this->moduleData->success[] = 'Пароль успешно сменен. Ваши новые учетные данные отправлены на указанный адрес электронной почты.';
                    $this->values->auEmail = '';
                } else {
                    $this->moduleData->errors[] = 'Ошибка при отправке email - проверьте указанный адрес или попробуйте еще раз позже';
                }
            } else {
                $this->moduleData->errors[] = 'Ошибка при смене пароля - проверьте указанный адрес или попробуйте еще раз позже';
            }
        } else {
            $this->moduleData->errors[] = 'Не верный email адрес';
        }
    } else {
        $this->moduleData->errors[] = 'Пустой email адрес';
    }
}