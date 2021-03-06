<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 * @var string $template_folder
 */

use ice\Helpers\Strings;

?>
<div class="form-group row">
    <div class="col-sm-9">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">Дата начала и окончания события</span>
            </div>
            <input type="text" class="form-control datetimepicker" data-toggle="datetimepicker" id="date_event"
                   data-target="#date_event" name="date_event" aria-describedby="date_eventHelp"
                   placeholder="Дата события"
                   value="<?= Strings::formatDate($this->moduleData->material->params['date_event']) ?>">
            <input type="text" class="form-control datetimepicker" data-toggle="datetimepicker" id="date_end"
                   data-target="#date_end" name="date_end" aria-describedby="date_endHelp" placeholder="Дата окончания"
                   value="<?= Strings::formatDate($this->moduleData->material->params['date_end']) ?>">
        </div>
    </div><?php

    if ($this->moduleData->material->params['important'] == 1) {
        $impChecked = 'checked';
    } else {
        $impChecked = '';
    }

    include_once($template_folder . '/admin/_important.php');

    ?>
</div>