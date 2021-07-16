<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 */

use ice\Helpers\Numbers;
use ice\Helpers\Strings;
use ice\Models\Mat;
use ice\Models\StoreRequest;

//секурность
if (!$this->moduleAccess()) {
    header('Location: /404/');
    die();
}
//Данные по заказу
$request = new StoreRequest($this->DB);
$request->getRecord((int)$this->values->id);
?>
    <h1>Заказ в интернет магазине №<?= $request->params['id'] ?></h1>
    <p>&nbsp;</p>
    <table>
        <tr>
            <td align="right">Заказчик: </td>
            <td><strong><?= $request->params['user']['full_name'] ?></strong></td>
        </tr>
        <tr>
            <td align="right">Телефон: </td>
            <td><strong><?= $request->params['user']['login_phone'] ?></strong></td>
        </tr>
        <tr>
            <td align="right">E-mail: </td>
            <td><strong><?= $request->params['user']['login_email'] ?></strong></td>
        </tr>
        <tr>
            <td align="right">Дата: </td>
            <td><strong><?= Strings::formatDate($request->params['date_add']) ?></strong></td>
        </tr>
    </table>
    <p>&nbsp;</p>
    <table class="bordered" width="100%">
        <tr>
            <th align="right" style="width: 32px;">№</th>
            <th align="left">Наименование</th>
            <th align="left">Категория</th>
            <th align="right" style="width: 64px;">Кол-во</th>
            <th align="right" style="width: 64px;">Цена</th>
            <th align="right" style="width: 64px;">Сумма</th>
        </tr><?php
        $i = 0;
        $allCnt = 0;
        $allItems = count($request->params['goods']);
        $allPrice = 0;
        foreach ($request->params['goods'] as $good) {
            ++$i;
            $allCnt += $request->params['goodsBuyParams'][$good['id']]['count'];
            $allGoodPrice = $request->params['goodsBuyParams'][$good['id']]['count'] * $request->params['goodsBuyParams'][$good['id']]['price'];
            $allPrice += $allGoodPrice;
            ?>
        <tr>
            <td align="right"><?= $i ?></td>
            <td align="left"><?= $good['name'] ?></td>
            <td align="left"><?= $good['material_type_name'] ?></td>
            <td align="right"><?= $request->params['goodsBuyParams'][$good['id']]['count'] ?></td>
            <td align="right"><?= Mat::price($request->params['goodsBuyParams'][$good['id']]['price'], false) ?></td>
            <td align="right"><?= Mat::price($allGoodPrice, false) ?></td>
        </tr>
            <?php
        } ?>
        <tr>
            <th colspan="3" align="right">Итого</th>
            <th align="right"><?= $allCnt ?></th>
            <th colspan="2" align="right"><?= Mat::price($allPrice, false) ?></th>
        </tr>
    </table>
    <p>Всего наименований: <?= $allItems ?> (общее кол-во: <?= $allCnt ?> шт.)
        <br><b>На сумму: <?= Mat::price($allPrice) ?></b> (<?= Numbers::parseCost($allPrice) ?>)</p>