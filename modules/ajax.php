<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 *
 * Module for processing Ajax requests
 *
 */

use ice\Models\StoreRequestGood;
use ice\Web\HeaderBuilder;
use ice\Models\Mat;
use ice\Models\MatList;
use ice\Models\StoreRequest;

$this->headers = new HeaderBuilder();
$this->headers->standartHeaders();
$this->headers->addHeader('Content-Type: application/json');

$this->moduleData = new stdClass();

$this->getRequestValue('action');

/**
 * @return array
 */
function makeCartAllCost():array {
    $allCost = 0;
    $allCnt = 0;
    foreach ($_SESSION['cart']['goods'] as $good){
        $allCost += $good['cost'];
        $allCnt += $good['count'];
        $_SESSION['cart']['goods'][$good['id']]['formatedCost'] = Mat::price($good['cost']);
        $_SESSION['cart']['goods'][$good['id']]['formatedPrice'] = Mat::price($good['price']);
    }

    $_SESSION['cart']['allCost'] = $allCost;
    $_SESSION['cart']['allFormatedCost'] = Mat::price($allCost);
    $_SESSION['cart']['allCnt'] = $allCnt;

    return [
        'goods' => $_SESSION['cart']['goods'],
        'allCost' => $allCost,
        'allFormatedCost' => Mat::price($allCost),
        'allCnt' => $allCnt
    ];
}

/**
 * @param array $storeSettings
 * @param string[] $param
 * @return array
 */
function storeSettingsParamToArray(array $storeSettings, array $params):array {

    foreach ($params as $param) {
        $storeSettingsPayments = explode(';',$storeSettings[$param]);
        $storeSettings[$param] = [];
        foreach ($storeSettingsPayments as $key => $value) {
            if ($value !== '') {
                $tempArr = explode(':', $value);
                $storeSettings[$param][$tempArr[0]] = $tempArr[1];
            }
        }
    }
    return $storeSettings;
}

