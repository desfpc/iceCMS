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

namespace ice;


class iceObject {

    private $dbtable;
    public $DB;
    private $cacher;
    public $cols;
    public $params;
    public $id;
    private $cacheKey;
    public $settings;
    public $errors;

    public static function formatDate($date){
        return date('d.m.Y H:i',strtotime($date));
    }

    //TODO формирование списка параметров для редактирования/добавления запись из $_POST
    public function paramsFromPost()
    {
        //получаем переменные из $_REQUEST
        foreach ($this->cols as $col)
        {
        }

        //формируем массив с переменными

    }

    //формирование списка параметров для редактирования/добавления запись из $this->values
    public function paramsFromValues($values) {

        $params = [];
        foreach ($this->cols as $col)
        {
            $valueName = $col['Field'];
            if($valueName != 'id') {
                if(isset($values->$valueName)){
                    $params[$col['Field']] = $values->$valueName;
                }
            }
        }

        return $params;

    }

    public function afterCreateRecord(){
        return true;
    }

    //создание новой записи
    public function createRecord($params = null){

        if(!is_null($params) && is_array($params))
        {
            $this->params = $params;
        }

        //формируем запрос для создания записи
        $qbuilder=new iceQueryBuilder($this->DB, $this->cols, $this->params, $this->dbtable);
        $query=$qbuilder->insert();

        if($res=$this->DB->query($query))
        {
            //получаем и возвращаем идентификатор записи
            $this->id = $this->DB->mysqli->insert_id;

            //выполняем дополнительные действия
            $this->afterCreateRecord();

            return $this->id;
        }
        return false;

    }

    //изменение записи
    public function updateRecord($params = null){

        if(!is_null($params) && is_array($params))
        {
            $this->params = $params;
        }

        if(!isset($this->params['id'])) {
            $this->params['id'] = $this->id;
        }

        $qbuilder=new iceQueryBuilder($this->DB, $this->cols, $this->params, $this->dbtable);
        $query=$qbuilder->update();

        //die($query);

        if($res=$this->DB->query($query))
        {
            $this->uncacheRecord();
            return true;
        }
        return false;
    }

    //удаление записи
    public function deleteRecord($id){
        $this->id=$id;

        $query='DELETE FROM '.$this->dbtable.' WHERE id = '.$this->values->id;
        if($res=$this->DB->query($query))
        {
            $this->uncacheRecord();
            $this->id = null;
            $this->params=false;
            return true;
        }
        return false;
    }

    private function getCacheKey($id=null)
    {
        if(is_null($id))
        {
            $this->cacheKey = $this->DB->settings->name.'_record_'.$this->dbtable.'_'.$this->id;
            return $this->cacheKey;
        }
        return $this->DB->settings->name.'_record_'.$this->dbtable.'_'.$id;

    }

    //метод для переработки в конкретном объекте
    public function fullRecord(){

    }

    //метод для переработки в конкретном объекте
    public function fullUncacheRecord(){

    }

    //получение данных обхекта
    public function getRecord($id = null){

        $this->params=false;

        if(!is_null($id)){
            $this->id=$id;
        }

        if(is_null($this->id)){
            return false;
        }

        //проверяем наличае записи в кэше
        $this->getCacheKey();

        if(!$this->cacher->has($this->cacheKey) || $this->params != $this->cacher->get($this->cacheKey, true))
        {
            $query='SELECT * FROM '.$this->dbtable.' WHERE id = '.$this->id;

            if($res = $this->DB->query($query))
            {
                if(count($res) > 0)
                {
                    $this->params=$res[0];
                    $this->fullRecord();

                    $this->cacheRecord();

                    return $this->params;
                }
            }
        }
        return false;
    }

    //кэширование записи
    private function cacheRecord($expired=30*24*60*60){
        $this->getCacheKey();
        //$expired=30*24*60*60;
        $this->cacher->set($this->cacheKey,json_encode($this->params),$expired);
    }

    //удаление из кэша записи
    public function uncacheRecord($id=null){

        if(is_null($id)){
            $id = $this->params['id'];
        }

        $cachekey = $this->getCacheKey($id);
        if($this->cacher->del($cachekey)){
            //расширенное удаление кэшей у связанных сущностей
            $this->fullUncacheRecord();
            return true;
        }

        return false;

    }

    private function getTableCols()
    {
        $key=$this->DB->settings->name.'_tableCols_'.$this->dbtable;
        $cols=array();

        //TODO сделать метод - удаляем таблицу из кэша
        //$this->cacher->del($key);

        //вытаскиваем из кэша
        if($this->cacher->has($key) && $cols=$this->cacher->get($key, true))
        {
            $this->cols=$cols;
        }
        else
        {
            $query='SHOW COLUMNS FROM '.$this->dbtable;
            //visualijop($query);
            if($res=$this->DB->query($query))
            {
                if(count($res) > 0)
                {
                    $this->cols=$res;
                    $this->cacher->set($key,json_encode($this->cols));
                }
            }
        }
    }

    public function doConstruct(iceDB $DB, $dtable, $id=null, iceSettings $settings=null)
    {

        $this->errors = [];
        $this->settings=$settings;

        if(is_object($this->settings) && isset($this->settings->cache->host) && isset($this->settings->cache->port))
        {
            $this->cacher = new iceCacher($this->settings->cache->host,$this->settings->cache->port);
        }
        else
        {
            $this->cacher = new iceCacher();
        }

        $this->DB = $DB;
        $this->dbtable = $dtable;

        $this->id = $id;
        $this->params=false;

        //получаем колонки таблицы
        $this->getTableCols();

    }

    public function __construct(iceDB $DB, $dtable, $id=null, $settings=null)
    {
        $this->doConstruct($DB, $dtable, $id, $settings);
    }

}