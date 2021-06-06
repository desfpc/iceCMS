<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 */

use ice\Models\Mat;
use ice\Models\StoreRequest;
use ice\Models\StoreRequestList;
use ice\Helpers\Strings;
use ice\Web\Widget;
use ice\Models\RequestStatuses;
use ice\Models\RequestPayments;

$template_folder = $this->settings->path . '/templates/' . $this->settings->template . '';

//подключаем стили и скрипты
include_once($template_folder . '/partial/t_jsandcss.php');
//$this->styles->addStyle('');
//$this->jscripts->addScript('');

//js document.load
include_once($template_folder . '/partial/t_jsreadyglobal.php');
$this->jsready .= '';

include_once($template_folder . '/partial/t_header.php');

$statuses = new RequestStatuses();
$payments = new RequestPayments();

//Получение списка заказов
$this->getRequestValues(['page','status','action','id','search']);
//пейджинация
if (!isset($this->values->page)) {
    $this->values->page = 1;
}

$page = (int)$this->values->page;
if ($page < 1) {
    $page = 1;
}
$perpage = 20;
$conditions = [];

if($this->values->status != '' && $this->values->status != 'all' && !empty($statuses->GetList()[$this->values->status])) {
    $conditions[] = [
        'string' => true,
        'type' => '=',
        'col' => 'status',
        'val' => $this->values->status
    ];
}

$sort = [
    ['col' => 'date_add', 'sort' => 'DESC']
];
$requests = new StoreRequestList($this->DB, $conditions, $sort, $page, $perpage);
$requestsCnt = $requests->getCnt();
$requests = $requests->getRecords();

//JS для срабатывания фильтров
$this->jsready .= "

    $('#filterStatus').change(function(){
        document.location.href='/admin/shop/?status='+$(this).val();
    });

";

?>
    <div class="container sitebody">
        <div class="row">
            <div class="col">
                <?php
                //выводим ошибки
                include_once($template_folder . '/partial/t_alert.php');
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-9"><input type="text" class="form-control" placeholder="Поиск" id="filterSearch"></div>
            <div class="col-3">
                <select class="form-control" id="filterStatus">
                    <option value="all">Все статусы</option>
                    <?php

                    foreach ($statuses->GetList() as $status => $name) {
                        if($this->values->status == $status) {
                            $selected = ' SELECTED';
                        } else { $selected = ''; }
                        echo '<option value="'.$status.'"'.$selected.'>'.$name.'</option>';
                    }

                    ?>
                </select>
            </div>
        </div>
        &nbsp;
        <div class="row">
            <div class="col">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Даты</th>
                            <th>Пользователь</th>
                            <th>Статус</th>
                            <th>Оплата /<br>доставка</th>
                            <th>Заказ</th>
                            <th style="width: 104px;">Действия</th>
                        </tr>
                    </thead>
                    <?php

                    if (!empty($requests)) {
                       echo '<tbody style="font-size: 12px;">';
                       foreach ($requests as $request) {
                           $requestObj = new StoreRequest($this->DB);
                           $requestObj->getRecord($request['id']);

                           if($requestObj->params['user']['login_phone'] != '') {
                               $phone = '<br>'.$requestObj->params['user']['login_phone'];
                           } else {
                               $phone = '';
                           }

                           echo '<tr>
                                     <td>'.$requestObj->params['id'].'</td>
                                     <td><strong>'.Strings::formatDate($requestObj->params['date_add']).'</strong><br>'.Strings::formatDate($requestObj->params['date_edit']).'</td>
                                     <td>
                                        <a target="_parent" href="/admin/users_admin/?mode=edit&id='.$requestObj->params['user']['id'].'">'.$requestObj->params['user']['full_name'].'</a>
                                        <br>'.$requestObj->params['user']['login_email'].$phone.'
                                     </td>
                                     <td style="background-color: '.$statuses->GetColor($requestObj->params['status']).'">'.$statuses->GetName($requestObj->params['status']).'</td>
                                     <td>оплата: <strong>'.$payments->GetName($requestObj->params['payment_method']).'</strong></td>
                                     <td>Стоимость: <strong>'.Mat::price($requestObj->params['price']).'</strong>
                                        <hr />
                                        <ul class="list-unstyled">
                                            ';

                           foreach ($requestObj->params['goods'] as $good) {
                               $url = Mat::GetUrl($good, $this->materialTypes);
                               echo '<li><a href="'.$url.'" target="_blank">'.$good['name'].' 
                               '.$requestObj->params['goodsBuyParams'][$good['id']]['count'].'шт 
                               '.Mat::price($requestObj->params['goodsBuyParams'][$good['id']]['price']).'</a></li>';
                           }

                           echo '
                                        </ul>
                                     </td>
                                     <td></td>
                                 </tr>';
                       }
                       echo '</tbody>';
                    } ?>
                </table>
                <?php

                $pages = new Widget($this->DB, 'pages', $this->settings);
                $pages->show([
                    'count' => $requestsCnt,
                    'perpage' => $perpage,
                    'page' => $page,
                    'url' => $_SERVER['REQUEST_URI']
                ]);

                ?>
            </div>
        </div>
    </div>
<?php include_once($template_folder . '/partial/t_footer.php');