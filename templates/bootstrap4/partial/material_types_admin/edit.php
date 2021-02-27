<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 */
?><form id="matTypeAddForm" action="/admin/material_types_admin/?mode=edit&id=<?= $this->moduleData->matType->params['id'] ?>" method="post"><input type="hidden" name="id" value="<?= $this->moduleData->matType->params['id'] ?>">
    <div class="form-group row">
        <label for="name" class="col-sm-2 col-form-label">Наименование</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="name" name="name" aria-describedby="nameHelp" placeholder="Наименование типа материала" required value="<?= $this->moduleData->matType->params['name']; ?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="name_en" class="col-sm-2 col-form-label">Наименование (En)</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="name_en" name="name_en" aria-describedby="name_enHelp" placeholder="Наименование (En)" value="<?= $this->moduleData->matType->params['name_en']; ?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="name_de" class="col-sm-2 col-form-label">Наименование (De)</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="name_de" name="name_de" aria-describedby="name_deHelp" placeholder="Наименование (De)" value="<?= $this->moduleData->matType->params['name_de']; ?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="id_char" class="col-sm-2 col-form-label">Идентификатор (En)</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="id_char" name="id_char" aria-describedby="id_charHelp" placeholder="Буквенный идентификатор (En)" value="<?= $this->moduleData->matType->params['id_char']; ?>">
        </div>
    </div>
    <br /><hr /><br />
    <div class="form-group row">
        <label for="parent_id" class="col-sm-2 col-form-label">Родительский тип</label>
        <div class="col-sm-10">
            <select class="form-control selectpicker" data-live-search="true" id="parent_id" name="parent_id" aria-describedby="parent_idHelp" placeholder="Родительский тип материала">
                <option value="<?= $this->moduleData->matType->params['parent_id']; ?>" selected><?= $this->moduleData->parentName; ?></option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="ordernum" class="col-sm-2 col-form-label">Вес (сортировка)</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="ordernum" name="ordernum" aria-describedby="ordernumHelp" placeholder="Порядок сортировки" value="<?= $this->moduleData->matType->params['ordernum']; ?>">
        </div>
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="sitemenu" name="sitemenu" value="1" <?php if($this->moduleData->matType->params['sitemenu'] == 1){echo 'checked';} ?>>
        <label for="sitemenu" class="form-check-label">Отображать в меню</label>
    </div>
    <br /><hr /><br />
    <div class="form-group row">
        <label for="template_list" class="col-sm-2 col-form-label">Шаблон списка</label>
        <div class="col-sm-10">
            <select class="form-control selectpicker" data-live-search="true" id="template_list" name="template_list" aria-describedby="parent_idHelp" placeholder="Шаблон списка материалов">
                <option value="NULL">нет</option>
                <?php

                if(is_array($this->moduleData->listTemplates) && count($this->moduleData->listTemplates) > 0){
                    foreach ($this->moduleData->listTemplates as $template){

                        if($this->moduleData->matType->params['template_list'] == $template['id']){
                            $selected = 'selected';
                        }
                        else {
                            $selected = '';
                        }

                        echo '<option value="'.$template['id'].'" '.$selected.'>'.$template['name'].'</option>';
                    }
                }

                ?>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="template_item" class="col-sm-2 col-form-label">Шаблон материала</label>
        <div class="col-sm-10">
            <select class="form-control selectpicker" data-live-search="true" id="template_item" name="template_item" aria-describedby="parent_idHelp" placeholder="Шаблон детализации материалов">
                <option value="NULL">нет</option>
                <?php

                if(is_array($this->moduleData->detailTemplates) && count($this->moduleData->detailTemplates) > 0){
                    foreach ($this->moduleData->detailTemplates as $template){

                        if($this->moduleData->matType->params['template_item'] == $template['id']){
                            $selected = 'selected';
                        }
                        else {
                            $selected = '';
                        }

                        echo '<option value="'.$template['id'].'" '.$selected.'>'.$template['name'].'</option>';
                    }
                }

                ?>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label for="template_admin" class="col-sm-2 col-form-label">Шаблон редактора</label>
        <div class="col-sm-10">
            <select class="form-control selectpicker" data-live-search="true" id="template_admin" name="template_admin" aria-describedby="parent_idHelp" placeholder="Шаблон редактирования материалов">
                <option value="NULL">нет</option>
                <?php

                if(is_array($this->moduleData->adminTemplates) && count($this->moduleData->adminTemplates) > 0){
                    foreach ($this->moduleData->adminTemplates as $template){

                        if($this->moduleData->matType->params['template_admin'] == $template['id']){
                            $selected = 'selected';
                        }
                        else {
                            $selected = '';
                        }

                        echo '<option value="'.$template['id'].'" '.$selected.'>'.$template['name'].'</option>';
                    }
                }

                ?>
            </select>
        </div>
    </div>
    <br /><hr /><br />
    <div class="form-group row">
        <label for="list_items" class="col-sm-2 col-form-label">Кол-во страницы</label>
        <div class="col-sm-10"><?php

            if($this->moduleData->matType->params['list_items'] === ''){
                $this->moduleData->matType->params['list_items'] = 10;
            }

            ?>
            <input type="text" class="form-control" id="list_items" name="list_items" aria-describedby="ordernumHelp" placeholder="Кол-во материалов на одной странице списка" value="<?=$this->moduleData->matType->params['list_items']?>">
        </div>
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="prepare_list" name="prepare_list" value="1" <?php if($this->moduleData->matType->params['prepare_list'] == 1){echo 'checked';} ?>>
        <label for="prepare_list" class="form-check-label">Формирование списка материалов (нет логики в шаблоне)</label>
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="prepare_item" name="prepare_item" value="1" <?php if($this->moduleData->matType->params['prepare_item'] == 1){echo 'checked';} ?>>
        <label for="prepare_item" class="form-check-label">Формирование детализации материала (нет логики в шаблоне)</label>
    </div>
    <br /><hr /><br />
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="shop_ifgood" name="shop_ifgood" value="1" <?php if($this->moduleData->matType->params['shop_ifgood'] == 1){echo 'checked';} ?>>
        <label for="shop_ifgood" class="form-check-label">Тип является каталогом товаров</label>
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="shop_ifstore" name="shop_ifstore" value="1" <?php if($this->moduleData->matType->params['shop_ifstore'] == 1){echo 'checked';} ?>>
        <label for="shop_ifstore" class="form-check-label">Тип является складом магазина</label>
    </div>
    <br /><hr /><br />
    <button type="submit" class="btn btn-success"><i class="material-icons md-24 md-light">edit</i> Изменить</button>
    <br /><hr /><br />
    <div class="row">
        <div class="col-md-12"><h2>Дополнительные поля</h2></div>
    </div>
