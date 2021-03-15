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

use ice\Web\HeaderBuilder;
use ice\Models\Mat;

$this->headers = new HeaderBuilder();
$this->headers->standartHeaders();
$this->headers->addHeader('Content-Type: application/json');

$this->moduleData = new stdClass();

$this->getRequestValue('action');

switch ($this->values->action) {

    //работа с корзиной
    case 'cart':

        $this->getRequestValues(['type','id']);

        $type = (int)$this->values->type;
        $id = (int)$this->values->id;

        $types = ['add','delete','wishAdd','wishDelete'];

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

                $allCost = 0;
                foreach ($_SESSION['cart']['goods'] as $good){
                    $allCost += $good['cost'];
                }


                break;
            //удаление товара из корзины
            case 'delete':

                break;
            //добавление товара в список wishlist
            case 'wishAdd':

                break;
            //удаление товара из списка wishlist
            case 'wishDelete':

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