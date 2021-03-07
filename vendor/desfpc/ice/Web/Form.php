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

class Form
{

    public $formArr = []; //форма (массив) для виджета

    public function __construct(array $arr)
    {
        $this->formArr = $arr;
        return $this->checkArr();
    }

    //проверка входящего массива на наличае необходимых полей
    public function checkArr()
    {
        return true;
    }

    //формирование массива полей формы из объекта (для быстрого создания массива для виджета формы)
    public function makeFromObj($obj)
    {

    }

    //валидация формы (проверка заполнения)
    public function validate()
    {

    }

}