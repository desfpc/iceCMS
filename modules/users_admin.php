<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\iceRender $this
 *
 * TODO User administration module
 *
 */

use ice\iceUser;
use ice\iceUserList;
use ice\iceRedirect;
use ice\Helpers\Strings;

//секурность
if(!$this->moduleAccess())
    {
        return;
    };

$this->moduleData=new stdClass();

$this->moduleData->title=$this->settings->site->title;
$this->moduleData->H1='Пользователи администрирование';
$this->moduleData->errors=[];
$this->moduleData->success=[];

//получение переменных
$this->getRequestValues(['mode','page','role','status']);

$this->moduleData->breadcrumbs = [];
$this->moduleData->breadcrumbs[] = [
    'name' => 'Пользователи администрирование',
    'param' => 'menu',
    'value' => 'users_admin',
    'dir' => 'admin/users_admin'
];

switch ($this->values->mode){

    //форма создания пользователя
    case 'add':

        $this->moduleData->breadcrumbs[] = [
            'name' => 'Создание пользователя',
            'param' => 'menu',
            'value' => 'users_admin'
        ];

        $this->getRequestValues(['action','regEmail','regLogin','regPass','regPass2','regNik','regTel','regFIO','regPD']);

        //обработка заполнения формы на создание пользователя
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            //проверка на наличае данных
            if(($this->values->regEmail == '' && $this->values->regTel == '') || $this->values->regPass == '' || $this->values->regPass2 == ''
                || $this->values->regPD == '')
            {
                $this->moduleData->errors[]='Введены не все обязательные поля';
            }
            //проверка паролей
            elseif($this->values->regPass != $this->values->regPass2)
            {
                $this->moduleData->errors[]='Введенные пароли не совпадают';
            }
            //корректность email
            elseif($this->values->regEmail != '' && !Strings::checkEmail($this->values->regEmail))
            {
                $this->moduleData->errors[]='Введен не верный адрес электронной почты';
            }
            else
            {
                //генерируем, распихиваем переданные параметры в свойство params, заносим пользюка
                $params=array(
                    'id' => null,
                    'login_email' => $this->values->regEmail,
                    'login_phone' => $this->values->regTel,
                    'nik_name' => $this->values->regNik,
                    'full_name' => $this->values->regFIO,
                    'passcode' => null,
                    'status_id' => 1,
                    'password_input' => $this->values->regPass,
                    'password' => null,
                    'date_add' => null,
                    'contacts' => null,
                    'user_state' => null,
                    'user_role' => 1,
                    'sex' => null
                );

                $user = new iceUser($this->DB);
                if($user->registerUser($params))
                {
                    //редиректим на форму редактирования пользователя
                    $this->setFlash('success',['Пользователь успешно создан']);
                    new iceRedirect('/admin/users_admin/?mode=edit&id='.$user->id);
                }
                else
                {
                    $this->moduleData->errors=$user->errors;
                }

            }

        }

        break;

    //список пользователей
    default:

        //страницы
        $page = (int)$this->values->page;
        if($page < 1){
            $page = 1;
        }
        $perpage = 20;

        //ограничиваем список в зависимости от переданной role
        $conditions=null;
        if($this->values->role == ''){
            $this->values->role = 'all';
        }
        elseif($this->values->role != 'all'){
            $conditions[] = [
                'string' => false,
                'type' => '=',
                'col' => 'user_role',
                'val' => $this->values->role
            ];
        }

        //ограничиваем список в зависимости от переданного status
        if($this->values->status == ''){
            $this->values->status = 'all';
        }
        elseif ($this->values->status != 'all'){
            $conditions[] = [
                'string' => false,
                'type' => '=',
                'col' => 'status_id',
                'val' => $this->values->status
            ];
        }

        //список материалов
        $users = new iceUserList($this->DB, $conditions, [['col' => 'id', 'sort' => 'DESC']], $page, $perpage);

        //справочник ролей пользователя
        $userRoles=[];
        $query='SELECT * FROM user_roles ORDER BY id ASC';
        if($res = $this->DB->query($query)){
            if(count($res) > 0){
                $userRoles = $res;
            }
        }

        //справочник статусов пользователя
        $userStatuses = [
            ['id' => 1, 'name' => 'активный'],
            ['id' => 2, 'name' => 'удаленный']
        ];

        //данные для шаблона
        $this->moduleData->page = $page;
        $this->moduleData->perpage = $perpage;
        $this->moduleData->usersCnt = $users->getCnt();
        $this->moduleData->users = $users->getRecords(null);
        $this->moduleData->userRoles = $userRoles;
        $this->moduleData->userStatuses = $userStatuses;

        break;
}