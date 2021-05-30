<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 */

use ice\Helpers\Strings;
use ice\Messages\Message;
use ice\Models\MatList;
use ice\Models\MatType;
use ice\Models\File;
use ice\Models\StoreRequest;
use ice\Models\StoreRequestGood;
use ice\Models\User;
use ice\Tools\CSRF;

$allCost = 0;
$allCnt = 0;
$goodsOut = '';

//получение переменных с формы
$this->getRequestValues(['email','telegram','name','comment','_csrf']);

//сохранение заказа
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $allCost = (float)$_SESSION['cart']['allCost'];
    if (isset($_SESSION['cart'], $_SESSION['cart']['allCost'], $_SESSION['cart']['goods'])
        && $allCost > 0 && is_array($_SESSION['cart']['goods']) && count($_SESSION['cart']['goods']) > 0) {

        //проверка CSRF
        if (empty($this->values->_csrf) || !CSRF::checkCSFR('store_request', $this->values->_csrf)) {
            $this->setFlash('error', ['Ошибка данных формы. Попробуйте отправить еще раз.']);
            return;
        }

        //регистрация/авторизация пользователя (если нужно)
        if($this->authorize->autorized) {
            $userId = $this->authorize->user->id;
        } else {
            if($this->values->email != '' && Strings::checkEmail($this->values->email)) {
                //генерируем, распихиваем переданные параметры в свойство params, заносим пользюка
                $userPass = Strings::randomPassword(8);
                $params = [
                    'id' => null,
                    'login_email' => $this->values->email,
                    'login_phone' => $this->values->telegram,
                    'nik_name' => $this->values->name,
                    'full_name' => $this->values->name,
                    'passcode' => null,
                    'status_id' => 1,
                    'password_input' => $userPass,
                    'password' => null,
                    'date_add' => null,
                    'contacts' => null,
                    'user_state' => null,
                    'user_role' => 1,
                    'sex' => null
                ];

                $user = new user($this->DB);
                if ($user->registerUser($params)) {
                    //TODO отсылка сообщения с паролем пользователя
                    $message = new Message($this->settings, ['email']);

                    $userId = $user->id;
                } else {
                    $this->setFlash('error', ['Ошибка регистрации пользователя']);
                    return;
                }
            } else {
                $this->setFlash('error', ['Не введен или не валидный E-mail пользователя']);
                return;
            }
        }

        //запись заказа
        $params = [
            'id' => null,
            'user_id' => $userId,
            'date_add' => null,
            'date_edit' => null,
            'status' => 'created',
            'payment_method' => 'on_delivery',
            'price' => $allCost,
            'comment' => $this->values->comment
        ];
        $request = new StoreRequest($this->DB);
        if(!$request->createRecord($params)) {
            $this->setFlash('error', ['Ошибка при создании заказа']);
            return;
        }

        //запись детализации заказа
        $cartGoods = '';
        foreach ($_SESSION['cart']['goods'] as $good) {
            if ($cartGoods != '') {
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
            $params = [
                'request_id' => $request->id,
                'good_id' => $material->id,
                'price' => $_SESSION['cart']['goods'][$material['id']]['cost'],
                'count' => $_SESSION['cart']['goods'][$material['id']]['count']
            ];
            $good = new StoreRequestGood();
            $good->createRecord($params);
        }

        //TODO отсылка уведомлений

        //TODO очистка формы, сессии и кук от данных заказа

        //TODO вывод рпезультата
    }
}

if(isset($_SESSION['cart'])){
    $allCost = $_SESSION['cart']['allFormatedCost'];
    $allCnt = $_SESSION['cart']['allCnt'];
    $cartGoods = '';

    if(isset($_SESSION['cart']['goods']) && is_array($_SESSION['cart']['goods']) && count($_SESSION['cart']['goods']) > 0) {

        //visualijoper::visualijop($_SESSION['cart']['goods']);

        foreach ($_SESSION['cart']['goods'] as $good) {
            if ($cartGoods != '') {
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
            $goodsOut.='<td>' . $_SESSION['cart']['goods'][$material['id']]['formatedPrice'] . '</td>';
            $goodsOut.='<td><input type="text" data="' . $material['id'] . '" class="form-control cart-good-cnt" value="' . $_SESSION['cart']['goods'][$material['id']]['count'] . '"></td>';
            $goodsOut.='<td><b class="cart_cost_' . $material['id'] . '">' . $_SESSION['cart']['goods'][$material['id']]['formatedCost'] . '</b></td>';

            $goodsOut.= '</tr>';
        }
    }

    ?><div class="row">
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
                <th class="cart_allCnt"><?=$allCnt?></th>
                <th class="cart_allCost"><?=$allCost?></th>
            </tr>
        </table>
        <?php
        $emailForm = true;
        $telegramForm = true;
        $nameForm = true;
        //определение полей для авторизации пользователя
        if($this->authorize->autorized) {
            $emailForm = false;
            if($this->authorize->user->params['full_name'] != ''){
                $nameForm = false;
            }
            $contacts = $this->authorize->user->params['contacts'];
            if($contacts != '') {
                if(($contacts = json_decode($contacts, true)) && !empty($contacts['telegram']) && $contacts['telegram'] != '') {
                    $telegramForm = false;
                }
            }
        }

        $csfr = new CSRF($this->settings,'store_request');

        /*$emailForm = true;
        $nameForm = true;
        $telegramForm = true;*/

        ?>
        <h2>Оформление заказа:</h2>
        <p>&nbsp;</p>
        <form method="post" action="/cart">
            <?php $csfr->printInput(); ?>
<?= $emailForm ? '
            <div class="row"><div class="col-md-12 form-group">
                <label for="email">Email адрес</label>
                <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="your@email.com" required value="'.$this->values->email.'">
                <small id="emailHelp" class="form-text text-muted">Является login-ом на сайте. Другие пользователи его не увидят. Обязательно.</small>
            </div></div>' : '' ?>
<?= $telegramForm ? '
            <div class="row"><div class="col-md-12 form-group">
                <label for="telegram">Telegram логин или номер телефона</label>
                <input type="text" class="form-control" id="telegram" name="telegram" aria-describedby="telegramHelp" placeholder="79991122333" value="'.$this->values->telegram.'">
                <small id="telegramHelp" class="form-text text-muted">Вводится для оповещения о статусе заказа через Telegram. Не обязатьельно.</small>
            </div></div>' : '' ?>
<?= $nameForm ? '
            <div class="row"><div class="col-md-12 form-group">
                <label for="name">Ваше имя</label>
                <input type="text" class="form-control" id="name" name="name" aria-describedby="nameHelp" placeholder="Дарт Вейдер" value="'.$this->values->name.'">
                <small id="telegramHelp" class="form-text text-muted">Как к Вам обращаться? Не обязатьельно.</small>
            </div></div>' : '' ?>
            <div class="row"><div class="col-md-12 form-group">
                <label for="comment">Комментарий к заказу</label>
                <textarea class="form-control" id="comment" name="comment" aria-describedby="commentHelp"><?=$this->values->comment?></textarea>
                <small id="commentHelp" class="form-text text-muted">Комментарий к заказу. Не обязатьельно.</small>
            </div></div>
            <div class="row"><div class="col-md-12 form-group">
                <button type="submit" class="btn btn-success"><i class="material-icons md-24 md-light">shopping_basket</i> Оформить заказ</button>
            </div></div>
        </form>
    </div>
</div><?php
} else {
    ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="alert alert-secondary" role="alert">
                В корзине нет товаров
            </div>
        </div>
    </div>
    <?php
}