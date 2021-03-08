<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Widget $this
 */

use ice\Web\Form;

//TODO js валидация (email, int, float, маска, required поле)
//TODO js обработка полей (tinymce, datetimepicker, etc)
//TODO вывод ошибок (при js и серверной валидации)

//Заполнение тестового массива для разработки (и далее документации)
$formArr = [
    'method' => 'POST',
    'id' => 'testForm',
    'paramIdFormula' => '%formId%_%paramName%', //авто формирование id полей формы - возможные варианты - %formId%, %paramName%
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
                'value' => '1',
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
                        'name' => 'Deutsche',
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
                'type' => 'switch',
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

function makeParamId($input,$paramIdFormula){
    return str_replace('%paramName%',$input['name'],$paramIdFormula);
}

function makeInputParams($input,$paramIdFormula,$noClass=false,$noValue=false){

    $params = '';

    if(!isset($input['id'])){
        $input['id'] = makeParamId($input, $paramIdFormula);
    }

    $params.=' id="'.$input['id'].'"';
    $params.=' name="'.$input['name'].'"';
    if(!$noValue){
        $params.=' value="'.$input['value'].'"';
    }

    if(isset($input['placeholder'])){
        $params.=' placeholder="'.$input['placeholder'].'"';
    }

    if(!$noClass){
        $class='form-control';
        if(isset($input['class'])){
            $class.=' '.$input['class'];
        }
        $params.=' class="'.$class.'"';
    }

    if(isset($input['checked']) && $input['checked']){
        $params.=' checked="checked"';
    }

    return $params;

}

function makeLabelAndDiv($input){

    if(isset($input['label'])){
        if(isset($input['size']) && $input['size'] > 3){
            $start='<label for="'.$input['id'].'" class="col-sm-2 col-form-label text-right">'.$input['label'].'</label><div class="col-sm-'.($input['size']-2).'">';
            $end='</div>';
        }
        elseif(isset($input['size'])) {
            $start='<div class="col-sm-'.$input['size'].'"><label for="'.$input['id'].'" class="col-form-label"><strong>'.$input['label'].'</strong></label>';
            $end = '</div>';
        }
        else {
            $start='<div class="col-sm"><label for="'.$input['id'].'" class="col-form-label"><strong>'.$input['label'].'</strong></label>';
            $end = '</div>';
        }
    } elseif(isset($input['size'])) {
        $start='<div class="col-sm-'.$input['size'].'">';
        $end='</div>';
    } else {
        $start='<div class="col-sm">';
        $end='</div>';
    }

    return [$start,$end];
}

function makeColSizeClass($item){
    if(isset($item['size'])){
        $divClass = 'col-sm-'.$item['size'];
    }
    else {
        $divClass = 'col-sm';
    }

    return $divClass;
}

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

    if(!isset($formArr['paramIdFormula'])){
        $formArr['paramIdFormula'] = '%formId%_%paramName%';
    }

    $paramIdFormula = str_replace('%formId%',$formArr['id'],$formArr['paramIdFormula']);

    //скрытые поля
    if(isset($formArr['hiddens']) && is_array($formArr['hiddens']) && count($formArr['hiddens']) > 0){
        foreach ($formArr['hiddens'] as $item){
            $out.='<input type="hidden"';

            $out.=makeInputParams($item, $paramIdFormula);

            $out.='>';
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

                    if(!isset($col['id']) && isset($col['name'])){
                        $col['id'] = makeParamId($col, $paramIdFormula);
                    }

                    switch ($col['type']){

                        //обычный текстовый input
                        case 'password':
                        case 'input':

                            if($col['type'] == 'password'){
                                $type = 'password';
                            } else {
                                $type = 'text';
                            }

                            $divs=makeLabelAndDiv($col);
                            $out.=$divs[0];

                            $out.='<input type="'.$type.'" ';
                            $out.=makeInputParams($col, $paramIdFormula).'>';

                            $out.=$divs[1];

                            break;

                        //выпадающий список select
                        case 'select':

                            $divs=makeLabelAndDiv($col);
                            $out.=$divs[0];

                            $out.='<select ';
                            $out.=makeInputParams($col, $paramIdFormula).'>';

                            if(isset($col['options']) && is_array($col['options']) && count($col['options'])>0){

                                foreach ($col['options'] as $option){

                                    if($col['value'] == $option['value']){
                                        $selected = ' selected';
                                    }
                                    else {
                                        $selected = '';
                                    }

                                    $out.='<option value="'.$option['value'].'"'.$selected.'>'.$option['name'].'</option>';

                                }

                            }

                            $out.='</select>';

                            $out.=$divs[1];

                            break;

                        //группа input-ов
                        case 'input-group':

                            if(isset($col['size'])){
                                $divClass = 'col-sm-'.$col['size'];
                            }
                            else {
                                $divClass = 'col-sm';
                            }

                            $out.='<div class="'.$divClass.'"><div class="input-group">';

                            if(isset($col['label'])){
                                $out .= '<div class="input-group-prepend">
                <span class="input-group-text">'.$col['label'].'</span>
            </div>';

                            }

                            if(isset($col['inputs']) && is_array($col['inputs']) && count($col['inputs'])>0){
                                foreach ($col['inputs'] as $input){
                                    $out.='<input type="text" '.makeInputParams($input, $paramIdFormula).'>';
                                }
                            }

                            $out.='</div></div>';

                            break;

                        //radio кнопки
                        case 'radio':

                            $divs=makeLabelAndDiv($col);
                            $out.=$divs[0];

                            if(isset($col['options']) && is_array($col['options']) && count($col['options']) > 0){

                                $i=0;

                                foreach ($col['options'] as $option){

                                    ++$i;

                                    if(!isset($option['id'])){
                                        $option['id'] = $col['id'].$i;
                                    }

                                    $option['label'] = $option['name'];
                                    $option['name'] = $col['name'];

                                    $out.='<div class="form-check">
  <input class="form-check-input" type="radio" '.makeInputParams($option,$paramIdFormula).'>
  <label class="form-check-label" for="exampleRadios1">
    '.$option['label'].'
  </label>
</div>';

                                }
                            }

                            $out.=$divs[1];

                            break;

                        //checkbox в виде switch
                        case 'switch':

                            $divClass = makeColSizeClass($col);

                            $out.='<div class="custom-control switch form-group '.$divClass.'">';

                            $out.='<input type="checkbox" class="danger" '.makeInputParams($col, $paramIdFormula, true).'>
                            <span class="slider round"></span>';

                            if(isset($col['label'])){
                                $out.='<label class="custom-control-label" for="'.$col['id'].'">'.$col['label'].'</label>';
                            }

                            $out.='</div>';

                            break;

                        //обычный checkbox
                        case 'checkbox':

                            $divClass = makeColSizeClass($col);

                            $out.='<div class="form-group form-check '.$divClass.'">';

                            $out.='<input type="checkbox" class="form-check-input" '.makeInputParams($col, $paramIdFormula, true).'>';

                            if(isset($col['label'])){
                                $out.='<label class="form-check-label" for="exampleCheck1">'.$col['label'].'</label>';
                            }

                            $out.='</div>';

                            break;

                        //текстовое поле
                        case 'text':

                            $divClass = makeColSizeClass($col);

                            $out.='<div class="form-group '.$divClass.'">';

                            if(isset($col['label'])){
                                $out.='<label for="'.$col['id'].'" class="col-form-label">'.$col['label'].'</label>';
                            }

                            $out.='<textarea '.makeInputParams($col, $paramIdFormula, false, true).'>'.$col['value'].'</textarea>';

                            $out.='</div>';

                            break;

                        //submit кнопка
                        case 'submit':

                            $divClass = makeColSizeClass($col);
                            $out.='<div class="form-group '.$divClass.'">';

                            if(isset($col['icon'])){
                                $icon = '<i class="material-icons md-24 md-light">'.$col['icon'].'</i> ';
                            }
                            else {
                                $icon = '';
                            }

                            $out.='<button type="submit" class="btn '.$col['class'].'">'.$icon.$col['text'].'</button>';

                            $out.='</div>';

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



