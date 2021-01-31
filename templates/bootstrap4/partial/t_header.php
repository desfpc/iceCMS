<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\iceRender $this
 */

use ice\iceWidget;

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
<div class="container">
    <div class="row">
        <div class="col-md" style="padding-top: 10px;">
            <?php

            if(!isset($this->moduleData->breadcrumbs)){
                $this->moduleData->breadcrumbs = [];
            }

            $breadcrumbs = new iceWidget($this->DB, 'breadcrumbsAdmin', $this->settings);
            $breadcrumbs->show($this->moduleData->breadcrumbs);

            ?>
        </div>
    </div>
</div>