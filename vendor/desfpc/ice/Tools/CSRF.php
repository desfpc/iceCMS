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

namespace ice\Tools;

use ice\Settings\Settings;

class CSRF
{

    public $formName;
    private $token = null;
    private $key;
    private $salt;

    public function __construct(Settings $settings, $formName = '')
    {
        $this->formName = $formName;
        $this->salt = $settings->secret;
        return $this->makeToken();
    }

    //формируем ключ формы (что бы был свой токен на каждую форму)

    private function makeToken()
    {
        $this->makeKey();
        $this->token = hash('tiger192,3', 'CSRF_' . $this->salt . $this->key);
        $_SESSION['CSRF_' . $this->key] = $this->token;
        return true;
    }

    //формируем CSRF токен

    private function makeKey()
    {
        $this->key = $this->formName . '_' . time() . '_' . rand(0, 1000);
    }

    //получаем ключ

    public static function checkCSFR($key, $token)
    {

        if (!isset($_SESSION['CSRF_' . $key])) return false;
        if ($token != $_SESSION['CSRF_' . $key]) return false;

        return true;
    }

    //получаем токен

    public function getKey()
    {
        return $this->key;
    }

    //проверяем CSFR

    public function getToken()
    {
        return $this->token;
    }

}