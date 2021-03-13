<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Form Class
 *
 */

namespace ice\Web;

use ice\Models\Obj;
use visualijoper\visualijoper;

class Form
{

    public $formArr = []; //массив формы для виджета
    public $form = []; //массив настроек самой формы
    public $params = []; //массив параметров
    public $validationErrors = []; //массив ошибок валидации

    //параметры формы для валидации массива и виджета
    public static $formParams = [
        'method',
        'id',
        'action',
        'accept-charset',
        'enctype',
        'name',
        'class',
        'target'
    ];

    public function __construct($form = [], $params = [])
    {
        $this->setFormSettings($form);
        $this->setFormParams($params);

        return true;
    }

    public function setFormParams(array $params){
        if(count($params) > 0){
            $this->params = $params;
        }
    }

    public function setFormSettings(array $form){
        if(count($form) > 0){
            $this->form = $form;
        }
        return true;
    }

    //формирование массива полей формы (разбитие параметров по строкам)
    public function makeFormRows(array $rows):array
    {

    }

    //формирование массива полей формы из объекта (для быстрого создания массива для виджета формы)
    public function makeFromObj(Obj $obj)
    {
        if(isset($obj->cols) && is_array($obj->cols) && count($obj->cols) > 0){

            foreach ($obj->cols as $col){

                //формируем данные по полю
                $param = [];
                $param['name'] = $col['Field'];
                if(isset($obj->params) && is_array($obj->params) && isset($obj->params[$col['Field']])){
                    $param['value'] = $obj->params[$col['Field']];
                }

                /*'label' => 'Email адрес',
                'type' => 'input',
                'name' => 'regEmail',
                'placeholder' => 'Введите email',
                'value' => '',
                'required' => true,
                'help' => 'Является login-ом на сайте. Другие пользователи его не увидят.',
                'validator' => 'email',
                'error' => 'Не верный email',
                'size' => 12*/

                //заносим поле в свойство
                $this->params[$param['name']] = $param;
            }

        }

        visualijoper::visualijop($this->params);

        return false;
    }

    //получение значений заполненной формы
    public function getFormValues(){

    }

    //валидация формы (проверка заполнения)
    public function validate()
    {

    }

}