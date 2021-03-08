<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Widget $this
 */

use ice\Web\Form;

//TODO js валидация (email, int, float, маска, required поле)
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
    'csrf_key' => 'csrf_key',
    'csrf_token' => 'csrf_token',
    'hiddens' => [
        [
            'name' => 'id',
            'value' => '5'
        ],
        [
            'name' => 'mode',
            'value' => 'edit'
        ]
    ],
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
                'error' => 'Не верный email',
                'size' => 12
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
                'size' => 4
            ],
            [
                'label' => 'Стоимость',
                'type' => 'input',
                'name' => 'price',
                'placeholder' => '0',
                'value' => '',
                'required' => false,
                'validator' => 'float',
                'size' => 4
            ],
            [
                'label' => 'Тест валидатора по маске',
                'type' => 'input',
                'name' => 'testMask',
                'placeholder' => '12.12.2020',
                'value' => '',
                'required' => false,
                'validator' => 'mask',
                'mask' => '',
                'size' => 4
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
                'size' => 12,
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
                'size' => 9,
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
                'checked' => false,
                'size' => 3
            ]
        ],
        'row_5' => [
            [
                'type' => 'radio',
                'label' => 'Radios',
                'name' => 'gridRadios',
                'value' => '1',
                'size' => 9,
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
                'checked' => true,
                'size' => 3
            ]
        ],
        'row_6' => [
            [
                'type' => 'text',
                'label' => 'Текстовое поле с TinyMCE',
                'class' => 'tinymce',
                'name' => 'content',
                'value' => 'Текст какой-то',
                'size' => 12
            ]
        ],
        'row_7' => [
            [
                'type' => 'submit',
                'class' => 'btn-success',
                'icon' => 'edit',
                'text' => 'изменить',
                'size' => 12
            ]
        ]
    ]
];
$this->params['form'] = $formArr; //переопределяем поля из тестового массива TODO убрать после отладки

$formArr = $this->params['form'];

$out = '';
$warnings = [];

if(is_array($formArr) && count($formArr) > 0){

    //строим форму
    $out.='<form ';
    foreach (Form::$formParams as $param){
        if(isset($formArr[$param])){
            $out .= $param.'="'.$formArr[$param].'"';
        }
    }
    $out.='>';

    //строим csrf поля
    if(isset($formArr['csrf_key'])){
        $out.='<input type="hidden" name="csrf_key" value="'.$formArr['csrf_key'].'">';
    }
    if(isset($formArr['csrf_token'])){
        $out.='<input type="hidden" name="csrf_token" value="'.$formArr['csrf_token'].'">';
    }

    //скрытые поля
    if(isset($formArr['hiddens']) && is_array($formArr['hiddens']) && count($formArr['hiddens']) > 0){
        foreach ($formArr['hiddens'] as $item){
            $out.='';
        }
    }

    //видимые поля
    if(isset($formArr['values']) && is_array($formArr['values']) && count($formArr['values']) > 0){
        //массив по строкам
        foreach ($formArr['values'] as $row){
            $out.='<div class="form-group row">';

            //массив по колонкам
            foreach ($row as $col){

                if(isset($col['type'])){

                    switch ($col['type']){

                        //обычный текстовый input
                        case 'input':

                            break;

                        //выпадающий список select
                        case 'select':

                            break;

                        //группа input-ов
                        case 'input-group':

                            break;

                        //radio кнопки
                        case 'radio':

                            break;

                        //checkbox в виде switch
                        case 'switch':

                            break;

                        //обычный checkbox
                        case 'checkbox':

                            break;

                        //текстовое поле
                        case 'text':
                            break;

                        //submit кнопка
                        case 'submit':
                            break;

                    }

                }
                else {
                    $warnings[] = $col;
                }

            }

            $out.='</div>';
        }
    }


    $out.='</form>';
}

echo $out;

//$this->styles->addStyle('/css/widgets/good.css');



