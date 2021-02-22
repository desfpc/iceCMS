<?php
/**
* Created by Sergey Peshalov https://github.com/desfpc
* PHP framework and CMS based on it.
* https://github.com/desfpc/iceCMS
* @var ice\iceRender $this
*/

use ice\iceWidget;

?>
<div class="row">
    <div class="col-sm-12">
        <?php
        //выводим ошибки
        include_once ($template_folder.'/partial/t_alert.php');

        ?>
        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th style="width: 60px;">ID</th>
                <th>E-mail</th>
                <th>Имя</th>
                <th>Роль</th>
                <th style="width: 60px;">Дата регистрации</th>
                <th style="width: 60px;">Действия</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <?php
        $pages = new iceWidget($this->DB, 'pages', $this->settings);
        $pages->show([
            'count' => $this->moduleData->usersCnt,
            'perpage' => $this->moduleData->perpage,
            'page' => $this->moduleData->page,
            'url' => $_SERVER['REQUEST_URI']
        ]);
        ?>
    </div>
</div>