<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 */

use ice\Models\MatList;
use ice\Web\Widget;

//получаем товары
$conditions = [];

if ($this->moduleData->mtype->params['id'] == 9) {
    //все товары

    $catalogTypes = $this->materialTypes['childs'];
    $catalogTypes = $catalogTypes[9];

    $ids = '9';
    foreach ($catalogTypes as $type) {
        if ($ids != '') {
            $ids .= ',';
        }
        $ids .= $type['id'];
    }

    $conditions[] = [
        'string' => false,
        'type' => 'IN',
        'col' => 'material_type_id',
        'val' => $ids
    ];
} else {
    //товары активного раздела
    $conditions[] = [
        'string' => false,
        'type' => '=',
        'col' => 'material_type_id',
        'val' => $this->moduleData->mtype->params['id']
    ];
}

$conditions[] = [
    'string' => false,
    'type' => '=',
    'col' => 'status_id',
    'val' => 1
];

//сортировки
$sort[] = ['col' => 'rand()', 'sort' => ''];

$goods = new MatList($this->DB, $conditions, $sort, 1, 4);
$goods = $goods->getRecords();

?>
    <div class="row no-gutter">
    <div class="col-md-3">
        <?php

        $catalogId = 7; //main catalog id

        $navigation = new Widget($this->DB, 'horizontalMenu', $this->settings);
        $navigation->show(['types' => $this->materialTypes, 'parent' => $catalogId, 'active' => $this->moduleData->mtype->params['id_char']]);

        ?>
    </div>
    <div class="col-md-9">
        <?php

        if ($goods && count($goods) > 0) {

            $i = 0;
            foreach ($goods as $good) {
                ++$i;
                if ($i == 1) {
                    echo '<div class="row">';
                }

                echo '<div class="col-md-4">';

                $wGood = new Widget($this->DB, 'good', $this->settings);
                $wGood->show($good);

                echo '</div>';

                if ($i == 3) {
                    $i = 0;
                    echo '</div>';
                }
            }
            if ($i > 0) {
                echo '</div>';
            }

        }

        ?>
    </div>
    </div><?php

//TODO пейджинация