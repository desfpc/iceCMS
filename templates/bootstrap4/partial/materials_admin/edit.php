<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 */

use ice\Models\Mat;

$tmImageList = '[]';

?>
    <div class="row">
    <div class="col-sm">
        <?php
        //выводим ошибки
        include_once($template_folder . '/partial/t_alert.php');

        ?>
    </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <small>Автор: <strong><?= $this->moduleData->material->params['user_name'] ?></strong> &nbsp;|&nbsp;
                Создано: <strong><?= Mat::formatDate($this->moduleData->material->params['date_add']) ?></strong> &nbsp;|&nbsp;
                Статус:
                <strong><?= Mat::statusIcon($this->moduleData->material->params['status_id']) ?> <?= Mat::statusName($this->moduleData->material->params['status_id']) ?></strong>
                &nbsp;|&nbsp;
                Тип материала: <strong><a
                        href="/admin/material_types_admin/?mode=edit&id=<?= $this->moduleData->material->params['material_type_id'] ?>"><?= $this->moduleData->material->params['material_type_name'] ?></a></strong>
                &nbsp;|&nbsp;
                <a href="/admin/materials_admin/?mode=edit&id=<?= $this->moduleData->material->params['id'] ?>&action=clearcache">Очистить
                    кэш</a> &nbsp;|&nbsp;
                TODO - Посмотреть на сайте
            </small>
            <br/>
            <hr/>
            <br/>
        </div>
    </div>
    <form id="matEditForm"
          action="/admin/materials_admin/?mode=edit&id=<?= $this->moduleData->material->params['id'] ?>" method="post">
        <input type="hidden" name="id" value="<?= $this->moduleData->material->params['id'] ?>"><input type="hidden"
                                                                                                       name="mode"
                                                                                                       value="edit">
        <input type="hidden" name="action" value="edit">
        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label text-right"><strong>Наименование</strong></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="name" name="name" aria-describedby="nameHelp"
                       placeholder="Наименование материала" required
                       value="<?= $this->moduleData->material->params['name']; ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="id_char" class="col-sm-2 col-form-label text-right">Идентификатор (En)</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="id_char" name="id_char" aria-describedby="id_charHelp"
                       placeholder="Буквенный идентификатор (En)"
                       value="<?= $this->moduleData->material->params['id_char']; ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="parent_id" class="col-sm-2 col-form-label text-right">Тип материала</label>
            <div class="col-sm-10">
                <select class="form-control selectpicker" data-live-search="true" id="material_type_id"
                        name="material_type_id" aria-describedby="parent_idHelp"
                        placeholder="Родительский тип материала">
                    <option value="<?= $this->moduleData->material->params['material_type_id'] ?>"
                            selected><?= $this->moduleData->material->params['material_type_name'] ?></option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="parent_id" class="col-sm-2 col-form-label text-right">Язык</label>
            <div class="col-sm-4">
                <select class="form-control selectpicker" data-live-search="false" name="language"
                        aria-describedby="languageHelp" placeholder="Язык">
                    <?php

                    if (count($this->moduleData->languages) > 0) {
                        foreach ($this->moduleData->languages as $language) {

                            if ($language['id'] == $this->moduleData->material->params['language']) {
                                $selected = 'selected';
                            } else {
                                $selected = '';
                            }

                            echo '<option value="' . $language['id'] . '" ' . $selected . '>' . $language['name'] . '</option>';

                        }
                    }

                    ?>
                </select>
            </div>
            <label for="status_id" class="col-sm-2 col-form-label text-right">Статус</label>
            <div class="col-sm-4">
                <select class="form-control selectpicker" data-live-search="false" name="status_id"
                        aria-describedby="status_idHelp" placeholder="Статус">
                    <?php

                    if (count($this->moduleData->statuses) > 0) {
                        foreach ($this->moduleData->statuses as $status) {

                            if ($status['id'] == $this->moduleData->material->params['status_id']) {
                                $selected = 'selected';
                            } else {
                                $selected = '';
                            }

                            echo '<option value="' . $status['id'] . '" ' . $selected . '>' . $status['name'] . '</option>';

                        }
                    }

                    ?>
                </select>
            </div>
        </div>
        <?php

        //подключение шаблона формы редактирования
        include_once($template_folder . '/admin/' . $this->moduleData->material->params['templates']['template_admin']['filename'] . '.php');

        ?>
        <br/>
        <button type="submit" class="btn btn-success"><i class="material-icons md-24 md-light">edit</i> Изменить
        </button>
    </form>
<?php

//подключение шаблона формы редактирования (часть вне основной формы)
include_once($template_folder . '/admin/' . $this->moduleData->material->params['templates']['template_admin']['filename'] . '__bottom.php');

if (isset($selecterIDs) && is_array($selecterIDs) && count($selecterIDs) > 0) {
    foreach ($selecterIDs as $selecterID) {
        $this->jsready .= "
        
        $('#" . $selecterID['id'] . "')
        .selectpicker({
            liveSearch: true
        })
        .ajaxSelectPicker({
        ajax: {
            url: '/ajax/?action=getmats&type=" . $selecterID['type'] . "',
            data: function () {
                var params = {
                    query: '{{{q}}}'
                };
               
                return params;
            }
        },
        locale: {
            emptyTitle: 'Поиск вариантов...'
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
    }
}

$this->jsready .= "

    $('#material_type_id')
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

  tinymce.init({
        selector: '.tinymce',
        plugins: 'print preview importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
        menubar: 'file edit view insert format tools table tc help',
        toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
        image_list: $tmImageList,
        image_class_list: [
            { title: 'нет', value: '' },
            { title: 'белая рамка', value: 'img-bordered__white' },
        ],
      });
      
      $('.datetimepicker').datetimepicker({
        format: 'DD.MM.YYYY HH:mm:ss',
        icons: {
                time: 'far fa-clock material-icons',
                date: 'far fa-calendar material-icons',
                up: 'far fa-arrow-up material-icons',
                down: 'far fa-arrow-down material-icons',
                previous: 'far fa-chevron-left material-icons',
                next: 'far fa-chevron-right material-icons',
                today: 'far fa-calendar-check-o material-icons',
                clear: 'far fa-trash material-icons',
                close: 'far fa-times material-icons'
            },
        locale: moment.locale('ru', {
            week: { dow: 1 }
        }),
      });

";

$this->jscripts->addScript('/js/bootstrap-select.js');
$this->jscripts->addScript('/js/defaults-ru_RU.js');
$this->jscripts->addScript('/js/ajax-bootstrap-select.js');
$this->jscripts->addScript('/js/ajax-bootstrap-select.ru-RU.min.js');
$this->jscripts->addScript('/js/tinymce/tinymce.min.js');
$this->jscripts->addScript('/js/tinymce/jquery.tinymce.min.js');
$this->jscripts->addScript('/js/moment.min.js');
$this->jscripts->addScript('/js/tempusdominus-bootstrap-4.min.js');
$this->jscripts->addScript('/js/locales/ru.js');

$this->styles->addStyle('/css/bootstrap-select.css');
$this->styles->addStyle('/css/ajax-bootstrap-select.css');
$this->styles->addStyle('/css/tempusdominus-bootstrap-4.min.css');