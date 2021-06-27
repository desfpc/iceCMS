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


$this->getRequestValues(['page','status','action','id','search','to_status','request_id']);

//действия над заказом
if ($this->values->to_status != '' && $this->values->request_id != '') {
    $query = "UPDATE store_requests 
SET status = '".$this->DB->mysqli->real_escape_string($this->values->to_status)."', date_edit = NOW()
WHERE id = ".$this->DB->mysqli->real_escape_string($this->values->request_id);
    if ($res = $this->DB->query($query))
    {
       $this->moduleData->success[] = 'Статус заказа успешно изменен';
    }
}

//пейджинация
if (!isset($this->values->page)) {
    $this->values->page = 1;
}

$page = (int)$this->values->page;
if ($page < 1) {
    $page = 1;
}
$perpage = 20;

//Получение списка заказов
$conditions = [];

if($this->values->status !== '' && $this->values->status !== 'all' && !empty($statuses->getList()[$this->values->status])) {
    $conditions[] = [
        'string' => true,
        'type' => '=',
        'col' => 'status',
        'val' => $this->values->status
    ];
}

if($this->values->search !== '') {
    $realSearch = (float)$this->values->search;
    if ($realSearch == $this->values->search && $realSearch !== (float)0) {
        $conditions[] = [
            'logic' => 'OR',
            'string' => false,
            'type' => '=',
            'col' => 'price',
            'val' => $realSearch
        ];
        $conditions[] = [
            'logic' => 'OR',
            'string' => false,
            'type' => '=',
            'col' => 'id',
            'val' => (int)$realSearch
        ];
    } else {
        $conditions[] = [
            'logic' => 'AND',
            'string' => false,
            'type' => 'LIKE',
            'col' => 'LOWER(comment)',
            'val' => "LOWER('%".$this->values->search."%')"
        ];
    }
}

$sort = [
    ['col' => 'date_add', 'sort' => 'DESC']
];
$requests = new StoreRequestList($this->DB, $conditions, $sort, $page, $perpage);
$requestsCnt = $requests->getCnt();
$requests = $requests->getRecords();



//JS для срабатывания фильтров
$this->jsready .= "

    var search = '".urlencode($this->values->search)."';
    var status = '".$this->values->status."';

    $('#filterStatus').change(function(){
        document.location.href='/admin/shop/?status='+$(this).val()+'&search='+search;
    });
    
    $('#filterSearch').change(function(){
        document.location.href='/admin/shop/?search='+$(this).val()+'&status='+status;
    });
    
    $('.btn-store-admin').click(function(){
        if(confirm('Сменить статус заказа '+$(this).attr('request_id')+' на '+$(this).attr('request_name'))) {
            document.location.href='/admin/shop/?search='+search+'&status='+$(this).attr('to_status')+'&to_status='+$(this).attr('to_status')+'&request_id='+$(this).attr('request_id');
        }
    });
    
    $('.btn-store-print').click(function(){
        
    });
    
    $('.btn-store-edit').click(function(){
        
        $.ajax({
            method: \"POST\",
            url: \"/?menu=ajax&action=store&type=getRequest&id=\"+$(this).attr('request_id'),
            dataType: \"json\"
        }).done(function ( res ) {
            console.log( res.request );
            
            $('.edit-form__products').html('');
            
            res.request.goods.forEach(function(good){
                console.log ( good );
                $('.edit-form__products').append('<tr>' +
                '   <td>' + good.id + '</td>' +
                '   <td><a target=\"_blank\" href=\"' + good.url + '\">' + good.name + '</a></td>' +
                '   <td>' + res.request.goodsBuyParams[good.id].count + '</td>' +
                '   <td>' + res.request.goodsBuyParams[good.id].price + '</td>' +
                '   <td></td>' +
                '</tr>');
            });
            
            $('#request-edit-form').css('opacity','0');
            $('#request-edit-form').show().animate({opacity: 1},200); 
        });
    });
    
    $('.modal-fullscreen .close-btn').click(function(){
        $('.modal-fullscreen').animate({opacity: 0},200,function() {
            $('.modal-fullscreen').hide();
        });
    });
    
    $('#new_material_id')
        .selectpicker({
            liveSearch: true
        })
        .ajaxSelectPicker({
        ajax: {
            url: '/ajax/?action=getstoreproduct',
            data: function () {
                var params = {
                    query: '{{{q}}}'
                };
               
                return params;
            }
        },
        locale: {
            emptyTitle: 'Поиск товара...'
        },
        preprocessData: function(data){
        
        console.log(data);
        
            var types = [];
            if(data.hasOwnProperty('types')){
                var len = data.types.length;
                for(var i = 0; i < len; i++){
                    var curr = data.types[i];
                    types.push(
                        {
                            'value': curr.id,
                            'text': curr.name,
                            'disabled': false
                        }
                    );
                }
            }
            return types;
        },
        preserveSelected: false
    });
