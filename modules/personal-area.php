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
use ice\Tools\CSRF;
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