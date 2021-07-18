<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * TODO Form Class
 *
 */

namespace ice\Web;

use ice\Models\Obj;
use visualijoper\visualijoper;

/**
 * Class Form
 * @package ice\Web
 */
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

    /**
     * Form constructor.
     *
     * @param array $form
     * @param array $params
     */
    public function __construct($form = [], $params = [])
    {
        $this->setFormSettings($form);
        $this->setFormParams($params);

        return true;
    }

    /**
     * Устанавливает параметры объекта
     *
     * @param array $params
     */
    public function setFormParams(array $params){
        if(count($params) > 0){
            $this->params = $params;
        }
    }

    /**
     * Устанавливает настройки объекта
     *
     * @param array $form
     * @return bool
     */
    public function setFormSettings(array $form){
        if(!empty($form)){
            $this->form = $form;
        }
        return true;
    }

    //TODO формирование массива полей формы (разбитие параметров по строкам)
    public function makeFormRows(array $rows):array
    {

    }

    /**
     * формирование массива полей формы из объекта (для быстрого создания массива для виджета формы)
     *
     * @param Obj $obj
     * @return false
     */
    public function makeFromObj(Obj $obj)
    {
        if(isset($obj->cols) && is_array($obj->cols) && count($obj->cols) > 0){

            foreach ($obj->cols as $col){

                //формируем данные по полю

                //Заполнение значения параметра
                $param = [];
                $param['name'] = $col['Field'];
                if(isset($obj->params) && is_array($obj->params) && isset($obj->params[$col['Field']])){
                    $param['value'] = $obj->params[$col['Field']];
                }

                //Наименование параметра
                if(isset($obj::$labels[$col['Field']])){
                    $param['label'] = $obj::$labels[$col['Field']];
                }

                //простой разбор типов переменных
                switch ($col['Type']){
                    case 'datetime':
                        $param['validator'] = 'datetime';
                        $param['type'] = 'input';
                        break;
                    case 'json': //TODO дополнительно разобрать JSON
                    case 'text':
                        $param['type'] = 'text';
                        break;
                    default:
                        $param['type'] = 'input';
                        break;
                }

                //устанавлмиваем required
                if($col['Null'] == 'NO'){
                    $param['required'] = true;
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

        //visualijoper::visualijop($this->params);

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