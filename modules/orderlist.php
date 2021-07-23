<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 *
 * User Personal Area module
 *
 */

use ice\Models\StoreRequestList;
use ice\Web\Redirect;

const FROM_ID = 'personal_area';

//проверка прав пользователя
if (!$this->authorize->autorized) {
    new Redirect('/authorize');
}

$this->moduleData = new stdClass();

$this->moduleData->title = 'Список заказов - '.$this->settings->site->title;
$this->moduleData->H1 = 'Список заказов '.$this->authorize->user->params['login_email'];
$this->moduleData->errors = [];
$this->moduleData->success = [];
$this->moduleData->breadcrumbs = [
    [
        'name' => 'Главная',
        'dir' => 'none'
    ],
    [
        'name' => 'Личный кабинет',
        'dir' => 'personal-area'
    ],
    [
        'name' => 'Список заказов',
        'param' => 'menu',
        'value' => 'orderlist'
    ]
];


$this->getRequestValues('page');
if (!isset($this->values->page)) {
    $this->values->page = 1;
}
$page = (int)$this->values->page;
if ($page < 1) {
    $page = 1;
}
$perpage = 20;

$conditions = [
    [
        'string' => false,
        'type' => '=',
        'col' => 'user_id',
        'val' => $this->authorize->user->id
    ]
];
$sort = [
    ['col' => 'date_add', 'sort' => 'DESC']
];

$requests = new StoreRequestList($this->DB, $conditions, $sort, $page, $perpage);
$this->moduleData->requestsCnt = $requests->getCnt();
$this->moduleData->requests = $requests->getRecords();