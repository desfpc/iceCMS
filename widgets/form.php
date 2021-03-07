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
    'csrf' => 'кодCSRF',
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
                'error' => 'Не верный email'
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
        ],
        'row_5' => [
            [
                'type' => 'radio',
                'label' => 'Radios',
                'name' => 'gridRadios',
                'value' => '1',
                'options' => [
                    [
                        'name' => 'Радио 1',
                        'value' => '1'
                    ],
                    [
                        'name' => 'Радио 2',
                        'value' => '2'
                    ],
                    [
                        'name' => 'Радио 3',
                        'value' => '3'
                    ]
                ]
            ],
            [
                'type' => 'checkbox',
                'label' => 'Чекбокс обычный',
                'name' => 'simpleCheckbox',
                'value' => '1',
                'checked' => true
            ]
        ],
        'row_6' => [
            [
                'type' => 'text',
                'label' => 'Текстовое поле с TinyMCE',
                'class' => 'tinymce',
                'name' => 'content',
                'value' => 'Текст какой-то'
            ]
        ],
        'row_7' => [
            [
                'type' => 'submit',
                'class' => 'btn-success',
                'icon' => 'edit',
                'text' => 'изменить'
            ]
        ]
    ]
];
$this->params['form'] = $formArr; //переопределяем поля из тестового массива TODO убрать после отладки


$formArr = $this->params['form'];

$out = '';

if(is_array($formArr) && count($formArr) > 0){



}

echo $out;

//$this->styles->addStyle('/css/widgets/good.css');



