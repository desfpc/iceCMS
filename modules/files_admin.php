<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 *
 * Module for Files administration
 *
 */

use ice\Models\File;
use ice\Models\FileList;
use ice\Models\MatTypeList;

//секурность
if (!$this->moduleAccess()) {
    return;
};

$this->moduleData = new stdClass();

$this->moduleData->title = $this->settings->site->title;
$this->moduleData->H1 = 'Файлы администрирование';

//получение переменных
$this->getRequestValues(['mtype', 'action']);

$this->moduleData->errors = [];
$this->moduleData->success = [];

//получаем дерево типов материалов (всех)
$materialTypes = new MatTypeList($this->DB, null, null, 1, null, 0, null);
$this->moduleData->materialTypes = $materialTypes->getRecordsTree('all');

$this->moduleData->materialTypes[0][] = [
    'id' => 'all',
    'name' => 'Все',
    'sitemenu' => 0,
    'parent_id' => 0,
    'list_items' => 0,

];
$this->moduleData->materialTypes[0][] = [
    'id' => 'none',
    'name' => 'Не связанные',
    'sitemenu' => 0,
    'parent_id' => 0,
    'list_items' => 0,

];

//действия
switch ($this->values->action) {
    case 'addfile':

        $file = new File($this->DB, null, $this->settings);
        if ($file->upload('newFile', 'auto', false, $this->authorize->user->id)) {
            $stext = 'Файл <strong>' . $file->params['filename'] . '</strong> успешно загружен.';
            $this->moduleData->success[] = $stext;
            $this->setFlash('success', [$stext]);
            $this->unsetValues();
            $this->redirect('/admin/files/?page=1&mtype=' . $this->values->mtype);
        } else {
            $this->moduleData->errors = $file->errors;
        }

        break;
}

//список файлов
//$iCaches = new ImageCacheList($this->DB, null, null, 1, null, 0, null);
//$this->moduleData->iCaches = $iCaches->getRecords(null);

//ограничиваем список в зависимости от переданного mtype
$conditions = null;
if ($this->values->mtype == '') {
    $this->values->mtype = 'all';
} elseif ($this->values->mtype != 'all') {

    if ($this->values->mtype == 'none') {
        $conditions[] = [
            'string' => false,
            'type' => 'NOT IN',
            'col' => 'id',
            'val' => 'SELECT mf.file_id FROM material_files mf'
        ];
    } else {
        $conditions[] = [
            'string' => false,
            'type' => 'IN',
            'col' => 'id',
            'val' => 'SELECT mf.file_id FROM material_files mf WHERE mf.material_id IN (SELECT mat.id FROM materials mat WHERE mat.material_type_id = ' . $this->values->mtype . ')'
        ];
    }

}

$files = new FileList($this->DB, $conditions, [['col' => 'id', 'sort' => 'DESC']]);
$this->moduleData->files = $files->getRecords(null);
