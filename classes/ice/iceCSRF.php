<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * CSRF Class
 *
 */

namespace ice;

class iceCSRF {

    private $token = null;
    private $key;
    private $salt;
    public $formName;

    public function __construct(iceSettings $settings, $formName='') {
        $this->formName = $formName;
        $this->salt = $settings->secret;
        return $this->makeToken();
    }

    //формируем ключ формы (что бы был свой токен на каждую форму)
    private function makeKey(){
        $this->key = $this->formName.'_'.time().'_'.rand(0,1000);
    }

    //формируем CSRF токен
    private function makeToken(){
        $this->makeKey();
        $this->token = hash('tiger192,3', 'CSRF_'.$this->salt.$this->key);
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