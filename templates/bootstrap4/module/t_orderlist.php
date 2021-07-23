<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 */

use ice\Helpers\Strings;
use ice\Models\Mat;
use ice\Models\RequestPayments;
use ice\Models\RequestStatuses;
use ice\Models\StoreRequest;

$template_folder = $this->settings->path . '/templates/' . $this->settings->template . '';

//подключаем стили и скрипты
include_once($template_folder . '/partial/t_jsandcss.php');
include_once($template_folder . '/partial/t_jsreadyglobal.php');
$this->jsready .= '';

include_once($template_folder . '/partial/t_header.php');

$statuses = new RequestStatuses();
$payments = new RequestPayments();

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
            <div class="col-md-3">
                <div class="hMenu">
                    <a href="/personal-area" class="hMenu_link" aria-current="true">
                        Настройки
                    </a>
                    <a href="/personal-area/orderlist" class="hMenu_link hMenu_link_active" aria-current="true">Список заказов</a>
                </div>
            </div>
            <div class="col-md-9">
                <?php if ($this->moduleData->requestsCnt === 0) {
                    ?><div class="alert alert-secondary" role="alert">
                        Заказов еще нет
                    </div><?php
                } else {
                    ?>
                    <table class="table table-striped">
                        <thead class="thead-dark">
                        <tr>
                            <th style="width: 71px;">ID</th>
                            <th>Даты</th>
                            <th>Статус</th>
                            <th>Оплата /<br>доставка</th>
                            <th>Заказ</th>
                        </tr>
                        </thead>
                        <tbody style="font-size: 12px;">
                        <?php foreach ($this->moduleData->requests as $request) {
                            $requestObj = new StoreRequest($this->DB);
                            $requestObj->getRecord($request['id']);

                            $icon = $statuses->getIcon($requestObj->params['status']);
                            if (!is_null($icon)) {
                                $icon = '<i class="material-icons md-16">'.$icon.'</i> ';
                            }
                            else {
                                $icon = '';
                            }

                            echo '<tr>
                                     <td><strong>'.$requestObj->params['id'].'</strong></td>
                                     <td><strong>'.Strings::formatDate($requestObj->params['date_add']).'</strong><br>'.Strings::formatDate($requestObj->params['date_edit']).'</td>
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

                            echo '
                                        </ul>
                                     </td>
                                  </tr>';

                        } ?>
                        </tbody>
                    </table>
                    <?php
                } ?>
            </div>
        </div>
    </div>
<?php include_once($template_folder . '/partial/t_footer.php');