</form>

    <div class="row">
        <div class="col">
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th style="width: 60px;">Id</th>
                    <th>Название</th>
                    <th>Тип значения</th>
                    <th>Тип материала<br/>(для значений)</th>
                    <th style="width: 60px;">Действия</th>
                </tr>
                </thead>
                <tbody>
                <?php

                if(isset($this->moduleData->matType->extraParams) && is_array($this->moduleData->matType->extraParams) && count($this->moduleData->matType->extraParams) > 0){
                    foreach ($this->moduleData->matType->extraParams as $extraParam){
                        echo '<tr>
                            <td>'.$extraParam['id'].'</td>
                            <td>'.$extraParam['name'].'</td>
                            <td>'.$extraParam['value_type'].'</td>
                            <td>'.$extraParam['value_mtype_name'].'</td>
                            <td></td>
                        </tr>';
                    }
                }

                ?>
                </tbody>
            </table>
            <h3>Создать новое поле:</h3>
            <form method="post" action="/admin/material_types_admin/?mode=addExtraParam&mtype_id=<?= $this->moduleData->matType->params['id'] ?>">
                <input type="hidden" name="mtype_id" value="<?= $this->moduleData->matType->params['id'] ?>">
                <input type="hidden" name="mode" value="addExtraParam">
                <div class="form-group row">
                    <label for="ep_name" class="col-sm-2 col-form-label">Наименование</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="ep_name" name="name" aria-describedby="nameHelp" placeholder="Наименование дополнительного поля" required value="">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="ep_value_type" class="col-sm-2 col-form-label">Тип значения</label>
                    <div class="col-sm-10">
                        <select class="form-control selectpicker" data-live-search="false" id="ep_value_type" name="value_type" aria-describedby="ep_value_typeHelp" placeholder="Тип значения параметра">
                            <option value="value_char">Строка</option>
                            <option value="value_int">Целое число</option>
                            <option value="value_mat">Материал</option>
                            <option value="value_text">Текстовое поле</option>
                            <option value="value_flag">Флаг</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="ep_value_mtype" class="col-sm-2 col-form-label">Тип материала</label>
                    <div class="col-sm-10">
                        <select class="form-control selectpicker" data-live-search="true" id="ep_value_mtype" name="value_mtype" aria-describedby="ep_value_mtypeHelp" placeholder="Тип материала для значиней">
                            <option value="NULL">нет</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-success"><i class="material-icons md-24 md-light">add_box</i> Создать</button>
            </form>
        </div>
    </div>

<?php
$this->jsready.="

    $('#parent_id, #ep_value_mtype')
        .selectpicker({
            liveSearch: true
        })
        .ajaxSelectPicker({
        ajax: {
            url: '/ajax/?action=getmattype',
            data: function () {
                var params = {
                    query: '{{{q}}}'
                };
               
                return params;
            }
        },
        locale: {
            emptyTitle: 'Поиск типа материала...'
        },
        preprocessData: function(data){
        
        console.log(data);
        
            var types = [];
            if(data.hasOwnProperty('types')){
                var len = data.types.length;
                for(var i = 0; i < len; i++){
                    var curr = data.types[i];
                    types.push(
                        {
                            'value': curr.id,
                            'text': curr.name,
                            'disabled': false
                        }
                    );
                }
            }
            return types;
        },
        preserveSelected: false
    });

";

$this->jscripts->addScript('/js/bootstrap-select.js');
$this->jscripts->addScript('/js/defaults-ru_RU.js');
$this->jscripts->addScript('/js/ajax-bootstrap-select.js');
$this->jscripts->addScript('/js//ajax-bootstrap-select.ru-RU.min.js');

$this->styles->addStyle('/css/bootstrap-select.css');
$this->styles->addStyle('/css/ajax-bootstrap-select.css');