<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\iceRender $this
 */

$template_folder=$this->settings->path.'/templates/'.$this->settings->template.'';

//подключаем стили и скрипты
include_once ($template_folder.'/partial/t_jsandcss.php');
//$this->styles->addStyle('');
//$this->jscripts->addScript('');

//js document.load
include_once ($template_folder.'/partial/t_jsreadyglobal.php');
//$this->jsready.='';

include_once ($template_folder.'/partial/t_header.php');

?>
    <div class="container sitebody">
        <div class="row">
            <div class="col">
                <?php
                //выводим ошибки
                include_once ($template_folder.'/partial/t_alert.php');
                ?>
            </div>
        </div><?php

        if($this->values->mode == 'edit') {

            $params = $this->moduleData->editTemplate->params;

            ?>
            <div class="row">
                <div class="col">
                    <h2>Изменить шаблон:</h2>
                    <form method="post" action="/admin/templates">
                        <input type="hidden" name="menu" value="templates" />
                        <input type="hidden" name="mode" value="edit" />
                        <input type="hidden" name="id" value="<?=$params['id']?>" />
                        <div class="form-group row">
                            <label for="filename" class="col-sm-3 col-form-label">Наименование файла:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="filename" name="filename" value="<?=$params['filename']?>" placeholder="newtemplate">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label">Наименование шаблона:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="name" name="name" value="<?=$params['name']?>" placeholder="Новый шаблон">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="type" class="col-sm-3 col-form-label">Тип шаблона:</label>
                            <div class="col-sm-9">
                                <select id="type" name="type" class="form-control">
                                    <?php

                                    $types = [
                                        'Шаблон материала',
                                        'Шаблон списка материалов',
                                        'Шаблон формы редактирования материала'
                                    ];

                                    $i=0;
                                    foreach ($types as $type){
                                        ++$i;

                                        if($i == $params['type']){
                                            $selected = 'SELECTED';
                                        }
                                        else {
                                            $selected = '';
                                        }

                                        echo '<option '.$selected.' value="'.$i.'">'.$type.'</option>';
                                    }

                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="content" class="col-sm-3 col-form-label">Описание шаблона:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="content" name="content" value="<?=$params['content']?>" placeholder="Новый шаблон для покорения мира">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Изменить</button>
                    </form>
                </div>
            </div>
            <hr />
            <p>&nbsp;</p>
            <?php
        }
        ?>
        <div class="row">
            <div class="col">
                <table class="table">
                    <tbody class="thead-dark">
                        <tr>
                            <th>Id</th>
                            <th>Файл</th>
                            <th>Наименование</th>
                            <th>Тип</th>
                            <th>Связанные типы материалов</th>
                            <th>Описание</th>
                            <th>Действия</th>
                        </tr>
                        <?php

                        if(isset($this->moduleData->templates) && count($this->moduleData->templates) > 0) {
                            foreach ($this->moduleData->templates as $template) {

                                /*echo '<pre>';
                                print_r($template);
                                echo '</pre>';*/

                                $mat_types = '';

                                if(isset($template['mat_types']) && is_array($template['mat_types']) && count($template['mat_types']) > 0) {

                                    foreach ($template['mat_types'] as $mtype) {

                                    }

                                }

                                echo '
                        <tr>
                            <td>'.$template['id'].'</td>
                            <td>'.$template['filename'].'</td>
                            <td>'.$template['name'].'</td>
                            <td>'.$template['type_name'].'</td>
                            <td>'.$mat_types.'</td>
                            <td>'.$template['content'].'</td>
                            <td><a href="/admin/templates/?mode=edit&id='.$template['id'].'">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Редактировать">
                <i class="material-icons md-16 md-light">edit</i>
            </button>
        </a></td>
                        </tr>';
                            }
                        }

                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col">
                <h2>Добавить шаблон:</h2>
                <form method="post" action="/admin/templates">
                    <input type="hidden" name="menu" value="templates" />
                    <input type="hidden" name="mode" value="add" />
                    <div class="form-group row">
                        <label for="filename" class="col-sm-3 col-form-label">Наименование файла:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="filename" name="filename" value="<?=$this->values->filename?>" placeholder="newtemplate">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Наименование шаблона:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" name="name" value="<?=$this->values->name?>" placeholder="Новый шаблон">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="type" class="col-sm-3 col-form-label">Тип шаблона:</label>
                        <div class="col-sm-9">
                            <select id="type" name="type" class="form-control">
                                <?php

                                $types = [
                                    'Шаблон материала',
                                    'Шаблон списка материалов',
                                    'Шаблон формы редактирования материала'
                                ];

                                $i=0;
                                foreach ($types as $type){
                                    ++$i;

                                    if($i == $this->values->type){
                                        $selected = 'SELECTED';
                                    }
                                    else {
                                        $selected = '';
                                    }

                                    echo '<option '.$selected.' value="'.$i.'">'.$type.'</option>';
                                }

                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="content" class="col-sm-3 col-form-label">Описание шаблона:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="content" name="content" value="<?=$this->values->content?>" placeholder="Новый шаблон для покорения мира">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Добавить</button>
                </form>
            </div>
        </div>
    </div>
<?php include_once ($template_folder.'/partial/t_footer.php');