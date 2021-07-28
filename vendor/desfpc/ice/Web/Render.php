<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Site Render Class
 *
 */

namespace ice\Web;

use ice\DB\Cacher;
use ice\DB\DB;
use ice\Models\MatTypeList;
use ice\Routes\ParseModule;
use ice\Routes\PathParser;
use ice\Settings\Settings;
use stdClass;
use visualijoper\visualijoper;

class Render
{

    public $settings; //настройки
    public $DB; //объект БД
    public $headers; //заголовки вывода
    public $moduleData; //данные, подготовленные модулем
    public $body; //тело вывода
    public $seo; //ключевые слова, описания и прочая дрянь
    public $jscripts; //jsскрипты вывода (массив с перечислением подгружаемых скриптов)
    public $jsready; //js тело для document.ready
    public $styles; //стили css (массив с перечислением подгружаемых стилей)
    public $errors; //ошибки
    public $warnings; //важные уведомления
    public $sitemessages; //сообщение от сайта пользователю (например результат какой-либо обработки)
    public $module; //активный модуль для вывода
    public $language; //выбранный язык отображения
    public $languageName; //выбранный язык отображения
    public $path_info;//результат разбора строки
    public $authorize;//авторизация пользюка
    public $values;//переменные
    public $materialTypes;//типы материалов
    public $version;
    public $cacher;
    public $parser;

    public function __construct($setup, $makeMaterialTypes = false)
    {
        $this->version = '0.1';
        $this->settings = new Settings($setup);
        $this->DB = new DB($this->settings);
        $this->styles = new StylesBuilder();
        $this->jscripts = new JScriptBuilder();
        $this->errors = array();
        $this->values = new stdClass();
        $this->authorize = new Authorize($this->DB);

        $this->language = 1; //TODO получение языка
        $this->languageName = 'ru';

        if (is_object($this->settings) && isset($this->settings->cache->host) && isset($this->settings->cache->port)) {
            $this->cacher = new Cacher($this->settings->cache->host, $this->settings->cache->port);
        } else {
            $this->cacher = new Cacher();
        }

        //проверяем ошибки БД
        if (isset($this->DB->errors) && $this->DB->errors->flag == 1) {
            $this->errors[] = $this->DB->errors->text;
        } //формируем типы материалов (для меню, парсинга итд итп)
        elseif ($makeMaterialTypes) {

            $this->materialTypes = $this->getMaterialTypesTree();
            $this->parser = new PathParser($this->DB, $this->materialTypes, $this->settings);
        }
    }

    public function getMaterialTypesTree()
    {

        $parser = new PathParser($this->DB, [], $this->settings);

        $key = $parser->getMTTCacheKey();
        if ($this->cacher->has($key)) {
            $cache = $parser->getMTTCache($key);
            if (!empty($cache)) {
                return $cache;
            }
        }

        $materialTypes = new MatTypeList($this->DB, null, null, 1, null, 0, null);
        $materialTypes = $materialTypes->getRecordsTree('all');
        $parser->setMTTCache($key, $materialTypes, 1 * 24 * 60 * 60);
        return $materialTypes;

    }

    public function moduleAccess()
    {
        if ($this->authorize->autorized) {
            if ((int)$this->authorize->user->params['role']['secure'] === 1) {
                return true;
            }
        }
        $this->module['name'] = '404';
        $this->loadModule();
        return false;
    }

    public function loadModule()
    {
        if (is_array($this->module) && $this->module['name'] != '') {
            //проверяем наличае файла модуля
            $modulePatch = $this->settings->path . '/modules/' . $this->module['name'] . '.php';
            if (!file_exists($modulePatch)) {
                $this->errors[] = 'Нет файла подключаемого модуля ' . $modulePatch;
            } else {
                require($modulePatch);
            }
        } else {
            $this->errors[] = 'Не выбран модуль для загрузки';
        }
    }

    public function setFlash($name, $value)
    {
        $flash = new FlashVars();
        $flash->set($name, $value);
    }

    public function getFlash($name)
    {
        $flash = new FlashVars();
        return ($flash->get($name));
    }

    public function redirect($url, $code = null)
    {
        $redirect = new Redirect($url, $code);
        die();
    }

    public function getRequestValue($valuename, $mode = 0)
    {

        $rv = new RequestValues($this->values);
        $rv->getRequestValue($valuename, $mode);

        $this->values = $rv->returnValues();

    }

