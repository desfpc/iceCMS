<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\iceRender $this
 *
 * Module for Image Caches administration
 *
 */

use ice\Models\ImageCache;
use ice\Models\ImageCacheList;

//секурность
if(!$this->moduleAccess())
    {
        return;
    };

$this->moduleData=new stdClass();

$this->moduleData->title=$this->settings->site->title;
$this->moduleData->H1='Кэши изображений';
$this->moduleData->errors=array();
$this->moduleData->success=array();


$this->getRequestValues(['mode','width','height','watermark','w_x','w_y']);

//действия над кэшами
switch ($this->values->mode){

    //добавление нового кэша
    case 'add':

        //проверяем, что форма отправлена
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            //пробуем создать кэш изображения
            $iCache = new ImageCache($this->DB);

            if($this->values->watermark == ''){
                $this->values->watermark = 0;
            }

            if($iCache->createRecord($iCache->paramsFromValues($this->values))){
                $this->moduleData->success[] = 'Кэш изображения <strong>'.$this->values->width.'x'.$this->values->height.'</strong> успешно создан.';
                $this->setFlash('success',['Кэш изображения <strong>'.$this->values->width.'x'.$this->values->height.'</strong> успешно создан']);
                $this->unsetValues();
                $this->redirect('/admin/image_caches_admin');
            }
            else {
                $this->moduleData->errors[] = 'Не удалось сохранить кэш изображения';
            }

        }

        break;

}

//список кэшей
$iCaches = new ImageCacheList($this->DB, null, null, 1, null, 0, null);
$this->moduleData->iCaches = $iCaches->getRecords(null);