<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 */

function printTypesMenu($obj, $arr, $id, $size){

    if(isset($arr[$id]) && is_array($arr[$id]) && count($arr[$id]) > 0){

        echo '<ul>';

        foreach ($arr[$id] as $type) {

            $menuclass = '';
            $class = '';

            if($type['sitemenu'] == 1) {
                $menuclass = 'sitemenu';
            }

            $menu = $obj->module['name'];

            if(isset($obj->values->mtype) && $obj->values->mtype != ''){
                $mtype = $obj->values->mtype;
            }
            else {
                $mtype = 'all';
            }

            if($mtype == $type['id']){
                $class = 'active';
            }

            echo '<li class="'.$menuclass.' '.$class.'"><a href="/admin/'.$menu.'/?mtype='.$type['id'].'&page=1">'.$type['name'].'</a>';

            //проверяем, есть ли у типа подразделы
            printTypesMenu($obj, $arr, $type['id'], ($size+1));

            echo '</li>';

        }

        echo '</ul>';

    }

}
//visualijop($this->moduleData->materialTypes);

echo '<div class="mtypes-menu">';
if(isset($this->moduleData->materialTypes) && is_array($this->moduleData->materialTypes) && count($this->moduleData->materialTypes) > 0) {

    printTypesMenu($this, $this->moduleData->materialTypes['childs'], 0, 0);

    $menuclass = 'mtypes-menu__all';
    $class = '';

    if($this->values->mtype == 'all'){
        $class = 'active';
    }

    $menu = $this->module['name'];
    echo '<p class="'.$menuclass.' '.$class.'"><a href="/admin/'.$menu.'/?mtype=all&page=1">Все</a></p>';

}
echo '</div>';
