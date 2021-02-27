<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Widget Class
 *
 */

namespace ice;

use ice\DB\DB;

class iceWidget {

    public $name;
    public $DB;
    public $params;
    public $settings;
    public $errors = [];
    public $styles;
    public $jscripts;

    public function __construct(DB $DB, $name, $settings=null)
    {
        $this->DB = $DB;
        $this->name = $name;

        $this->settings=$settings;

        $this->jscripts = new iceJScriptBuilder();
        $this->styles = new iceStylesBuilder();

        //TODO раскомитить, если нужно будет кэширование для виджетов
        /*if(is_object($this->settings) && isset($this->settings->cache->host) && isset($this->settings->cache->port))
        {
            $this->cacher = new iceCacher($this->settings->cache->host,$this->settings->cache->port);
        }
        else
        {
            $this->cacher = new iceCacher();
        }*/

    }

    //подключаем файл виджета
    public function show($params = []){

        $this->params = $params;

        if($this->name != ''){

            //проверяем наличае файла виджета
            $widgetPatch=$this->settings->path.'/widgets/'.$this->name.'.php';
            if(!file_exists($widgetPatch))
            {
                $this->errors[]='Нет файла подключаемого виджета '.$widgetPatch;
            }
            else
            {
                require ($widgetPatch);
            }

        }

    }

}