    public function getRequestValues($valuesnames, $mode = 0)
    {

        $rv = new RequestValues($this->values);
        $rv->getRequestValues($valuesnames, $mode);

        $this->values = $rv->returnValues();

    }

    public function unsetValues()
    {
        if (is_object($this->values)) {
            if (count(get_object_vars($this->values)) > 0) {
                foreach ($this->values as $key => $value) {
                    $this->values->$key = '';
                }
            }
        }
        return true;
    }

    public function showErrors()
    {
        if (is_array($this->errors) && count($this->errors) > 0) {
            visualijoper\visualijoper::visualijop($this->errors);
        }
    }

    public function parseREST()
    {

    }

    public function parseURL()
    {
        $path = [];
        if (isset($_SERVER['REQUEST_URI'])) {
            $request_path = explode('?', $_SERVER['REQUEST_URI']);

            $path['base'] = rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/');
            $path['call_utf8'] = substr(urldecode($request_path[0]), strlen($path['base']) + 1);
            $path['call'] = utf8_decode($path['call_utf8']);
            if ($path['call'] == basename($_SERVER['PHP_SELF'])) {
                $path['call'] = '';
            }
            $path['call_parts'] = explode('/', $path['call']);

            if (isset($request_path[1])) {
                $path['query_utf8'] = urldecode($request_path[1]);
                $path['query'] = utf8_decode(urldecode($request_path[1]));
            } else {
                $path['query_utf8'] = '';
                $path['query'] = '';
            }
            $vars = explode('&', $path['query']);
            foreach ($vars as $var) {
                $t = explode('=', $var);
                if (isset($t[1])) {
                    $path['query_vars'][$t[0]] = $t[1];
                } else {
                    $path['query_vars'][$t[0]] = '';
                }

            }
        }
        $this->path_info = $path;

        $this->module = new ParseModule($this->settings, $this->DB, $path);
        $this->module = $this->module->module;
    }

    public function printSite()
    {
        //если есть ошибки сайта - выводим 500 ошибку
        if (is_array($this->errors) && count($this->errors) > 0) {
            $errors = $this->errors;
            $this->module['name'] = '500';
            $this->loadModule();
            $this->loadTemplate();
            $this->errors = null;
            $this->printSite();
            $this->errors = $errors;
        } else {
            //выводим заголовки
            if (is_null($this->headers)) {
                $this->headers = new HeaderBuilder();
                $this->headers->standartHeaders();
            }

            $this->headers->printHeaders();

            //выводим тело сайта (формируется в шаблоне)
            if (!is_null($this->body)) {
                echo $this->body;
            }
        }

    }

    public function loadTemplate()
    {
        if (is_array($this->module) && $this->module['name'] != '') {
            //проверяем наличае файла модуля
            $templatePatch = $this->settings->path . '/templates/' . $this->settings->template . '/module/t_' . $this->module['name'] . '.php';
            if (!file_exists($templatePatch)) {
                $this->errors[] = 'Нет файла подключаемого шаблона ' . $templatePatch;
            } else {
                ob_start();
                require($templatePatch);
                $this->body = ob_get_contents();
                ob_end_clean();
            }
        } else {
            $this->errors[] = 'Не подключен ни один модуль';
        }
    }

    public function destroy()
    {
        if ($this->DB->mysqli) {
            $this->DB->mysqli->close();
        }
        unset($this->settings);
        unset($this->DB); //объект БД
        unset($this->headers); //заголовки вывода
        unset($this->moduleData); //данные, подготовленные модулем
        unset($this->body); //тело вывода
        unset($this->seo); //ключевые слова, описания и прочая дрянь
        unset($this->jscripts); //jsскрипты вывода (массив с перечислением подгружаемых скриптов)
        unset($this->jsready); //js тело для document.ready
        unset($this->styles); //стили css (массив с перечислением подгружаемых стилей)
        unset($this->errors); //ошибки
        unset($this->warnings); //важные уведомления
        unset($this->sitemessages); //сообщение от сайта пользователю (например результат какой-либо обработки)
        unset($this->module); //активный модуль для вывода
        unset($this->language); //выбранный язык отображения
        unset($this->path_info); //результат разбора строки
        unset($this->authorize); //авторизация пользюка
        unset($this->values); //переменные
    }
}