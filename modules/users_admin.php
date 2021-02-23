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

use ice\iceUserList;

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
    'value' => 'users_admin'
];

switch ($this->values->mode){

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