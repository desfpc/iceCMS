<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 *
 * TODO User administration module
 *
 */

use ice\Models\User;
use ice\Models\UserList;
use ice\Web\Redirect;
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
$this->getRequestValues(['mode']);

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
            if(($this->values->regEmail == '' && $this->values->regTel == '') || $this->values->regPass == '' || $this->values->regPass2 == '')
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

                $user = new User($this->DB);
                if($user->registerUser($params))
                {
                    //редиректим на форму редактирования пользователя
                    $this->setFlash('success',['Пользователь успешно создан']);
                    new Redirect('/admin/users_admin/?mode=edit&id='.$user->id);
                }
                else
                {
                    $this->moduleData->errors=$user->errors;
                }

            }

        }

        break;

    case 'edit':

        $this->getRequestValues(['action','regEmail','regLogin','regPass','regPass2','regNik','regTel','regFIO','regPD', 'id']);

        //пробуем получить пользователя
        if($this->values->id != ''){
            $user = new User($this->DB, (int)$this->values->id);
            if(!$user->getRecord()) {
                new Redirect('/404');
            }
        }

        $this->moduleData->breadcrumbs[] = [
            'name' => $user->params['login_email'],
            'param' => 'menu',
            'value' => 'users_admin'
        ];

        //TODO обработка изменения пользователя
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

        }
        else {
            //заполняем поля данными пользователя для редактирования
            $this->values->regEmail = $user->params['login_email'];
            $this->values->regNik = $user->params['nik_name'];
            $this->values->regTel = $user->params['login_phone'];
            $this->values->regFIO = $user->params['full_name'];
        }

        break;

    //список пользователей
    default:

        $this->getRequestValues(['page','role','status','action','id']);

        //действия (без формы редактирования)
        if($this->values->action != '' && $this->values->id != ''){

            //действия, требующие получения пользователя
            $userActions = ['disable','enable'];

            if(in_array($this->values->action,$userActions)){

                $id = (int)$this->values->id;
                $user = new User($this->DB, $id);
                if(!$user->getRecord()){
                    new Redirect('/404');
                }

                switch ($this->values->action){
                    //отключение пользователя
                    case 'disable':
                        if($user->disableUser()){
                            $this->moduleData->success[]='Пользователь '.$user->params['login_email'].' отключен';
                        }
                        else{
                            $this->moduleData->errors[]='Ошибка отключения пользователя '.$user->params['login_email'];
                        }
                        break;

                    case 'enable':
                        if($user->enableUser()){
                            $this->moduleData->success[]='Пользователь '.$user->params['login_email'].' включен';
                        }
                        else{
                            $this->moduleData->errors[]='Ошибка включения пользователя '.$user->params['login_email'];
                        }
                        break;
                }

            }
        }

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
        $users = new UserList($this->DB, $conditions, [['col' => 'id', 'sort' => 'DESC']], $page, $perpage);

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
            ['id' => 2, 'name' => 'отключенный']
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