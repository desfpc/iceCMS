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

namespace ice\Routes;

use ice\DB\DB;
use ice\Settings\Settings;

class ParseModule
{

    public $settings;
    public $path_info;
    public $DB;
    public $module;

    public function __construct(Settings $settings, DB $DB, $path_info)
    {
        $this->settings = $settings;
        $this->DB = $DB;
        $this->path_info = $path_info;

        $this->getModule();

    }

    public function getModule()
    {
        $menu = '';

        if ($this->DB->errors->flag) {
            return false;
        }

        //определяем модуль
        if (isset($_REQUEST['menu']) && $_REQUEST['menu'] != '') {
            $menu = $_REQUEST['menu'];
        } else {
            //проверяем роуты
            if (is_array($this->settings->routes) && count($this->settings->routes) > 0) {

                if (is_array($this->path_info['call_parts']) && count($this->path_info['call_parts']) > 0) {
                    $callRoute = mb_strtolower(implode('/', $this->path_info['call_parts']), 'UTF8');
                    //удаляем последний / в роуте
                    if (mb_substr($callRoute, -1, 1, 'UTF8') == '/') {
                        $callRoute = mb_substr($callRoute, 0, -1, 'UTF8');
                    }
                    if (key_exists($callRoute, $this->settings->routes)) {
                        $menu = $this->settings->routes[$callRoute];
                    }
                }

            }

            //принудительно выставляем модуль в materials для рендеринга CMS сайта
            if ($menu == '') {
                $menu = 'materials';
            }
        }

        //проверка на наличае модуля в базе
        $query = 'SELECT * FROM modules WHERE name = ' . "'" . $this->DB->mysqli->real_escape_string($menu) . "'";
        if ($res = $this->DB->query($query)) {
            if (count($res) > 0) {
                $this->module = $res[0];
                return $this->module;
            }
        }

        //ничего не нашли, выводим 404
        $arr = [];
        $arr['id'] = 9;
        $arr['name'] = '404';
        $arr['content'] = 'Ошибка 404';
        $this->module = $arr;

        return $this->module;

    }

}