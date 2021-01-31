<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\iceRender $this
 */

//достаём ошибки из flash переменных
$flashSuccess = $this->getFlash('success');
$flashErrors = $this->getFlash('errors');

if(!is_null($flashSuccess) && is_array($flashSuccess) && count($flashSuccess) > 0){
    $this->moduleData->success = array_merge($this->moduleData->success, $flashSuccess);
}
if(!is_null($flashErrors) && is_array($flashErrors) && count($flashErrors) > 0){
    $this->moduleData->errors = array_merge($this->moduleData->errors, $flashErrors);
}

if(is_array($this->moduleData->errors) && count($this->moduleData->errors) > 0)
{
    ?>
    <div class="alert alert-danger" role="alert">
        <h4 class="alert-heading"><i class="material-icons md-24">error</i>&nbsp;Ошибка!</h4>
        <hr>
        <?php

        foreach ($this->moduleData->errors as $error) {
            echo '<p>'.$error.'</p>';
        }

        ?>
    </div>
    <?php
}
if(is_array($this->moduleData->success) && count($this->moduleData->success) > 0)
{
    ?>
    <div class="alert alert-success" role="alert">
        <h4 class="alert-heading"><i class="material-icons md-24">done</i>&nbsp;Успешно!</h4>
        <hr>
        <?php

        foreach ($this->moduleData->success as $error) {
            echo '<p>'.$error.'</p>';
        }

        ?>
    </div>
    <?php
}