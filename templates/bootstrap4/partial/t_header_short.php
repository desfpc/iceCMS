<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 */

use ice\Web\Widget;

?><!doctype html>
<html lang="en">
<head>
    <base href="/">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="ice framework">
    <meta name="author" content="RedneckCode">
    <meta name="keyword" content="dashboard, message, task, manager, coloboration">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <title><?= $this->moduleData->title ?></title>
</head>
<body>
<?php

if ($this->authorize->secure == 1) {
    include_once($template_folder . '/partial/t_securepanel.php');
    $mainmenuclass = 'secureclass';
} else {
    $mainmenuclass = '';
}

?>
<div class="container-fluid header-topbar">
    <div class="row">
        <div class="col-sm">
            <a href="mailto:support@ice4service.com"><i
                    class="material-icons md-16 md-dark">mail_outline</i> <?= $this->settings->email->mail ?></a>
            &nbsp;&nbsp;<a href="tel:+79898283459"><i class="material-icons md-16 md-dark">local_phone</i> +7 (000)
                000-00-00</a>
        </div>
    </div>
</div>
<div class="container-fluid main-menu <?= $mainmenuclass ?>">
    <div class="row">
        <div class="col-md-auto logo col-auto">
            <a href="/"><img src="/resourses/logos/logofw.svg" width="56"
                             height="65"><span><?= $this->settings->site->title ?></span></a>
        </div>
        <?php

        $navigation = new Widget($this->DB, 'navigationMenu', $this->settings);
        $navigation->show($this->materialTypes);

        ?>
        <div class="col-lg-2" style="margin-bottom: 18px; text-align: right;">
            <div class="normalblock border rounded-circle header-round">
                <i class="material-icons md-24 md-dark">search</i>
            </div>
            <div class="normalblock border rounded-circle header-round person-round passive">
                <i class="material-icons md-24 md-dark">person</i>
                <div class="person-info">
                    <i class="close material-icons md-24 md-grey">close</i>
                    <?php
                    //выводим инфо пользователя, ссылки на личный кабинет и выход
                    if ($this->authorize->autorized) {
                        ?>
                        <div class="user-name-block">
                            <div class="border rounded-circle photo">
                                <i class="material-icons md-24 md-dark">person</i>
                            </div>
                            <div class="name">
                                <?= $this->authorize->user->params['full_name'] ?>
                            </div>
                        </div>
                        <div class="links">
                            <p><a href="/account-profile">Личный кабинет</a></p>
                            <p><a href="/orderlist">Список заказов</a></p>
                            <p><a href="/wishlist">Список желаний</a></p>
                            <hr>
                            <p><a href="/exit">Выход</a></p>
                        </div>
                        <?php
                    } //выводим форму авторизации и ссылки на регистрацию
                    else {
                        ?>
                        <form id="smallRegForm" action="/authorize" method="post">
                            <input type="email" class="form-control" id="auEmail" name="auEmail"
                                   aria-describedby="auEmailHelp" placeholder="Введите email" required="" value="">
                            <input type="password" class="form-control" id="auPass" name="auPass"
                                   placeholder="введите Пароль" required="">
                            <input type="hidden" name="action" value="login">
                            <button type="submit" class="btn btn-primary">Авторизироваться</button>
                        </form>
                        <?
                    }
                    ?>
                </div>
            </div><?php

            $allCost = 0; //полная стоимость заказа
            $allCnt = 0; //полное кол-во товаров

            if(isset($_SESSION['cart'])) {
                if(isset($_SESSION['cart']['allCost'])) {
                    $allCost = $_SESSION['cart']['allFormatedCost'];
                }
                if(isset($_SESSION['cart']['allCnt'])) {
                    $allCnt = $_SESSION['cart']['allCnt'];
                }
            }

            ?>
            <div class="normalblock border rounded header-cart">
                <i class="material-icons md-24 md-dark">shopping_basket</i><span class="header-cart__cnt">&nbsp;&nbsp;<?=$allCnt?>&nbsp;&nbsp;</span><span class="hline"></span><span class="header-cart__cost">&nbsp;&nbsp;<strong><?=$allCost?></strong></span>
                <div class="header-cart__details">
                    <ul class="header-cart__goods">
                        <?php

                        if(isset($_SESSION['cart']['goods']) && is_array($_SESSION['cart']['goods']) && count($_SESSION['cart']['goods']) > 0) {
                            foreach ($_SESSION['cart']['goods'] as $good) {
                                if($good['count'] > 0) {
                                    echo '<li>';
                                    echo $good['name'].' ('.$good['count'].'шт)';
                                    echo '</li>';
                                }
                            }
                        }

                        ?>
                    </ul>
                    <hr>
                    <a href="/cart">Перейти в корзину</a>
                </div>
            </div>
        </div>
    </div>
</div>