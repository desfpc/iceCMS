<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var \iceRender $this
 */
include_once('t_header_short.php');
?>
<div class="container-fluid header-name">
    <div class="container">
        <div class="row">
            <div class="col-md">
                <h1><?= $this->moduleData->H1 ?></h1>
            </div>
        </div>
    </div>
</div>
<div class="container sitebody">
    <?php

    $breadcrumbs = new iceWidget($this->DB, 'breadcrumbs', $this->settings);
    $breadcrumbs->show(['types' => $this->parser->mtypes, 'material' => $this->parser->material]);

    ?>
    <div class="row">
        <div class="col">
            <?php

            //выводим ошибки
            include_once ($template_folder.'/partial/t_alert.php');

            ?>
        </div>
    </div>