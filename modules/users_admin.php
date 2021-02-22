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
$this->getRequestValues(['mode','page','role']);

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

        //список материалов
        $users = new iceUserList($this->DB, $conditions, [['col' => 'id', 'sort' => 'DESC']], $page, $perpage);
        $this->moduleData->page = $page;
        $this->moduleData->perpage = $perpage;
        $this->moduleData->usersCnt = $users->getCnt();
        $this->moduleData->users = $users->getRecords(null);


        break;
}