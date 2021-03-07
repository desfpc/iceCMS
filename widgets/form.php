<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Widget $this
 */

//TODO js валидация (email, int, float, маска, required поле)
//TODO генерация HTML из массива
//TODO вывод ошибок (при js и серверной валидации)
//TODO тестовый массив - обычные чекбоксы, радиобутаны, текстареи, субмит

//Заполнение тестового массива для разработки (и далее документации)
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
                'help' => 'Является login-ом на сайте. Другие пользователи его не увидят.',
                'validator' => 'email',
            ]
        ],
        'row_2' => [
            [
                'label' => 'Код товара',
                'type' => 'input',
                'name' => 'goodcode',
                'placeholder' => 'код товара',
                'value' => '',
                'required' => false,
            ],
            [
                'label' => 'Стоимость',
                'type' => 'input',
                'name' => 'price',
                'placeholder' => '0',
                'value' => '',
                'required' => false,
                'validator' => 'float'
            ],
            [
                'label' => 'Тест валидатора по маске',
                'type' => 'input',
                'name' => 'testMask',
                'placeholder' => '12.12.2020',
                'value' => '',
                'required' => false,
                'validator' => 'mask',
                'mask' => ''
            ]
        ],
        'row_3' => [
            [
                'label' => 'Язык',
                'type' => 'select',
                'name' => 'language',
                'live-search' => false,
                'value' => '',
                'class' => 'selectpicker',
                'options' => [
                    [
                        'name' => 'Русский',
                        'value' => '1'
                    ],
                    [
                        'name' => 'English',
                        'value' => '2'
                    ],
                    [
                        'name' => 'English',
                        'value' => '3'
                    ]
                ]
            ]
        ],
        'row_4' => [
            [
                'type' => 'input-group',
                'label' => 'Дата начала и окончания события',
                'inputs' => [
                    [
                        'type' => 'input',
                        'name' => 'date_event',
                        'value' => '07.03.2021 13:24',
                        'placeholder' => 'dd.mm.yyyy hh:ii',
                        'validator' => 'datetime'
                    ],
                    [
                        'type' => 'input',
                        'name' => 'date_end',
                        'value' => '10.03.2021 13:24',
                        'placeholder' => 'dd.mm.yyyy hh:ii',
                        'validator' => 'datetime'
                    ]
                ]
            ],
            [
                'type' => 'switch ',
                'label' => 'Важный материал',
                'name' => 'important',
                'value' => 1,
                'checked' => false
            ]
        ]
    ]
];

//$this->styles->addStyle('/css/widgets/good.css');



