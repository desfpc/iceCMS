<?php

use ice\Models\File;
use ice\Helpers\Strings;

?>
<h2>Файлы:</h2>
<table class="table">
    <thead class="thead-dark">
    <tr>
        <th style="width: 30px;">ID</th>
        <th style="width: 48px;"></th>
        <th>Имя</th>
        <th>Имя файла</th>
        <th style="width: 60px;">Дата создания</th>
        <th style="width: 60px;">Размер</th>
        <th style="width: 60px;">Действия</th>
    </tr>
    </thead>
    <tbody>
    <?php

    if (isset($this->moduleData->material->files) && is_array($this->moduleData->material->files) && count($this->moduleData->material->files) > 0) {

        $tmImageList = '[';

        foreach ($this->moduleData->material->files as $file) {

            $iconArr = File::formatIcon($this->DB, $file, true, true);
            $fileIcon = $iconArr['icon'];
            $fileLink = $iconArr['link'];

            if ($file['filetype'] == 'image') {
                if ($tmImageList != '[') {
                    $tmImageList .= ',';
                }
                $tmImageList .= '{ title: \'' . $file['name'] . '\', value: \'' . $fileLink . '\' }';
            }

            echo '<tr>
                                <td>' . $file['id'] . '</td>
                                <td>' . $fileIcon . '</td>
                                <td>' . $file['name'] . '</td>
                                <td>' . $file['filename'] . '</td>
                                <td>' . Strings::formatDate($file['date_add']) . '</td>
                                <td>' . File::formateSize($file['size']) . '</td>
                                <td></td>
                            </tr>';
        }

        $tmImageList .= ']';

    }

    ?>
    </tbody>
</table>
<br/>
<h3>Загрузить файл:</h3>
<form method="post" enctype="multipart/form-data"
      action="/admin/materials_admin/?mode=edit&id=<?= $this->moduleData->material->params['id'] ?>">
    <input type="hidden" name="menu" value="materials_admin">
    <input type="hidden" name="mode" value="edit">
    <input type="hidden" name="id" value="<?= $this->moduleData->material->params['id'] ?>">
    <input type="hidden" name="MAX_FILE_SIZE" value="20971520">
    <input type="file" id="newFile" name="newFile">
    <input type="hidden" name="action" value="addfile">
    <button type="submit" class="btn btn-success"><i class="material-icons md-24 md-light">attach_file</i> Загрузить
    </button>
</form>
<hr/>
<br/>
