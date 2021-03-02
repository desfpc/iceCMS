<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 */

function printTypes($arr, $id, $size)
{

    if (isset($arr[$id]) && is_array($arr[$id]) && count($arr[$id]) > 0) {

        foreach ($arr[$id] as $type) {

            if ($type['sitemenu'] == 1) {
                $menuIcon = '<i class="material-icons md-24 md-dark">check_box</i>';
            } else {
                $menuIcon = '<i class="material-icons md-24 md-dark">indeterminate_check_box</i>';
            }

            echo '<tr class="types_' . $size . '">
    <td>' . $type['id'] . '</td>
    <td>' . $type['id_char'] . '</td>
    <td>' . $type['parent_name'] . '</td>
    <td>' . $type['name'] . '</td>
    <td>' . $menuIcon . '</td>
    <td><small>' . $type['template_list_name'] . '
    <br>' . $type['template_item_name'] . '
    <br>' . $type['template_admin_name'] . '</small></td>
    <td>' . $type['ordernum'] . '</td>
    <td><a href="/admin/material_types_admin/?mode=edit&id=' . $type['id'] . '">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Редактировать">
                <i class="material-icons md-16 md-light">edit</i>
            </button>
        </a><!--
        --><a href="/admin/material_types_admin/?mode=delete&id=' . $type['id'] . '">
            <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Удалить">
                <i class="material-icons md-16 md-light">delete</i>
            </button>
        </a>
    </td>
</tr>';


            //проверяем, есть ли у типа подразделы
            printTypes($arr, $type['id'], ($size + 1));


        }

    }

}

?>
<div class="row">
    <div class="col">
        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th>Id</th>
                <th>Id букв.</th>
                <th>Родитель</th>
                <th>Наименование</th>
                <th>Меню</th>
                <th>Шаблоны</th>
                <th>Вес</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            <?php

            if (is_array($this->moduleData->materialTypes) && count($this->moduleData->materialTypes) > 0) {

                printTypes($this->moduleData->materialTypes['childs'], 0, 0);

            }

            ?>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col">
        <a href="/admin/material_types_admin/?mode=add">
            <button type="button" class="btn btn-primary"><i class="material-icons md-24 md-light">add_box</i> Создать
                новый
            </button>
        </a>
    </div>
</div>