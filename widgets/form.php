<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Widget $this
 */


$formArr = [
    'method' => 'POST',
    'id' => 'testForm',
    'action' => '',
    'accept-charset' => 'utf-8',
    'enctype' => 'multipart/form-data',
    'name' => 'testForm',
    'class' => 'test-form',
    'target' => '_self',
    'values' => [
        'row_1' => [
            [
                'label' => 'Email адрес',
                'type' => 'input',
                'name' => 'regEmail',
                'placeholder' => 'Введите email',
                'value' => '',
                'required' => true,
                'help' => 'Является login-ом на сайте. Другие пользователи его не увидят.'
            ]
        ]
    ]
];

//$this->styles->addStyle('/css/widgets/good.css');



