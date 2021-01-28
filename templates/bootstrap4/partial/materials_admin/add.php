<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var \iceRender $this
 */

//выводим ошибки
include_once ($template_folder.'/partial/t_alert.php');

?>
<form id="matTypeAddForm" action="/?menu=materials_admin&mode=add" method="post">
    <div class="form-group row">
        <label for="name" class="col-sm-2 col-form-label">Наименование</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="name" name="name" aria-describedby="nameHelp" placeholder="Наименование материала" required value="<?= $this->values->name; ?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="id_char" class="col-sm-2 col-form-label">Идентификатор (En)</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="id_char" name="id_char" aria-describedby="id_charHelp" placeholder="Буквенный идентификатор (En)" value="<?= $this->values->id_char; ?>">
        </div>
    </div>
    <div class="form-group row">
        <label for="parent_id" class="col-sm-2 col-form-label">Родительский тип</label>
        <div class="col-sm-10">
            <select class="form-control selectpicker" data-live-search="true" id="material_type_id" name="material_type_id" aria-describedby="parent_idHelp" placeholder="Родительский тип материала">
                <option value="<?=$this->moduleData->selectedMatType['id']?>" selected><?=$this->moduleData->selectedMatType['name']?></option>
            </select>
        </div>
    </div>
    <br /><hr /><br />
    <button type="submit" class="btn btn-success"><i class="material-icons md-24 md-light">add_box</i> Создать</button>
</form>
<?php
$this->jsready.="

    $('#material_type_id')
        .selectpicker({
            liveSearch: true
        })
        .ajaxSelectPicker({
        ajax: {
            url: '/?menu=ajax&action=getmattype',
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