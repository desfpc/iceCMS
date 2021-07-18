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

use ice\Models\User;
use ice\Web\Redirect;
use ice\Tools\CSRF;
use ice\Helpers\Strings;
//use ice\Web\Form;

const FROM_ID = 'personal_area';

//проверка прав пользователя
if (!$this->authorize->autorized) {
    new Redirect('/authorize');
}

$this->moduleData = new stdClass();

$this->moduleData->title = 'Личный кабинет - '.$this->settings->site->title;
$this->moduleData->H1 = 'Личный кабинет '.$this->authorize->user->params['login_email'];
$this->moduleData->errors = [];
$this->moduleData->success = [];
$this->moduleData->breadcrumbs = [
    [
        'name' => 'Главная',
        'dir' => 'none'
    ],
    [
        'name' => 'Личный кабинет',
        'param' => 'menu',
        'value' => 'personal-area'
    ]
];

//изменение пользователя
try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $this->getRequestValues(['tel','sex','old_pass','new_pass','_csrf']);

        //проверка CSRF
        if (empty($this->values->_csrf) || !CSRF::checkCSFR(FROM_ID, $this->values->_csrf)) {
            $error = 'Ошибка данных формы. Попробуйте отправить еще раз.';
            $this->setFlash('errors', [$error]);
            throw new Exception($error);
        }

        $user = new User($this->DB);
        $user->getRecord($this->authorize->user->params['id']);

        //формирование нового пароля
        if ($this->values->old_pass !== '' && $this->values->new_pass) {
            if ($this->values->new_pass === $this->values->old_pass) {
                $error = 'Ошибка - новый пароль должен отличаться от старого';
                $this->setFlash('errors', [$error]);
                throw new Exception($error);
            }
            if (!password_verify($this->values->old_pass, $this->authorize->user->params['password'])) {
                $error = 'Ошибка - не верный старый пароль';
                $this->setFlash('errors', [$error]);
                throw new Exception($error);
            }
            //генерируем хэш пароля
            if (!Strings::validatePassword($this->values->new_pass)) {
                $error = 'Ошибка - слишком простой новой пароль! Пароль должен быть не короче 8-ми символов, содержать цифры, заглавные и прописные буквы.';
                $this->setFlash('errors', ['Ошибка - слишком простой новой пароль! Пароль должен быть не короче 8-ми символов, содержать цифры, заглавные и прописные буквы.']);
                throw new Exception($error);
            }
            $newPass = password_hash($this->values->new_pass, PASSWORD_DEFAULT);
            $user->params['password'] = $newPass;
        }

        //обновление данных пользователя
        $this->values->tel = Strings::telForSave($this->values->tel);

        $user->params['login_phone'] = $this->values->tel;
        $user->params['sex'] = $this->values->sex;
        if ($user->updateRecord()) {
            $this->setFlash('success', ['Данные успешно изменены']);
            $this->authorize->user->params = $user->params;
        } else {
            $error = 'Ошибка сохранения данных - попробуйте позже';
            $this->setFlash('errors', ['Ошибка сохранения данных - попробуйте позже']);
            throw new Exception($error);
        }
    }
} catch (Exception $e) {
    //errror... ok...
}

$csfr = new CSRF($this->settings,FROM_ID);

//$form = new Form(); TODO формирование формы через класс (который надо таки доделать)
$this->moduleData->formArr = [
    'form' => [
        'method' => 'POST',
        'id' => FROM_ID,
        'paramIdFormula' => '%formId%_%paramName%', //авто формирование id полей формы - возможные варианты - %formId%, %paramName%
        'action' => '',
        'accept-charset' => 'utf-8',
        'enctype' => 'multipart/form-data',
        'name' => 'personalAreaForm',
        'class' => 'personalAreaForm',
        'target' => '_self',
        'csrf_token' => $csfr->getToken(),
        'hiddens' => [],
        'values' => [
            'row_1' => [
                [
                    'label' => 'Телефон',
                    'type' => 'input',
                    'name' => 'tel',
                    'placeholder' => 'Введите номер телефона',
                    'value' => $this->authorize->user->params['login_phone'],
                    'required' => true,
                    'help' => 'Заполняется опционально. Другие пользователи его не увидят.',
                    'validator' => 'phone',
                    'error' => 'Не верный формат телефона',
                    'size' => 6
                ],
                [
                    'label' => 'Пол',
                    'type' => 'select',
                    'options' => [
                        ['name' => 'Не выбран', 'value' => ''],
                        ['name' => 'Мужской', 'value' => 'm'],
                        ['name' => 'Женский', 'value' => 'w'],
                        ['name' => 'Другой', 'value' => 'o'],
                    ],
                    'name' => 'sex',
                    'placeholder' => 'Укажите свой пол',
                    'value' => $this->authorize->user->params['sex'],
                    'required' => false,
                    'help' => 'Заполняется опционально. Выберите подходящий пол.',
                    'size' => 6
                ]
            ],
            'row_2' => [
                [
                    'label' => 'Пароль',
                    'type' => 'password',
                    'name' => 'old_pass',
                    'placeholder' => 'Текущий пароль (для смены)',
                    'value' => '',
                    'required' => false,
                    'help' => 'Заполняется для смены пароля',
                    'validator' => 'password',
                    'error' => 'Не верный текущий пароль',
                    'size' => 6
                ],
                [
                    'label' => 'Новый пароль',
                    'type' => 'password',
                    'name' => 'new_pass',
                    'placeholder' => 'Новый пароль',
                    'value' => '',
                    'required' => false,
                    'help' => 'Заполняется для смены пароля',
                    'validator' => 'password',
                    'error' => 'Слишком лёгкий пароль',
                    'size' => 6
                ]
            ],
            'row_3' => [
                [
                    'type' => 'submit',
                    'class' => 'btn-success',
                    'icon' => 'edit',
                    'text' => 'Изменить',
                    'size' => 12
                ]
            ]
        ]
    ]
];