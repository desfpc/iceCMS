<?php
$mType = $this->moduleData->mType;

if(isset($mType->extraParams) && is_array($mType->extraParams) && count($mType->extraParams) > 0){

    $selecterIDs = [];

    ?><br><hr /><h2>Дополнительные поля:</h2><?php

    foreach($mType->extraParams as $extraParam){

        $input='';

        $divclass='form-group row';
        $aExtraValue = false;
        $value = '';

        if(isset($this->moduleData->material->extraValues) && $this->moduleData->material->extraValues && is_array($this->moduleData->material->extraValues)){
            foreach ($this->moduleData->material->extraValues as $extraValue){
                if($extraValue['param_id'] == $extraParam['id']){
                    $aExtraValue = $extraValue;
                }
            }
        }

        switch ($extraParam['value_type']){
            case 'value_mat':

                $options='';
                $selecterIDs[] = ['id' => 'extraParam_'.$extraParam['id'], 'type' => $extraParam['value_mtype']];

                if($aExtraValue){
                    $options.='<option value="'.$aExtraValue['value_mat'].'">'.$aExtraValue['value_mat_name'].'</option>';
                }

                $input = '<select class="form-control selectpicker" data-live-search="true" id="extraParam_'.$extraParam['id'].'" name="extraParam_'.$extraParam['id'].'" aria-describedby="extraParam_'.$extraParam['id'].'Help" placeholder="'.$extraParam['name'].'">
                            '.$options.'
                          </select>';
                break;
            case 'value_text':

                if($aExtraValue){
                    $value = $aExtraValue['value_text'];
                }

                $input = '<textarea class="form-control tinymce" id="extraParam_'.$extraParam['id'].'" name="extraParam_'.$extraParam['id'].'">'.$value.'</textarea>';
                break;
            case 'value_flag':

                /*<div class="custom-control switch form-group col-sm-3">
    <input type="checkbox" class="danger" id="important" value="1" <?=$impChecked?>>
    <span class="slider round"></span>
    <label class="custom-control-label" for="important">Важный материал</label>
</div>*/
                if(!is_null($aExtraValue['value_flag']) && $aExtraValue['value_flag']){
                    $checked = 'checked';
                }
                else {
                    $checked = '';
                }

                $divclass = 'custom-control switch form-group col-sm-3';

                $input = '<input type="checkbox" class="danger" id="extraParam_'.$extraParam['id'].'" value="1" '.$checked.'>
                            <span class="slider round"></span>';
                break;
            default:

                if($aExtraValue){
                    $value = $aExtraValue[$extraParam['value_type']];
                }

                $input = '<input type="text" class="form-control" id="extraParam_'.$extraParam['id'].'" name="extraParam_'.$extraParam['id'].'" aria-describedby="extraParam_'.$extraParam['id'].'Help" placeholder="'.$extraParam['name'].'" value="'.$value.'">';
                break;
        }

        ?>
        <div class="<?=$divclass?>">
            <label for="extraParam_<?=$extraParam['id']?>" class="col-sm-2 col-form-label text-right"><?=$extraParam['name']?></label>
            <div class="col-sm-10">
                <?=$input?>
            </div>
        </div>
        <?php

    }

}