switch ($this->values->action) {
    //работа с заказом интернет магазина
    case 'store':

        //секурность
        if (!$this->moduleAccess()) {
            return;
        }

        $this->getRequestValues(['type','id']);
        $id = (int)$this->values->id;

        switch ($this->values->type) {
            case 'setRequest':
                $requestBody = file_get_contents('php://input');
                if(!$requestBody = json_decode($requestBody)) {
                    die(json_encode(['success' => false, 'message' => 'Wrong JSON string']));
                }
                $requestBody->requestId = (int)$requestBody->requestId;
                $request = new StoreRequest($this->DB,$requestBody->requestId);
                if (!$request->getRecord($requestBody->requestId)){
                    die(json_encode(['success' => false, 'message' => 'Wrong Request ID']));
                }
                if (empty($requestBody->goods)) {
                    die(json_encode(['success' => false, 'message' => 'Wrong Goods']));
                }
                $query = 'DELETE FROM store_request_goods WHERE request_id = ' . $requestBody->requestId;
                if (!$this->DB->query($query)) {
                    die(json_encode(['success' => false, 'message' => 'Server Error']));
                }
                $allprice = 0;
                foreach ($requestBody->goods as $good) {
                    if (is_object($good)) {
                        $requestGood = new StoreRequestGood($this->DB);
                        $params = [
                            'request_id' => $requestBody->requestId,
                            'good_id' => $good->id,
                            'price' => $good->price,
                            'count' => $good->material_count
                        ];
                        $requestGood->createRecord($params);
                        $allprice += $good->price*$good->material_count;
                    }
                }
                $request->params['date_edit'] = 'NOW()';
                $request->params['payment_method'] = $requestBody->paymentMethod;
                $request->params['delivery'] = $requestBody->delivery;
                $request->params['price'] = $allprice;
                $request->updateRecord();

                $this->moduleData->res = ['success' => true, 'message' => 'Success saved'];
                break;
            case 'getRequest':
                $request = new StoreRequest($this->DB,$id);
                if(!$request->getRecord($id)){
                    die(json_encode(['success' => false, 'message' => 'Wrong Request ID']));
                }

                $goodsVsURL = [];
                foreach ($request->params['goods'] as $good) {
                    $url = Mat::GetUrl($good, $this->materialTypes);
                    $goodsVsURL[] = array_merge($good, ['url' => $url]);
                }
                $request->params['goods'] = $goodsVsURL;

                $matList = new MatList($this->DB);
                $storeSettings = $matList->getTypeActiveRecords('online-store-settings');
                $tempSettings = [];
                foreach ($storeSettings as $item) {
                    $tempSettings[str_replace('-','_',$item['id_char'])] = $item['anons'];
                }
                $storeSettings = $tempSettings;
                //$storeSettings = array_column($storeSettings, 'anons', 'id_char');
                $storeSettings = storeSettingsParamToArray($storeSettings, ['Sposoby_oplaty','Sposoby_dostavki']);


                $this->moduleData->res = ['request' => $request->params, 'storeSettings' => $storeSettings];
                break;
            case 'getNewGood':
                $mat = new Mat($this->DB);
                if(!$mat->getRecord($id)){
                    die(json_encode(['success' => false, 'message' => 'Wrong Good ID ' . $id]));
                }
                $mat->params['url'] = Mat::GetUrl($mat->params, $this->materialTypes);
                $this->moduleData->res = $mat;
                break;
        }

        break;

    //работа с корзиной
    case 'cart':

        $this->getRequestValues(['type','id']);

        $type = $this->values->type;
        $id = (int)$this->values->id;

        $types = ['add','edit','wishAdd','wishEdit'];

        if(!in_array($type,$types)){
            die(json_encode(['success' => false, 'message' => 'Wrong Type']));
        }

        $mat = new Mat($this->DB);
        if(!$mat->getRecord($id)){
            die(json_encode(['success' => false, 'message' => 'Wrong Good ID']));
        }

        switch ($type){
            //добавление товара в корзину
            case 'add':

                $this->getRequestValue('count');
                $this->values->count = (int)$this->values->count;
                if($this->values->count <= 0){
                    $this->values->count = 1;
                }

                if(!isset($_SESSION['cart'])){
                    $_SESSION['cart'] = [];
                }

                if(!isset($_SESSION['cart']['goods'][$id])){
                    $_SESSION['cart']['goods'][$id] = [
                        'id' => $id,
                        'count' => $this->values->count,
                        'name' => $mat->params['name'],
                        'price' => $mat->params['price'],
                        'cost' => $this->values->count * $mat->params['price']
                    ];
                }
                else {
                    $_SESSION['cart']['goods'][$id]['count'] += $this->values->count;
                    $_SESSION['cart']['goods'][$id]['cost'] = $_SESSION['cart']['goods'][$id]['count'] * $mat->params['price'];
                }

                $this->moduleData->res = makeCartAllCost();

                break;
            //изменение кол-ва товаров
            case 'edit':

                $this->getRequestValue('count');
                $this->values->count = (int)$this->values->count;
                if($this->values->count <= 0){
                    $this->values->count = 0;
                }

                if(!isset($_SESSION['cart'])){
                    $_SESSION['cart'] = [];
                }

                if(!isset($_SESSION['cart']['goods'][$id])){
                    $_SESSION['cart']['goods'][$id] = [
                        'id' => $id,
                        'count' => $this->values->count,
                        'name' => $mat->params['name'],
                        'price' => $mat->params['price'],
                        'cost' => $this->values->count * $mat->params['price']
                    ];
                }
                else {
                    $_SESSION['cart']['goods'][$id]['count'] = $this->values->count;
                    $_SESSION['cart']['goods'][$id]['price'] = $mat->params['price'];
                    $_SESSION['cart']['goods'][$id]['cost'] = $this->values->count * $mat->params['price'];
                }

                $this->moduleData->res = makeCartAllCost();

                break;
            //добавление товара в список wishlist
            case 'wishAdd':

                break;
        }

        break;

    //получаем список материалов по типу
    case 'getmats':

        $this->getRequestValues(['type']);

        $type = (int)$this->values->type;

        $query = "SELECT id, name FROM materials WHERE material_type_id = $type AND status_id = 1 ORDER BY name ASC";

        if (!$res = $this->DB->query($query)) {
            $out = false;
        } else {
            foreach ($res as $row) {
                $out[] = [
                    'id' => $row['id'],
                    'name' => $row['name']
                ];
            }
        }

        $this->moduleData->res = ['types' => $out];

        break;

    //получение товара интернет магазина
    case 'getstoreproduct':

        $this->getRequestValues(['query']);
        $escaped_query = $this->DB->mysqli->real_escape_string($this->values->query);

        $query = "SELECT m.id, m.name, m.goodcode FROM materials m 
            WHERE m.status_id = 1 AND m.material_type_id IN (SELECT t.id FROM material_types t WHERE t.shop_ifgood = 1)
            AND (LOWER(m.goodcode) LIKE LOWER('%" . $escaped_query . "%') || LOWER(m.name) LIKE LOWER('%" . $escaped_query . "%'))";

        if (!$res = $this->DB->query($query)) {
            $out = false;
        } else {
            foreach ($res as $row) {
                $out[] = [
                    'id' => $row['id'],
                    'name' => $row['name']
                ];
            }
        }

        $this->moduleData->res = ['types' => $out];

        break;

    //получаем тип материала по наименованию или буквенному идентификатору
    case 'getmattype':

        $this->getRequestValues(['query']);
        $escaped_query = $this->DB->mysqli->real_escape_string($this->values->query);

        $query = "SELECT * FROM material_types 
        WHERE LOWER(name) LIKE LOWER('%" . $escaped_query . "%')";

        if (!$res = $this->DB->query($query)) {
            $out = false;
        } else {
            foreach ($res as $row) {
                $out[] = [
                    'id' => $row['id'],
                    'name' => $row['name']
                ];
                //$out[]=array($row['id'] => $row['name']);
            }
        }

        $this->moduleData->res = ['types' => $out];

        break;
}


//всегда выводим false, если результата нет после обработки
if (!isset($this->moduleData->res)) {
    $this->moduleData->res = false;
}

//выводим результат без подключениыя шаблонов
die(json_encode($this->moduleData->res));