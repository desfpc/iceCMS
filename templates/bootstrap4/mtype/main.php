<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 */

//tests
$main = new \ice\iceMessage($this->settings);
$mainsend = false;/* Проверка работы почтового клиента
    $main->send('desfpc@gmail.com',
    'Проверка работы нового модуля с почтой',
    '<b>Проверочное письмо</b><br>Проверяем новый модуль отправки писем',
    ['E:\work\Ampps\www\iceCMS\img\ice\logo.png',
        'E:\work\Ampps\www\iceCMS\resourses\logos\logofw.png']);*/

?><div class="row">
    <div class="col-sm-12">
        Hello World! <?php if($mainsend){echo 'Mail sended';} ?>
    </div>
</div>