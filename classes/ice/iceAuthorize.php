<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Authorization Class
 * TODO Token authorization
 *
 */

namespace ice;

class iceAuthorize {

    public $autorized;
    public $user;
    public $errors;
    public $secure;

    public function doAuthorize($DB, $login, $pass)
    {
        $user= new iceUser($DB);
        if($user->authorizeUser($pass, $login))
        {
            $this->user=$user;
            $this->autorized=true;
            $this->secure=$user->params['role']['secure'];

            return true;
        }

        $this->errors=array('Не верное сочетание логина и пароля');
        return false;

    }

    public function deAuthorize()
    {
        if(!is_null($this->user))
        {
            $this->user->deauthorizeUser();
        }
        $this->user = null;
        $this->autorized = false;
    }

    public function __construct(iceDB $DB, $login = null, $pass = null)
    {
        $this->autorized = false;
        $this->user = null;
        $this->errors = null;
        $this->secure = false;

        $this->doAuthorize($DB, $login, $pass);
    }

}