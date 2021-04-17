<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 */

use ice\Models\Mat;
use ice\Models\MatList;
use ice\Models\MatType;
use ice\Models\File;
//use visualijoper\visualijoper;

$allCost = 0;
$allCnt = 0;
$goodsOut = '';

if(isset($_SESSION['cart'])){
    $allCost = $_SESSION['cart']['allCost'];
    $allCnt = $_SESSION['cart']['allCnt'];
    $cartGoods = '';

    if(isset($_SESSION['cart']['goods']) && is_array($_SESSION['cart']['goods']) && count($_SESSION['cart']['goods']) > 0) {

        //visualijoper::visualijop($_SESSION['cart']['goods']);

        foreach ($_SESSION['cart']['goods'] as $good) {
            if($cartGoods != ''){
                $cartGoods .= ',';
            }
            $cartGoods .= $good['id'];
        }

        $conditions[] = [
            'string' => false,
            'type' => 'IN',
            'col' => 'id',
            'val' => $cartGoods
        ];
        $materials = new MatList($this->DB, $conditions, [['col' => 'name', 'sort' => 'ASC']], 1, 1000);
        $goods = $materials->getRecords();

        foreach ($goods as $material) {
            $goodsOut .= '<tr>';

            if ($material['favicon'] != '') {
                $file = ['id' => $material['favicon']];
                $favicon = File::formatIcon($this->DB, $file, true);
            } else {
                $favicon = '';
            }

            $mtype = new MatType($this->DB, $material['material_type_id']);
            $mtype->getRecord();
            $mtype->getURL();

            $url = $mtype->url . '/' . $material['id_char'];

            $goodsOut.='<td>' . $favicon . '</td>';
            $goodsOut.='<td><a href="' . $url . '">' . $material['name'] . '</a></td>';
            $goodsOut.='<td>' . Mat::price($_SESSION['cart']['goods'][$material['id']]['price']) . '</td>';
            $goodsOut.='<td><input type="text" data="' . $material['id'] . '" class="form-control cart-good-cnt" value="' . $_SESSION['cart']['goods'][$material['id']]['count'] . '"></td>';
            $goodsOut.='<td><b>' . Mat::price($_SESSION['cart']['goods'][$material['id']]['cost']) . '</b></td>';

            $goodsOut.= '</tr>';
        }
    }

}

?>
<div class="row">
    <div class="col-sm-12">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th></th>
                    <th>Товар</th>
                    <th>Стоимость</th>
                    <th>Кол-во</th>
                    <th>Итого</th>
                </tr>
            </thead>
            <?=$goodsOut?>
            <tr>
                <td colspan="3">
                <th><?=$allCnt?></th>
                <th><?=Mat::price($allCost)?></th>
            </tr>
        </table>
    </div>
</div>