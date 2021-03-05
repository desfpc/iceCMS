<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 */

use ice\Models\File;
use ice\Models\Mat;
use ice\Web\Widget;

?>
<div class="row">
    <div class="col-sm-2">
        <?php
        //выводим дерево категорий (полное)
        include_once($template_folder . '/partial/material_types_admin/menu_list.php');
        ?>
        <hr>
        <a href="/admin/materials_admin/?mode=add&material_type_id=<?= $this->values->mtype ?>">
            <button type="button" class="btn btn-primary"><i class="material-icons md-24 md-light">add_box</i> Создать
            </button>
        </a>
    </div>
    <div class="col-sm-10">
        <?php
        //выводим ошибки
        include_once($template_folder . '/partial/t_alert.php');

        ?>
        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th style="width: 60px;">ID</th>
                <th style="width: 48px;"></th>
                <th>Имя</th>
                <th>Тип материала</th>
                <th style="width: 60px;">Дата создания</th>
                <th style="width: 60px;">Действия</th>
            </tr>
            </thead>
            <tbody>
            <?php

            if (isset($this->moduleData->materials) && is_array($this->moduleData->materials) && count($this->moduleData->materials) > 0) {
                foreach ($this->moduleData->materials as $material) {

                    if ($material['favicon'] != '') {
                        $file = ['id' => $material['favicon']];
                        $favicon = File::formatIcon($this->DB, $file, true);
                    } else {
                        $favicon = '';
                    }

                    echo '<tr>
                                <td>' . Mat::statusIcon($material['status_id']) . '&nbsp;' . $material['id'] . '</td>
                                <td>' . $favicon . '</td>
                                <td>' . $material['name'] . '</td>
                                <td>' . $material['material_type_name'] . '</td>
                                <td><small>' . Mat::formatDate($material['date_add']) . '</small></td>
                                <td><a href="/admin/materials_admin/?mode=edit&id=' . $material['id'] . '">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Редактировать">
                <i class="material-icons md-16 md-light">edit</i>
            </button>
        </a><!--
        --><a href="/admin/materials_admin/?mode=delete&id=' . $material['id'] . '">
            <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Удалить">
                <i class="material-icons md-16 md-light">delete</i>
            </button>
        </a></td>
                            </tr>';
                }
            }

            ?>
            </tbody>
        </table>
        <?php
        $pages = new Widget($this->DB, 'pages', $this->settings);
        $pages->show([
            'count' => $this->moduleData->materialsCnt,
            'perpage' => $this->moduleData->perpage,
            'page' => $this->moduleData->page,
            'url' => $_SERVER['REQUEST_URI']
        ]);
        ?>
    </div>
</div>