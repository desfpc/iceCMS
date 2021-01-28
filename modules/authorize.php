<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var \iceRender $this
 */

$this->moduleData=new stdClass();

$this->moduleData->title=$this->settings->site->title.' - авторизация';
$this->moduleData->H1='Авторизация';
$this->moduleData->user=new iceUser($this->DB);
$this->moduleData->errors=array();
$this->moduleData->success=array();

$this->getRequestValues(array('action','auEmail','auPass'));

switch ($this->values->action)
{
    //обработка создания пользователя
    case 'login':

        if($this->authorize->doAuthorize($this->DB, $this->values->auEmail, $this->values->auPass))
        {
            $this->moduleData->success[]='Вы успешно авторизировались!';
        }
        else
        {
            $this->moduleData->errors = $this->authorize->errors;
        }

        break;
}