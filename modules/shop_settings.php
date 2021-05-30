<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 *
 * TODO online commerce shop settings module
 *
 */

use ice\Models\MatList;

//секурность
if (!$this->moduleAccess()) return;

$this->moduleData = new stdClass();

$this->moduleData->title = $this->settings->site->title;
$this->moduleData->H1 = 'Магазин - настройки';
$this->moduleData->errors = array();
$this->moduleData->success = array();

$query = "SELECT id FROM material_types WHERE id_char = 'online-store-settings'";
if(!$res = $this->DB->query($query)) {
    $this->setFlash('errors', ['Нет возможности определить тип материала настроек']);
    $this->moduleData->settings = null;
} else {
    $settingsId = $res[0]['id'];
    //параметры выборки
    $conditions[] = [
        'string' => false,
        'type' => '=',
        'col' => 'material_type_id',
        'val' => $settingsId
    ];

    //сортировки
    $sort[] = ['col' => 'id', 'sort' => 'desc'];

    $settings = new MatList($this->DB, $conditions, $sort, 1, 100);
    $this->moduleData->settings = $settings->records;
}
