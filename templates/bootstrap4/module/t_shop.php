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
    var requestId = 0;
    var requestObj = {};

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
        window.open('/print_forms/?type=storeRequest&id=' + $(this).attr('request_id'));
    });
    
    $('.editRequestBtn').click(function(){
        requestObj.requestId = requestId;
        requestObj.paymentMethod = $('.paymentMethodSelect').val();
        requestObj.delivery = $('.deliveryTypeSelect').val();
        requestObj.goods = [];
        $('.rGood').each(function(){
            const goodCnt = Number($(this).find('.rGood__count').val());
            const goodPrice = Number($(this).find('.rGood__price').val());
            const goodId = $(this).attr('id').replace('rGood_' + requestId + '_','');
            requestObj.goods[goodId] = {
                id: goodId,
                material_count: goodCnt,
                price: goodPrice
            };
        });
        
        $.ajax({
            method: \"POST\",
            url: \"/?menu=ajax&action=store&type=setRequest&id=\"+requestId,
            data: JSON.stringify(requestObj),
            dataType: \"json\"
        }).done(function ( res ) {
            if(res.success === true) {
                document.location.reload();
            }
        });
    });
    
    function reloadRequest() {
        var allCnt = 0;
        var allPrice = 0; 
        $('.rGood').each(function(){
            var goodCnt = Number($(this).find('.rGood__count').val());
            var goodPrice = Number($(this).find('.rGood__price').val());
            allCnt += goodCnt;
            allPrice += goodCnt * goodPrice;
        });
        $('#edit-form-itogo__count').html(allCnt);
        $('#edit-form-itogo__price').html(number_format(allPrice));
    }
    
    function addToRequest(goodId) {
        reloadRequest();
    }
    
    function deleteFromRequest(goodId) {
        $('#rGood_' + requestId + '_' + goodId).remove();
        reloadRequest();
    }
    
    function storeInit() {
        $('#request-edit-form').css('opacity','0');
        $('#request-edit-form').show().animate({opacity: 1},200);
        $('.rGood__count, .rGood__price').change(function(){
            reloadRequest();
            });
        $('.rGood__del').click(function(){
            const parentRow = $(this).parent().parent();
            const goodId = parentRow.prop('id').replace('rGood_' + requestId + '_', '');
            console.log(goodId);
            deleteFromRequest(goodId);
            });
        reloadRequest();
    }
    
    $('.btn-store-edit').click(function(){
        requestId = $(this).attr('request_id');
        $.ajax({
            method: \"POST\",
            url: \"/?menu=ajax&action=store&type=getRequest&id=\"+requestId,
            dataType: \"json\"
        }).done(function ( res ) {
            console.log( res );
            
            $('.edit-form__products').html('');
            
            res.request.goods.forEach(function(good){
                $('.edit-form__products').append('<tr class=\"rGood\" id=\"rGood_' + requestId + '_' + good.id +'\">' +
                '   <td>' + good.id + '</td>' +
                '   <td><a target=\"_blank\" href=\"' + good.url + '\">' + good.name + '</a></td>' +
                '   <td><input class=\"form-control rGood__count\" type=\"text\" value=\"' + res.request.goodsBuyParams[good.id].count + '\"></td>' +
                '   <td><input class=\"form-control rGood__price\" type=\"text\" value=\"' + res.request.goodsBuyParams[good.id].price + '\"></td>' +
                '   <td><button type=\"button\" class=\"btn btn-danger btn-sm rGood__del\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Удалить\">' +
                '           <i class=\"material-icons md-16 md-light\">delete</i>' +
                '       </button>' +
                '</td>' +
                '</tr>');
            });
            
            $('.edit-form__products').append('<tr id=\"edit-form-itogo\">' +
            '   <td colspan=2><strong>Итого</strong></td>' +
            '   <td id=\"edit-form-itogo__count\"></td>' +
            '   <td id=\"edit-form-itogo__price\"></td>' +
            '   <td></td>' +
            '</tr>');
            
            (Object.entries(res.storeSettings.Sposoby_dostavki)).forEach(function(delivery){
                if(delivery[0] === res.request.delivery) {
                    $('.deliveryTypeSelect').append('<option value=\"' + delivery[0] + '\" SELECTED>' + delivery[1] + '</option>');
                } else {
                    $('.deliveryTypeSelect').append('<option value=\"' + delivery[0] + '\">' + delivery[1] + '</option>');
                }
            });
            
            (Object.entries(res.storeSettings.Sposoby_oplaty)).forEach(function(payment){
                if(payment[0] === res.request.payment_method) {
                    $('.paymentMethodSelect').append('<option value=\"' + payment[0] + '\" SELECTED>' + payment[1] + '</option>');
                } else {
                    $('.paymentMethodSelect').append('<option value=\"' + payment[0] + '\">' + payment[1] + '</option>');
                }
            });
            
            storeInit();    
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
    
    $('#new_material_id').change(function(){
        const id = $('#new_material_id').val();
        if(id !== undefined && id !== '')
            {
            console.log(id);
            $.ajax({
                method: \"POST\",
                url: \"/?menu=ajax&action=store&type=getNewGood&id=\"+id,
                dataType: \"json\"
            }).done(function (res) {
                console.log(res);
                const row = '<tr class=\"rGood\" id=\"rGood_' + requestId + '_' + res.params.id + '\">' +
                '<td>' + res.params.id + '</td><td><a target=\"_blank\" href=\"' + res.params.url + '\">' + res.params.name + '</a></td>' +
                '<td><input class=\"form-control rGood__count\" type=\"text\" value=\"1\"></td>' +
                '<td><input class=\"form-control rGood__price\" type=\"text\" value=\"' + res.params.price + '\"></td>' +
                '<td><button type=\"button\" class=\"btn btn-danger btn-sm rGood__del\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"\" data-original-title=\"Удалить\">' +
                '<i class=\"material-icons md-16 md-light\">delete</i></button></td></tr>';
                $(row).insertBefore('#edit-form-itogo');
                storeInit();
            });
            }
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
                                    <th style="width: 100px;">Кол-во</th>
                                    <th style="width: 150px;">Стоимость</th>
                                    <th style="width: 100px;">Действия</th>
                                </tr>
                                </thead>
                                <tbody class="edit-form__products">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="new_material_id" class="col-sm-2 col-form-label text-right"><strong>Добавить товар</strong></label>
                        <div class="col-sm-10">
                            <select class="form-control selectpicker" data-live-search="true" id="new_material_id" name="new_material_id" aria-describedby="status_idHelp" placeholder="Добавить товар в заказ">
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label text-right"><strong>Доставка</strong></label>
                        <div class="col-sm-4">
                            <select class="form-control deliveryTypeSelect"></select>
                        </div>
                        <label for="name" class="col-sm-2 col-form-label text-right"><strong>Оплата</strong></label>
                        <div class="col-sm-4">
                            <select class="form-control paymentMethodSelect"></select>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success editRequestBtn"><i class="material-icons md-24 md-light">edit</i> Изменить</button>
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