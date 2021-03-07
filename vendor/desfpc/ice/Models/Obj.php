<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Base Object (Based on DB table) Class
 *
 */

namespace ice\Models;

use ice\DB\Cacher;
use ice\DB\DB;
use ice\DB\QueryBuilder;
use ice\Settings\Settings;

class Obj
{

    public $DB;
    public $cols;
    public $params;
    public $id;
    public $settings;
    public $errors;
    public $isGotten = false;
    private $dbtable;
    private $cacher;
    private $cacheKey;

    public function __construct(DB $DB, $dtable, $id = null, $settings = null)
    {
        $this->doConstruct($DB, $dtable, $id, $settings);
    }

    //TODO формирование списка параметров для редактирования/добавления запись из $_POST

    public function doConstruct(DB $DB, $dtable, $id = null, Settings $settings = null)
    {

        $this->errors = [];
        $this->settings = $settings;

        if (is_object($this->settings) && isset($this->settings->cache->host) && isset($this->settings->cache->port)) {
            $this->cacher = new Cacher($this->settings->cache->host, $this->settings->cache->port);
        } else {
            $this->cacher = new Cacher();
        }

        $this->DB = $DB;
        $this->dbtable = $dtable;

        $this->id = $id;
        $this->params = false;

        //получаем колонки таблицы
        $this->getTableCols();

    }

    //формирование списка параметров для редактирования/добавления запись из $this->values

    private function getTableCols()
    {
        $key = $this->DB->settings->name . '_tableCols_' . $this->dbtable;
        $cols = array();

        //TODO сделать метод - удаляем таблицу из кэша
        //$this->cacher->del($key);

        //вытаскиваем из кэша
        if ($this->cacher->has($key) && $cols = $this->cacher->get($key, true)) {
            $this->cols = $cols;
        } else {
            $query = 'SHOW COLUMNS FROM ' . $this->dbtable;
            //visualijoper\visualijoper::visualijop($query);
            if ($res = $this->DB->query($query)) {
                if (count($res) > 0) {
                    $this->cols = $res;
                    $this->cacher->set($key, json_encode($this->cols));
                }
            }
        }
    }

    public static function formatDate($date)
    {
        return date('d.m.Y H:i', strtotime($date));
    }

    //создание новой записи

    public function paramsFromPost()
    {
        //получаем переменные из $_REQUEST
        foreach ($this->cols as $col) {
        }

        //формируем массив с переменными

    }

    //изменение записи

    public function paramsFromValues($values)
    {

        $params = [];
        foreach ($this->cols as $col) {
            $valueName = $col['Field'];
            if ($valueName != 'id') {
                if (isset($values->$valueName)) {
                    $params[$col['Field']] = $values->$valueName;
                }
            }
        }

        return $params;

    }

    //удаление записи

    public function createRecord($params = null)
    {

        if (!is_null($params) && is_array($params)) {
            $this->params = $params;
        }

        //формируем запрос для создания записи
        $qbuilder = new QueryBuilder($this->DB, $this->cols, $this->params, $this->dbtable);
        $query = $qbuilder->insert();

        if ($res = $this->DB->query($query)) {
            //получаем и возвращаем идентификатор записи
            $this->id = $this->DB->mysqli->insert_id;

            //выполняем дополнительные действия
            $this->afterCreateRecord();

            return $this->id;
        }
        return false;

    }

    public function afterCreateRecord()
    {
        return true;
    }

    //метод для переработки в конкретном объекте

    public function updateRecord($params = null)
    {

        if (!is_null($params) && is_array($params)) {
            $this->params = $params;
        }

        if (!isset($this->params['id'])) {
            $this->params['id'] = $this->id;
        }

        $qbuilder = new QueryBuilder($this->DB, $this->cols, $this->params, $this->dbtable);
        $query = $qbuilder->update();

        //die($query);

        if ($res = $this->DB->query($query)) {
            $this->uncacheRecord();
            return true;
        }
        return false;
    }

    //метод для переработки в конкретном объекте

    public function uncacheRecord($id = null)
    {

        if (is_null($id)) {
            $id = $this->params['id'];
        }

        $cachekey = $this->getCacheKey($id);
        if ($this->cacher->del($cachekey)) {
            //расширенное удаление кэшей у связанных сущностей
            $this->fullUncacheRecord();
            return true;
        }

        return false;

    }

    //получение данных обхекта

    private function getCacheKey($id = null)
    {
        if (is_null($id)) {
            $this->cacheKey = $this->DB->settings->name . '_record_' . $this->dbtable . '_' . $this->id;
            return $this->cacheKey;
        }
        return $this->DB->settings->name . '_record_' . $this->dbtable . '_' . $id;

    }

    //кэширование записи

    public function fullUncacheRecord()
    {
        //TODO удаление кэшей списков объектов
    }

    //удаление из кэша записи

    public function deleteRecord($id)
    {
        $this->id = $id;

        $query = 'DELETE FROM ' . $this->dbtable . ' WHERE id = ' . $this->values->id;
        if ($res = $this->DB->query($query)) {
            $this->uncacheRecord();
            $this->id = null;
            $this->params = false;
            return true;
        }
        return false;
    }

    public function getRecord($id = null)
    {

        $this->params = false;

        if (!is_null($id)) {
            $this->id = $id;
        }

        if (is_null($this->id)) {
            return false;
        }

        //проверяем наличае записи в кэше
        $this->getCacheKey();

        if (!$this->cacher->has($this->cacheKey) || $this->params != $this->cacher->get($this->cacheKey, true)) {
            $query = 'SELECT * FROM ' . $this->dbtable . ' WHERE id = ' . $this->id;

            if ($res = $this->DB->query($query)) {
                if (count($res) > 0) {
                    $this->params = $res[0];
                    $this->fullRecord();
                    $this->cacheRecord();

                    $this->isGotten = true;

                    return $this->params;
                }
            }
        }
        return false;
    }

    public function fullRecord()
    {

    }

    private function cacheRecord($expired = 30 * 24 * 60 * 60)
    {
        $this->getCacheKey();
        //$expired=30*24*60*60;
        $this->cacher->set($this->cacheKey, json_encode($this->params), $expired);
    }

}