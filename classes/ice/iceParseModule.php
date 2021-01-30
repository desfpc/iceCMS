<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Parse Module From URL Patch Class
 *
 */

namespace ice;

class iceParseModule {

    public $path_info;
    public $DB;
    public $module;

    public function getModule()
    {
        if($this->DB->errors->flag)
        {
            return false;
        }

        //определяем модуль
        if(isset($_REQUEST['menu']))
        {
            $menu=$_REQUEST['menu'];
        }
        else
        {
            $menu='materials';
        }

        $findmodule=false;
        //проверка на наличае модуля в базе
        $query='SELECT * FROM modules WHERE name = '."'".$this->DB->mysqli->real_escape_string($menu)."'";
        if($res=$this->DB->query($query))
        {
            if(count($res) > 0)
            {
                $this->module=$res[0];
                $findmodule=true;
            }
        }

        if(!$findmodule)
        {
            $arr=array();
            $arr['id'] = 9;
            $arr['name'] = '404';
            $arr['content']='Ошибка 404';

            $this->module=$arr;
        }

    }

    public function __construct(iceDB $DB, $path_info)
    {
        $this->DB=$DB;
        $this->path_info=$path_info;

        $this->getModule();

    }

}