<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * CSFR Class
 *
 */

namespace ice;

class iceCSRF {

    const SALT = 'dfdfv_d3453!!3SD&@#sdc4_DcsdcDC_D@wsd$%#'; //TODO вынести соль в настройки приложения

    private $token = null;
    private $key;
    public $formName;

    public function __construct($formName='') {
        $this->formName = $formName;
        return $this->makeToken();
    }

    //формируем ключ формы (что бы был свой токен на каждую форму)
    private function makeKey(){
        $this->key = $this->formName.time();
    }

    //формируем CSRF токен
    private function makeToken(){
        $this->makeKey();
        $this->token = hash('tiger192,3', 'CSRF_'.self::SALT.$this->key);
        $_SESSION['CSRF_'.$this->key] = $this->token;
        return true;
    }

    //получаем ключ
    public function getKey(){
        return $this->key;
    }
    //получаем токен
    public function getToken(){
        return $this->token;
    }

    //проверяем CSFR
    public static function checkCSFR($key, $token){

        if(!isset($_SESSION['CSRF_'.$key])) return false;
        if($token != $_SESSION['CSRF_'.$key]) return false;

        return true;
    }

}