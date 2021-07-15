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
use visualijoper\visualijoper;

class Obj
{

    public DB $DB;
    public $cols;
    public $params;
    public $id;
    public $settings;
    public $errors;
    public $isGotten = false;
    private $dbtable;
    private $cacher;
    private $cacheKey;
    private $rules; //TODO дополнительные правила для валидации полей

    //наименования свойств $params
    public static $labels = [];//нужно переопределить в конкретном классе

    public function __construct(DB $DB, $dtable, $id = null, $settings = null)
    {
        $this->doConstruct($DB, $dtable, $id, $settings);
    }

    //непосредственно construct - сделано, что бы не указывать имя таблицы в дочерних классах при переопледелении __construct
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

    //TODO валидация объекта
    public function validate()
    {

    }

    //получение полей таблицы из БД
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

            if ($res = $this->DB->query($query)) {
                if (count($res) > 0) {
                    $this->cols = $res;
                    $this->cacher->set($key, json_encode($this->cols));
                }
            }
        }
    }

    public function paramsFromPost(){
        $post = $_REQUEST;

        $params = [];

        foreach ($this->cols as $col){
            if(isset($post[$col['Field']])){
                $params[$col['Field']] = $post[$col['Field']];
            }
        }

        return $params;

    }

    //заполенние полей объекта(таблицы) из values
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

    //создание записи в БД
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

    //действие после создания записи в БД
    public function afterCreateRecord()
    {
        return true;
    }

    //обновление записи в БД
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

        //visualijoper::visualijop($query);

        //die($query);

        if ($res = $this->DB->query($query)) {
            $this->uncacheRecord();
            return true;
        }
        return false;
    }

    //удаление кэша записи
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

    //формирование ключа для кэша объекта
    private function getCacheKey($id = null)
    {
        if (is_null($id)) {
            $this->cacheKey = $this->DB->settings->name . '_record_' . $this->dbtable . '_' . $this->id;
            return $this->cacheKey;
        }
        return $this->DB->settings->name . '_record_' . $this->dbtable . '_' . $id;

    }

    //дополнительная логика по удалению кэша записи (например удаление кэшкй связанных объкектов или списков объектов)
    public function fullUncacheRecord()
    {
        //TODO удаление кэшей списков объектов
    }

    //удаление записи из БД
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

    //получение записи из БД
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

    //полное формирование данных объекта (доп. логика после получения данных из таблицы БД)
    public function fullRecord()
    {

    }

    //кэширование объекта
    private function cacheRecord($expired = 30 * 24 * 60 * 60)
    {
        $this->getCacheKey();
        //$expired=30*24*60*60;
        $this->cacher->set($this->cacheKey, json_encode($this->params), $expired);
    }

}