";

$this->jscripts->addScript('/js/bootstrap-select.js');
$this->jscripts->addScript('/js/defaults-ru_RU.js');
$this->jscripts->addScript('/js/ajax-bootstrap-select.js');
$this->jscripts->addScript('/js/ajax-bootstrap-select.ru-RU.min.js');

$this->styles->addStyle('/css/bootstrap-select.css');
$this->styles->addStyle('/css/ajax-bootstrap-select.css');

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
        <div class="modal-fullscreen" id="request-edit-form">
            <div class="container" style="min-width: 80%; text-align: left;">
                <form method="post" action="/cart" id="edit-form">
                    <div class="row">
                        <div class="col">
                            <div class="close-btn"><i class="material-icons md-24">close</i></div>
                            <h2>Форма редактирования заказа <span id="edit-form__header-id"></span></h2>
                            <table class="table">
                                <thead class="thead-dark">
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Товар</th>
                                    <th>Кол-во</th>
                                    <th>Стоимость</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody class="edit-form__products">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="new_material_id" class="col-sm-2 col-form-label text-right"><strong>Добавить товар в заказ</strong></label>
                        <div class="col-sm-10">
                            <select class="form-control selectpicker" data-live-search="true" id="new_material_id" name="new_material_id" aria-describedby="status_idHelp" placeholder="Добавить товар в заказ">
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label text-right"><strong>Статус</strong></label>
                        <div class="col-sm-10">
                            <select class="form-control">

                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label text-right"><strong>Тип оплаты</strong></label>
                        <div class="col-sm-10">
                            <select class="form-control">

                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success"><i class="material-icons md-24 md-light">edit</i> Изменить</button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-9"><input type="text" class="form-control" placeholder="Поиск" id="filterSearch" value="<?= $this->values->search ?>"></div>
            <div class="col-3">
                <select class="form-control" id="filterStatus">
                    <option value="all">Все статусы</option>
                    <?php

                    foreach ($statuses->getList() as $status => $name) {
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
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width: 71px;">ID</th>
                            <th>Даты</th>
                            <th>Пользователь</th>
                            <th>Статус</th>
                            <th>Оплата /<br>доставка</th>
                            <th>Заказ</th>
                            <th style="width: 104px;">Новый статус</th>
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

                           $icon = $statuses->getIcon($requestObj->params['status']);
                           if (!is_null($icon)) {
                               $icon = '<i class="material-icons md-16">'.$icon.'</i> ';
                           }
                           else {
                               $icon = '';
                           }

                           echo '<tr>
                                     <td>
                                     <strong>'.$requestObj->params['id'].'</strong>
                                     <hr>
                                     <button class="btn btn-store-admin_small btn-store-print btn-info" request_id="'.$requestObj->params['id'].'"
                                        data-toggle="tooltip" data-placement="top" title="" data-original-title="Печатная форма"><i class="material-icons md-16">print</i></button>
                                     <button class="btn btn-store-admin_small btn-store-edit btn-warning" request_id="'.$requestObj->params['id'].'"
                                        data-toggle="tooltip" data-placement="top" title="" data-original-title="Форма редактирования"><i class="material-icons md-16">create</i></button>
                                     </td>
                                     <td><strong>'.Strings::formatDate($requestObj->params['date_add']).'</strong><br>'.Strings::formatDate($requestObj->params['date_edit']).'</td>
                                     <td>
                                        <a target="_parent" href="/admin/users_admin/?mode=edit&id='.$requestObj->params['user']['id'].'">'.$requestObj->params['user']['full_name'].'</a>
                                        <br>'.$requestObj->params['user']['login_email'].$phone.'
                                     </td>
                                     <td style="background-color: '.$statuses->GetColor($requestObj->params['status']).'">'.$icon.$statuses->getName($requestObj->params['status']).'</td>
                                     <td>
                                        оплата: <strong>'.$payments->getName($requestObj->params['payment_method']).'</strong>
                                        <hr>
                                        комментарий: 
                                        <br>'.$request['comment'].'
                                     </td>
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

                           $toStatuses = $statuses->getActions($request['status']);

                           echo '
                                        </ul>
                                     </td>
                                     <td>
                                        ';

                           if (!empty($toStatuses)) {
                               foreach ($toStatuses as $action) {
                                   $icon = $statuses->getIcon($action);
                                   if (!is_null($icon)) {
                                       $icon = '<i class="material-icons md-16">'.$icon.'</i>';
                                   }
                                   else {
                                       $icon = '';
                                   }
                                   echo '<button class="btn btn-store-admin '.$statuses->getBtnClass($action).'" to_status="'.$action.'" request_id="'.$request['id'].'" request_name="'.$statuses->getName($action).'">'.$icon.' '.$statuses->getName($action).'</button>';
                               }
                           }

                           echo '          </td>
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