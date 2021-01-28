<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 */
//настройки фрэймворка
class iceSettings {
    public $db;
    public $email;
    public $sms;
    public $template;
    public $errors;
    public $site;
    public $cache;
    public $path;

    public function __construct($setup)
    {

        $this->errors = new stdClass();
        $this->errors->flag=0;
        $this->errors->text='Настройки не загружались';

        try
        {

            $settingsvalues=array();

            $settingsvalues['path']=1;
            $settingsvalues['template']=1;
            $settingsvalues['dev']=1;

            $settingsvalues['db']['type']=1;
            $settingsvalues['db']['name']=1;
            $settingsvalues['db']['host']=1;
            $settingsvalues['db']['port']=1;
            $settingsvalues['db']['login']=1;
            $settingsvalues['db']['pass']=1;
            $settingsvalues['db']['encoding']=1;

            $settingsvalues['email']['mail']=1;
            $settingsvalues['email']['port']=1;
            $settingsvalues['email']['signature']=1;
            $settingsvalues['email']['pass']=1;
            $settingsvalues['email']['smtp']=1;

            $settingsvalues['site']['title']=1;
            $settingsvalues['site']['primary_domain']=1;
            $settingsvalues['site']['redirect_to_primary_domain']=1;
            $settingsvalues['site']['language_subdomain']=1;

            $settingsvalues['cache']['use_redis']=1;
            $settingsvalues['cache']['redis_host']=1;
            $settingsvalues['cache']['redis_port']=1;

            foreach ($settingsvalues as $key => $value)
            {
                if(is_array($value))
                {
                    $paramname=$key;

                    if(!isset($setup[$paramname]) && !is_array($setup[$paramname]))
                    {
                        throw new Exception('Ошибка файла настроек - нет необходимого поля либо оно не является массивом: '.$paramname);
                    }

                    $this->$paramname = new stdClass();

                    foreach ($setup[$paramname] as $key2 => $value2)
                    {
                        $paramname2=$key2;

                        if($value2 == 1)
                        {
                            if(!isset($setup[$paramname][$paramname2]))
                            {
                                throw new Exception('Ошибка файла настроек - нет необходимого поля: '.$paramname.'-'.$paramname2);
                            }
                        }

                        $this->$paramname->$paramname2 = $setup[$paramname][$paramname2];

                    }

                }
                else
                {
                    $paramname=$key;

                    if($value == 1)
                    {
                        if(!isset($setup[$paramname]))
                        {
                            throw new Exception('Ошибка файла настроек - нет необходимого поля: '.$paramname);
                        }
                    }

                    $this->$paramname = $setup[$paramname];

                }
            }

            $this->errors->flag=0;
            $this->errors->text='Настройки загружены';

        }
        catch (Throwable $t)
        {
            $this->errors->flag=1;
            $this->errors->text='Не удалось загрузить настройки: '.$t->getMessage();
        }

    }

}

//работа с БД
class iceDB {
    public $settings;
    public $errors;
    public $warning;
    public $mysqli;
    public $status;

    public function __construct(iceSettings $settings)
    {
        $this->status = new stdClass();
        $this->errors = new stdClass();
        $this->warning = new stdClass();

        $this->status->flag=0;
        $this->status->text='Соединение с БД не установлено';

        $this->settings = $settings->db;

        $this->connect();
    }

    //соединение с БД
    public function connect()
    {

        $this->errors->flag='1';
        $this->errors->text='Выбранный тип БД ('.$this->settings->type.') не поддерживается';

        switch ($this->settings->type)
        {
            case 'mysql':

                try
                {
                    if (!$this->mysqli=mysqli_connect($this->settings->host, $this->settings->login, $this->settings->pass))
                    {
                        $this->errors->flag='1';
                        $this->errors->text='Нет возможности установить соединение с БД';
                    }
                    else
                    {
                        if (!$this->mysqli->select_db($this->settings->name))
                        {
                            $this->errors->flag=1;
                            $this->errors->text='Нет возможности выбрать БД "'.$this->settings->name.'"';
                        }
                        else
                        {
                            /* изменение набора символов на заданный */
                            if (!$this->mysqli->set_charset($this->settings->encoding)) {
                                //printf("Ошибка при загрузке набора символов utf8: %s\n", $mysqli->error);
                                $this->warning->flag=1;
                                $this->warning->text='Ошибка выбора кодировки: '.$this->settings->encoding;
                            }
                            else
                            {
                                $this->errors->flag=0;
                                $this->errors->text='Соединение с БД установлено';

                                $this->status->flag=1;
                                $this->status->text='Соединение с БД установлено';

                            }
                        }
                    }
                }
                catch (Throwable $t)
                {
                    $this->errors->flag=1;
                    $this->errors->text='Не удалось установить соединение с БД: '.$t->getMessage();
                }

                break;
        }

        if($this->errors->flag == 0)
        {
            return true;
        }
        return false;

    }

    //выполнение запроса к БД
    public function query($query, $free=true, $cnt=false){

        if($this->status->flag)
        {
            switch ($this->settings->type){

                //обработка запроса к мускулю
                case 'mysql':

                    if(!$res = $this->mysqli->query($query)){
                        $this->warning->flag=1;

                        if(!isset($this->warning->text))
                        {
                            $this->warning->text=array();
                        }

                        $this->warning->text[]='Ошибка выполнения запроса: '.$query;
                        return false;
                    }

                    //есди запрос - select show WITH RECURSIVE
                    if(preg_match("/^select/i", trim($query)) || preg_match("/^show/i", trim($query)) || preg_match("/^with recursive/i", trim($query)))
                    {
                        if(!$cnt){
                            $result = array();
                            while ($row = $res->fetch_assoc())
                            {
                                $result[] = $row;
                            }

                            if($free)
                            {
                                $res->free();
                            }
                            return($result);
                        }

                        $result = $res->num_rows;

                        if($free)
                        {
                            $res->free();
                        }
                        return $result;

                    }

                    /*if($free)
                    {
                        $res->free();
                    }*/

                    //прочие запросы
                    return(true);


                    break;
            }
        }

        return false;
    }
}

//парсим URL запроса
class icePathParser {

    private $cacher;
    private $types;
    public $settings;
    public $errors;
    public $DB;
    public $mtypes = [];
    public $material;

    public function getMTTCache($key){
        return json_decode($this->cacher->get($key), true);
    }

    public function setMTTCache($key, $value, $expire){
        $this->cacher->set($key, json_encode($value), $expire);
    }

    public function getMTTCacheKey(){
        return $this->DB->settings->name.'materialTypesTree';
    }

    public function delMTTCache(){
        return $this->cacher->del($this->getMTTCacheKey());
    }

    public function getMTUCacheKey($id){
        return $this->DB->settings->name.'materialTypeURL_'.$id;
    }

    public function delMTUCache($id){
        return $this->cacher->del($this->getMTUCacheKey($id));
    }

    public function parseURL($call_parts){

        $mtypes = [];
        $mtype = null;
        $material = null;
        $parent = 0;

        //если пусто - то главный раздел
        if(count($call_parts) == 1 && $call_parts[0] == ''){
            $call_parts[0] = 'main';
        }

        //проверяем на существование типа материала
        foreach ($call_parts as $part){

            $finded = false;

            //проверяем существование типа материала
            if(isset($this->types['childs'][$parent]) && is_array($this->types['childs'][$parent]) && count($this->types['childs'][$parent]) > 0){
                foreach ($this->types['childs'][$parent] as $type){
                    if($type['id_char'] == $part){
                        $mtypes[] = $type;
                        $parent = $type['id'];
                        $mtype = $type;
                        $finded = true;
                    }
                }
            }

            //проверяем материал
            if(!$finded){
                $query = 'SELECT id FROM materials WHERE material_type_id = '.$parent.' AND id_char = '."'$part'";
                if($res = $this->DB->query($query)){
                    if(count($res) > 0){
                        $mid = $res[0]['id'];
                        $material = new iceMat($this->DB, $mid);
                        if($material->getRecord()){
                            $finded = true;
                        }
                    }
                }
            }

        }

        $this->mtypes = $mtypes;
        $this->material = $material;

    }

    //функция строит URL типа материала, зная список типов материала и id
    public function getMatTypeURL($id){

        $url='';

        $key = $this->getMTUCacheKey($id);
        if($this->cacher->has($key)){
            return $this->cacher->get($key);
        }

        //проходим массив типов, находим всех предков и строим URL
        if(key_exists($id, $this->types['types'])){
            if($this->types['types'][$id]['id_char'] != 'main'){
                $url = '/'.$this->types['types'][$id]['id_char'];
            }

            if($this->types['types'][$id]['parent_id'] > 0){
                $url = $this->getMatTypeURL($this->types['types'][$id]['parent_id']).$url;
            }

        }

        if($url == ''){
            $url = '/';
        }

        $expired = 1*24*60*60;
        $this->cacher->set($key,$url,$expired);

        return $url;

    }

    public function __construct(iceDB $DB, $types, $settings=null)
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
        $this->types = $types;
    }

}

//авторизация пользователя (php сессия либо токен авторизация)
class iceAuthorize {

    public $autorized;
    public $user;
    public $errors;
    public $secure;

    public function doAuthorize($DB, $login, $pass)
    {
        $user= new iceUser($DB);
        if($user->authorizeUser($pass, $login))
        {
            $this->user=$user;
            $this->autorized=true;
            $this->secure=$user->params['role']['secure'];

            return true;
        }

        $this->errors=array('Не верное сочетание логина и пароля');
        return false;

    }

    public function deAuthorize()
    {
        if(!is_null($this->user))
        {
            $this->user->deauthorizeUser();
        }
        $this->user = null;
        $this->autorized = false;
    }

    public function __construct(iceDB $DB, $login = null, $pass = null)
    {
        $this->autorized = false;
        $this->user = null;
        $this->errors = null;
        $this->secure = false;

        $this->doAuthorize($DB, $login, $pass);
    }

}

//кэширование
class iceCacher {

    private $redis;
    public $key;
    public $value;
    public $expired;
    private $status;
    public $host;
    public $port;

    public function __construct($host='localhost',$port=6379)
    {
        $this->status=0;

        $this->host=$host;
        $this->port=$port;

        //создаем объект yampeeredis
        $this->redis = new redka($this->host, $this->port);
        $this->redis->connect();
        $this->status=1;
    }

    public function has($key)
    {
        if($this->status)
        {
            $this->key=$key;
            return($this->redis->has($key));
        }
        return false;
    }

    public function findKeys($pattern = '*')
    {
        if($this->status)
        {
            return($this->redis->findKeys($pattern));
        }
        return false;
    }

    public function get($key, $decode=false)
    {
        if($this->status)
        {
            $this->key=$key;

            $this->value=$this->redis->get($key);

            if($decode)
            {
                return(json_decode($this->value, true));
            }
            return($this->value);

        }
        return false;
    }

    public function set($key, $value, $expired = null)
    {
        if($this->status)
        {
            $this->key=$key;
            $this->value=$value;
            $this->expired=$expired;

            if($this->redis->set($this->key,$this->value, $this->expired))
            {
                return true;
            }
            return false;
        }
        return false;
    }

    public function del($key)
    {
        if($this->status)
        {
            $this->key=$key;
            if($this->redis->del($this->key))
            {
                return true;
            }
            return false;
        }
        return false;
    }

}

//TODO логирование
class iceLogger {

}

class iceRedirect {

    public function __construct($url,$code = null)
    {
        header('Location: '.$url,true, $code);
        die();
    }

}

//класс создает различные запросы
class iceQueryBuilder {

    public $cols;
    public $params;
    public $table;
    private $DB;

    public function update()
    {
        $out='UPDATE '.$this->table.' SET';

        $sout='';

        foreach ($this->cols as $col)
        {
            if($col['Field'] != 'id' && $col['Field'] != 'date_add')
            {

                if($sout != '')
                {
                    $sout.=',';
                }

                $sout.=' '.$col['Field'].'=';

                //в зависимости от типа колонки, рисуем ковычки на значение
                if(mb_stripos($col['Type'], 'char', 0, 'UTF-8') !== false || mb_stripos($col['Type'], 'text', 0, 'UTF-8') !== false)
                {
                    if(is_null($this->params[$col['Field']]))
                    {
                        $sout.='NULL';
                    }
                    else
                    {
                        $sout.="'".$this->DB->mysqli->real_escape_string($this->params[$col['Field']])."'";
                    }
                }
                else
                {
                    //$sout.=$this->DB->mysqli->real_escape_string($this->params[$col->Field]);
                    if(is_null($this->params[$col['Field']]))
                    {
                        //if($col->Field == 'date_edit')
                        if($col['Field'] == 'date_add' || $col['Field'] == 'date_event' || $col['Field'] == 'date_end' || $col['Field'] == 'date_edit')
                        {
                            $sout.='NOW()';
                        }
                        else
                        {
                            $sout.='NULL';
                        }
                    }
                    else
                    {

                        if(
                            ((mb_strpos($col['Type'], 'int', 0, 'UTF-8') !== false) || (mb_strpos($col['Type'], 'real', 0, 'UTF-8') !== false)) &&
                            (!isset($this->params[$col['Field']]) || is_null($this->params[$col['Field']]) || $this->params[$col['Field']] == '')
                        ) {
                            $sout.='NULL';
                        }
                        elseif ($col['Field'] == 'date_edit'){
                            $sout.='NOW()';
                        }
                        elseif ($col['Field'] == 'date_event' || $col['Field'] == 'date_end'){
                            if($this->params[$col['Field']] == ''){
                                $sout.='NULL';
                            }
                            else {
                                $sout.="'".$this->DB->mysqli->real_escape_string($this->params[$col['Field']])."'";
                            }
                        }
                        else {

                            $sout.=$this->DB->mysqli->real_escape_string($this->params[$col['Field']]);
                        }
                    }
                }

            }
        }

        $out.=$sout.' WHERE id = '.$this->params['id'];

        return $out;
    }

    public function insert()
    {
        $out='INSERT INTO '.$this->table.' (';

        $sout='';
        $pout='';

        //visualijop($this->cols);

        foreach ($this->cols as $col)
        {
            $col=(array)$col;

            if($sout != '')
            {
                $sout.=',';
                $pout.=',';
            }

            $sout.=$col['Field'];

            //вносим значение автоинкремента
            if($col['Extra'] == 'auto_increment')
            {
                $pout.='NULL';
            }
            //вносим обычное значение
            else
            {

                //visualijop($col['Type']);
                //visualijop($this->params[$col['Field']]);

                //в зависимости от типа колонки, рисуем кавычки на значение
                if(mb_stripos($col['Type'], 'char', 0, 'UTF-8') !== false || mb_stripos($col['Type'], 'text', 0, 'UTF-8') !== false || mb_stripos($col['Type'], 'enum', 0, 'UTF-8') !== false)
                {

                    if(is_null($this->params[$col['Field']]))
                    {
                        $pout.='NULL';
                    }
                    else
                    {
                        $pout.="'".$this->DB->mysqli->real_escape_string($this->params[$col['Field']])."'";
                    }
                }
                else
                {
                    if(!isset($this->params[$col['Field']]) || is_null($this->params[$col['Field']]))
                    {
                        if($col['Field'] == 'date_add' || $col['Field'] == 'date_event' || $col['Field'] == 'date_end' || $col['Field'] == 'date_edit')
                        {
                            $pout.='NOW()';
                        }
                        else
                        {
                            $pout.='NULL';
                        }
                    }
                    else
                    {
                        if((mb_strpos($col['Type'], 'int', 0, 'UTF-8') === false) || (mb_strpos($col['Type'], 'real', 0, 'UTF-8') === false) && (!isset($this->params[$col['Field']]) || is_null($this->params[$col['Field']]) || $this->params[$col['Field']] === '')){
                            $this->params[$col['Field']] = 'NULL';
                        }

                        $pout.=$this->DB->mysqli->real_escape_string($this->params[$col['Field']]);
                    }
                }
            }
        }

        $out.=$sout.') VALUES ('.$pout;



        $out.=')';

        //visualijop($out);

        return $out;
    }

    public function __construct(iceDB $DB, $cols, $params, $table)
    {
        $this->params=$params;
        $this->cols=$cols;
        $this->table=$table;
        $this->DB=$DB;
    }

}

//какой-либо объект (таблица БД)
class iObject {

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

//список объектов
class iObjectList {

    public $dbtable;//таблица записей
    public $DB;
    public $conditions;//условия вывода записей
    public $settings;//глобальные настройки
    public $cachetime;//время кэширования результатов запроса
    public $records;//полученные записи
    public $sort;//настройки сортировки
    public $page;//страница
    public $perpage;//кол-во выводимых записей
    public $cacher;
    public $cacheKey;

    //функция для расширения в списках по конкретным объектам
    public function moreQuery(){
        return '';
    }

    public function getCacheKey($query)
    {
        //ключ начало
        $this->cacheKey=$this->DB->settings->name.'_list_'.$this->dbtable.':';

        //ключ-запрос
        if($query != '')
        {
            $this->cacheKey.=$query;
        }

        return($this->cacheKey);

    }

    public function prepareRecords($baseQuery = null, $parentQuery = null, $cnt = false){
        //формирование запроса
        if(is_null($baseQuery)) {
            $query='SELECT dbtable.* ';

            $query.=$this->moreQuery();

            $query.=' FROM '.$this->dbtable.' dbtable
        WHERE 1=1 ';
        }
        else {
            $query = $baseQuery;
        }

        //условия из переданных условий выборки
        //col - поле таблицы
        //value - как ограничиваем
        //type - =/<>/</>/in/not in/is/is not/like/not like/
        //string - true/false
        if(is_array($this->conditions) && count($this->conditions) > 0)
        {
            foreach ($this->conditions as $condition)
            {
                if($condition['string'])
                {
                    $condition['val']="'".$this->DB->mysqli->real_escape_string($condition['val'])."'";
                }

                switch ($condition['type'])
                {
                    case 'NOT IN':
                    case 'IN':
                    case 'LIKE':
                    case 'NOT LIKE':
                        $query.=' AND '.$condition['col'].' '.$condition['type'].' ('.$condition['val'].')';
                        break;

                    default:
                        $query.=' AND '.$condition['col'].' '.$condition['type'].' '.$condition['val'];
                        break;
                }
            }
        }

        if(!$cnt){
            if(!is_array($this->sort))
            {
                $defsort=[
                    'col'=>'id',
                    'sort'=>'DESC'
                ];
                $this->sort[]=$defsort;
            }

            if(is_array($this->sort) && count($this->sort) > 0)
            {
                $query.=' ORDER BY ';

                $i=0;
                foreach ($this->sort as $sort)
                {
                    ++$i;
                    if($i > 1)
                    {
                        $query.=', ';
                    }
                    $query.=$sort['col'].' '.$sort['sort'];
                }
            }

            if(!is_null($this->perpage)){

                $query.=' LIMIT '.$this->perpage;

                if(!is_null($this->page)){
                    $offset = $this->perpage * ($this->page - 1);
                    $query.=' OFFSET '.$offset;
                }

            }

        }

        //если запрос обёрнут в родительский запрос (например для рекурсии)
        if(!is_null($parentQuery)) {
            $query = str_replace('%subQuery%',$query,$parentQuery);
        }

        //visualijop($query);

        if($this->cacher->has($this->getCacheKey($query)))
        {
            $records=$this->cacher->get($this->cacheKey, true);
        }
        else
        {
            if($cnt){
                if($res=$this->DB->query($query, true, true))
                {
                    $records=$res;
                }
                else
                {
                    $records=false;
                }
            }
            else {
                if($res=$this->DB->query($query))
                {
                    $records=$res;
                }
                else
                {
                    $records=false;
                }
            }
        }

        $this->records=$records;
        $this->cacheRecords();

        return $records;
    }

    //получение кол-ва записей
    public function getCnt($baseQuery = null, $parentQuery = null){
        return $this->prepareRecords($baseQuery, $parentQuery, true);
    }

    //получение записей
    public function getRecords($baseQuery = null, $parentQuery = null){
        return $this->prepareRecords($baseQuery, $parentQuery, false);
    }

    //кэширование списка
    public function cacheRecords(){
        if(!is_null($this->cachetime) && $this->cachetime > 0)
        {
            $this->cacher->set($this->cacheKey, json_encode($this->records), $this->cachetime);
        }
    }

    //удаление из кэша списка
    public function uncacheRecords(){
        $this->getCacheKey(null);

        $keys=$this->cacher->findKeys($this->cacheKey.'*');

        if(is_array($keys) && count($keys) > 0)
        {
            foreach ($keys as $key)
            {
                $this->cacher->del($key);
            }
        }
    }

    public function doConstruct(iceDB $DB, $dbtable, $conditions=null, $sort=null, $page=null, $perpage=null, $cachetime=0, $settings=null) {
        $this->DB=$DB;
        $this->dbtable=$dbtable;
        $this->conditions=$conditions;
        $this->settings=$settings;
        $this->cachetime=$cachetime;
        $this->sort=$sort;
        $this->page = $page;
        $this->perpage = $perpage;

        if(is_object($this->settings) && isset($this->settings->cache->host) && isset($this->settings->cache->port))
        {
            $this->cacher = new iceCacher($this->settings->cache->host,$this->settings->cache->port);
        }
        else
        {
            $this->cacher = new iceCacher();
        }
    }

    public function __construct(iceDB $DB, $dbtable, $conditions=null, $sort=null, $page=1, $perpage=20, $cachetime=0, $settings=null)
    {
        $this->doConstruct($DB, $dbtable, $conditions, $sort, $page, $perpage, $cachetime, $settings);
    }

}

class iceMatExtraParams extends iObject {

    //подменяем создание объекта - прописываем железно целевую таблицу
    public function __construct(iceDB $DB, $id=null, $settings=null)
    {
        $this->doConstruct($DB, 'material_extra_params', $id, $settings);
    }

    public function moreQuery(){
        $query=', (SELECT p.name FROM material_types p WHERE p.id = dbtable.value_mtype) value_mtype_name';
        return $query;
    }

    public function afterCreateRecord(){

        //удаляем кэш типа материала
        $mType = new iceMatType($this->DB, $this->params['mtype_id']);
        if($mType->getRecord()){
            $mType->uncacheRecord();
        }

        return true;
    }

}

class iceMatExtraParamsList extends iObjectList {

    public function __construct(iceDB $DB, $conditions=null, $sort=null, $page=1, $perpage=20, $cachetime=0, $settings=null)
    {
        $this->doConstruct($DB, 'material_extra_params', $conditions, $sort, $page, $perpage, $cachetime, $settings);
    }

    public function moreQuery(){
        $query=', (SELECT p.name FROM material_types p WHERE p.id = dbtable.value_mtype) value_mtype_name';
        return $query;
    }

}

class iceMatExtraValues extends iObject {

    public function moreQuery(){
        $query=', (SELECT m.name FROM materials m WHERE m.id = dbtable.value_mat) value_mat_name,
                (SELECT e.name FROM material_extra_params e WHERE e.id = dbtable.param_id) param_name,
                (SELECT e1.value_type FROM material_extra_params e1 WHERE e1.id = dbtable.param_id) value_type,
                (SELECT e2.value_mtype FROM material_extra_params e2 WHERE e2.id = dbtable.param_id) value_mtype';
        return $query;
    }

    //подменяем создание объекта - прописываем железно целевую таблицу
    public function __construct(iceDB $DB, $id=null, $settings=null)
    {
        $this->doConstruct($DB, 'material_extra_values', $id, $settings);
    }

    public function afterCreateRecord(){

        //удаляем кэш типа материала
        if($this->params['value_type'] == 'value_mat'){
            $mat = new iceMat($this->DB, $this->params['value_mat']);
            if($mat->getRecord()){
                $mat->uncacheRecord();
            }
        }

        return true;
    }

}

class iceMatExtraValuesList extends iObjectList {

    public function moreQuery(){
        $query=', (SELECT m.name FROM materials m WHERE m.id = dbtable.value_mat) value_mat_name,
                (SELECT e.name FROM material_extra_params e WHERE e.id = dbtable.param_id) param_name,
                (SELECT e1.value_type FROM material_extra_params e1 WHERE e1.id = dbtable.param_id) value_type,
                (SELECT e2.value_mtype FROM material_extra_params e2 WHERE e2.id = dbtable.param_id) value_mtype';
        return $query;
    }

    public function __construct(iceDB $DB, $conditions=null, $sort=null, $page=1, $perpage=20, $cachetime=0, $settings=null)
    {
        $this->doConstruct($DB, 'material_extra_values', $conditions, $sort, $page, $perpage, $cachetime, $settings);
    }

}

//тип материала
class iceMatType extends iObject {

    public $url;
    public $extraParams;

    //формируем URL типа материала
    public function getURL(){
        $url = '';
        if(isset($this->params['parents'])){
            $parents = $this->params['parents'];
            foreach ($parents as $parent){
                $url.='/'.$parent['id_char'];
            }
        }

        if($this->params['id_char'] != 'main'){
            return $url.'/'.$this->params['id_char'];
        }
        return $url;
    }

    //получаем родительские записи
    public function getParents(){
        $query='WITH RECURSIVE ptypes AS (
	
	SELECT t.* FROM 
	material_types t WHERE t.id = '.$this->id.'

	UNION ALL

	SELECT tt.* FROM
	material_types tt, ptypes p
	WHERE tt.id = p.parent_id
	)

SELECT * FROM ptypes WHERE id <> '.$this->id.' ORDER BY parent_id ASC;';

        if($res=$this->DB->query($query))
        {
            $this->params['parents']=$res;
        }

    }

    //получаем список дочерних разделов
    public function getChilds(){

        $query='WITH RECURSIVE ptypes AS (
	
	SELECT t.* FROM 
	material_types t WHERE t.id = '.$this->id.'

	UNION ALL

	SELECT tt.* FROM
	material_types tt, ptypes p
	WHERE tt.parent_id = p.id

	)

SELECT * FROM ptypes WHERE id <> '.$this->id.';';

        if($res=$this->DB->query($query))
        {
            $this->params['childs']=$res;
        }

    }

    //получаем связанные файлы с типом материалов
    public function getFiles(){

        $query='SELECT f.*, mt.ordernum 
        FROM files f, mtype_files mt 
        WHERE f.id = mt.file_id AND mt.mtype_id = '.$this->id.'
        ORDER BY mt.ordernum ASC, f.date_event DESC';
        if($res=$this->DB->query($query))
        {
            $this->params['files']=$res;
        }

    }

    //расширяем стандартный метод - удаление кэшей у связанных сущностей
    public function fullUncacheRecord(){

        //удаляем кэши родительских типов
        if(isset($this->params['parents']))
        {
            foreach ($this->params['parents'] as $mtype)
            {
                $this->uncacheRecord($mtype['id']);
            }
        }

        //удаляем кэши дочерних типов
        if(isset($this->params['childs']))
        {
            foreach ($this->params['childs'] as $mtype)
            {
                $this->uncacheRecord($mtype['id']);
            }
        }

        //TODO удаляем кэши связаных файлов

    }

    //получение экстра-полей
    public function getExtraParams(){

        $conditions = [];
        $sort = [];

        $conditions[] = [
            'string' => false,
            'type' => '=',
            'col' => 'mtype_id',
            'val' => $this->id
        ];

        $sort[] = ['col' => 'name', 'sort' => 'ASC'];

        $this->extraParams = new iceMatExtraParamsList($this->DB, $conditions, $sort, 1, 100);
        $this->extraParams = $this->extraParams->getRecords();
    }

    //расширяем стандартный метод - к полям БД добавляем связанные данные
    public function fullRecord(){

        //экстра-поля
        $this->getExtraParams();

        //дерево родительских типов материалов
        $this->getParents();

        //дерево дочерних типов материалов
        $this->getChilds();

        //связанные файлы с типом материала
        $this->getFiles();

        //полный URL
        $this->url = $this->getURL();
    }

    //TODO расширяем стандартный метод кэширования - удаление кэшей связанных файлов и типов материалов

    //подменяем создание объекта - прописываем железно целевую таблицу
    public function __construct(iceDB $DB, $id=null, $settings=null)
    {
        $this->doConstruct($DB, 'material_types', $id, $settings);
    }

}

//список типов материалов
class iceMatTypeList extends iObjectList {

    //дерево
    public function getRecordsTree($mode = 'all') {

        //режим отображения
        //all - все дерево
        //active - только видимые типы
        //mat - только видимые с активными материалами внутри
        switch ($mode) {
            case 'all':
                $this->conditions = null;
                break;
            case 'active':
                $this->conditions = [
                    0 => [
                        'string' => false,
                        'type' => '=',
                        'col' => 'sitemenu',
                        'val' => 1
                    ]
                ];
                break;
            case 'mat':

                //в типах материала проверки на язык не идет, так как для скорости работы, языки вписываются в поля таблицы...
                // Но - в данном случае проверяем материал, поэтому смотрим, есть ли язык в кондициях запроса
                $langQuery = '';
                if(is_array($this->conditions) && count($this->conditions) > 0) {
                    foreach ($this->conditions as $condition) {
                        if($condition['col'] == 'language'){
                            $langQuery = ' AND language = '.$condition['val'];
                        }
                    }
                }

                $this->conditions = [
                    0 => [
                        'string' => false,
                        'type' => '=',
                        'col' => 'sitemenu',
                        'val' => 1
                    ],
                    1 => [
                        'string' => false,
                        'type' => 'IN',
                        'col' => 'id',
                        'val' => 'SELECT material_type_id FROM materials WHERE status_id = 1'.$langQuery
                    ]
                ];
                break;
        }

        $this->sort = [
            0 => [
                'col' => 'parent_id',
                'sort' => 'ASC'
            ],
            1 => [
                'col' => 'ordernum',
                'sort' => 'ASC'
            ],
            2 => [
                'col' => 'id',
                'sort' => 'ASC'
            ]
        ];

        if($rows = $this->getRecords()) {

            if(count($rows) > 0) {

                //преобразуем массив записей в дерево записей
                $tree = [];
                foreach ($rows as $row) {

                    if(is_null($row['parent_id'])){
                        $row['parent_id'] = 'null';
                    }

                    $tree['types'][$row['id']] = $row;
                    $tree['childs'][$row['parent_id']][$row['id']] = $row;
                }
                return $tree;
            }

        }

        return false;
    }

    public function moreQuery(){
        $query=', (SELECT p.name FROM material_types p WHERE p.id = dbtable.parent_id) parent_name, 
        (SELECT ti.filename FROM templates ti WHERE ti.id = dbtable.template_item) template_item_name,
        (SELECT tl.filename FROM templates tl WHERE tl.id = dbtable.template_list) template_list_name,
        (SELECT ta.filename FROM templates ta WHERE ta.id = dbtable.template_admin) template_admin_name';
        return $query;
    }

    public function __construct(iceDB $DB, $conditions=null, $sort=null, $page=1, $perpage=20, $cachetime=0, $settings=null)
    {
        $this->doConstruct($DB, 'material_types', $conditions, $sort, $page, $perpage, $cachetime, $settings);
    }

}

//шаблон типа материала
class iceTemplate extends iObject {
    //подменяем создание объекта - прописываем железно целевую таблицу
    public function __construct(iceDB $DB, $id=null, $settings=null)
    {
        $this->doConstruct($DB, 'templates', $id, $settings);
    }

    //получение название колонки в типе материала в зависимости от типа шаблона
    public function getColName() {
        $colName = false;
        switch ($this->params['type']){
            case '1':
                $colName = 'template_item';
                break;
            case '2':
                $colName = 'template_list';
                break;
            case '3':
                $colName = 'template_admin';
                break;
        }
        return $colName;
    }

    //получение названия типа
    public function getTypeName() {

        $colName = false;
        switch ($this->params['type']){
            case '1':
                $colName = 'Шаблон материала';
                break;
            case '2':
                $colName = 'Шаблон списка материалов';
                break;
            case '3':
                $colName = 'Шаблон формы редактирования материала';
                break;
        }

        $this->params['type_name']=$colName;

    }

    //получение типов материалов шаблона
    public function getMatTypes() {

        $query = 'SELECT * FROM material_types WHERE '.$this->getColName().' = '.$this->params['id'];

        if($res=$this->DB->query($query))
        {
            $this->params['mat_types']=$res;
        }
        else {
            $this->params['mat_types'] = [];
        }
    }

    //расширяем стандартный метод - к полям БД добавляем связанные данные
    public function fullRecord(){
        $this->getMatTypes();
        $this->getTypeName();
    }

}

//список шаблонов
class iceTemplateList extends iObjectList {
    public function __construct(iceDB $DB, $conditions=null, $sort=null, $page=1, $perpage=20, $cachetime=0, $settings=null)
    {
        $this->doConstruct($DB, 'templates', $conditions, $sort, $page, $perpage, $cachetime, $settings);
    }
}

//кэш изображения
class iceImageCache extends iObject {
    //подменяем создание объекта - прописываем железно целевую таблицу
    public function __construct(iceDB $DB, $id=null, $settings=null)
    {
        $this->doConstruct($DB, 'image_caches', $id, $settings);
    }

    //создание новой записи
    public function createRecord($params = null){

        if(!is_null($params) && is_array($params))
        {
            $this->params = $params;
        }

        //формируем запрос для создания записи
        $qbuilder=new iceQueryBuilder($this->DB, $this->cols, $this->params, 'image_caches');
        $query=$qbuilder->insert();

        if($res=$this->DB->query($query))
        {
            //получаем и возвращаем идентификатор записи
            return true;
        }
        return false;

    }

    public function getWatermarkData() {

        if($this->params['watermark'] == 0){
            $this->params['watermark_data']=['name' => 'нет'];
        }
        else {
            $query = 'SELECT name FROM files WHERE id = '.$this->params['watermark'];
            if($res=$this->DB->query($query))
            {
                $this->params['watermark_data']=$res[0];
            }
        }
    }

    //расширяем стиандартный метод - к полям БД добавляем связанные данные
    public function fullRecord(){
        $this->getWatermarkData();
    }
}

//список кэшей изображений
class iceImageCacheList extends iObjectList {
    public function __construct(iceDB $DB, $conditions=null, $sort=null, $page=1, $perpage=20, $cachetime=0, $settings=null)
    {
        $this->doConstruct($DB, 'image_caches', $conditions, $sort, $page, $perpage, $cachetime, $settings);
    }

    public function moreQuery(){
        $query=', (SELECT fi.name FROM files fi WHERE fi.id = dbtable.watermark) watermark_name';
        return $query;
    }

    //получение записей
    public function getRecords($baseQuery = null, $parentQuery = null){

        //формирование запроса
        if(is_null($baseQuery)) {
            $query='SELECT dbtable.* ';

            $query.=$this->moreQuery();

            $query.=' FROM '.$this->dbtable.' dbtable
        WHERE 1=1 ';
        }
        else {
            $query = $baseQuery;
        }

        //условия из переданных условий выборки
        //col - поле таблицы
        //value - как ограничиваем
        //type - =/<>/</>/in/not in/is/is not/like/not like/
        //string - true/false
        if(is_array($this->conditions) && count($this->conditions) > 0)
        {
            foreach ($this->conditions as $condition)
            {
                if($condition['string'])
                {
                    $condition['val']="'".$this->DB->mysqli->real_escape_string($condition['val'])."'";
                }

                switch ($condition['type'])
                {
                    case 'NOT IN':
                    case 'IN':
                    case 'LIKE':
                    case 'NOT LIKE':
                        $query.=' AND '.$condition['col'].' '.$condition['type'].' ('.$condition['val'].')';
                        break;

                    default:
                        $query.=' AND '.$condition['col'].' '.$condition['type'].' '.$condition['val'];
                        break;
                }
            }
        }

        if(!is_array($this->sort))
        {
            $defsort=[
                'col'=>'width',
                'sort'=>'ASC'
            ];
            $this->sort[]=$defsort;
        }

        if(is_array($this->sort) && count($this->sort) > 0)
        {
            $query.=' ORDER BY ';

            $i=0;
            foreach ($this->sort as $sort)
            {
                ++$i;
                if($i > 1)
                {
                    $query.=', ';
                }
                $query.=$sort['col'].' '.$sort['sort'];
            }
        }

        //если запрос обёрнут в родительский запрос (например для рекурсии)
        if(!is_null($parentQuery)) {
            $query = str_replace('%subQuery%',$query,$parentQuery);
        }

        //visualijop($query);

        if($this->cacher->has($this->getCacheKey($query)))
        {
            $records=$this->cacher->get($this->cacheKey, true);
        }
        else
        {
            if($res=$this->DB->query($query))
            {
                $records=$res;
            }
            else
            {
                $records=false;
            }
        }

        $this->records=$records;
        $this->cacheRecords();

        return $records;
    }
}

class iceWidget {

    public $name;
    public $DB;
    public $params;
    public $settings;
    public $errors = [];
    public $styles;
    public $jscripts;

    public function __construct(iceDB $DB, $name, $settings=null)
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

//TODO пользователь
class iceUser extends iObject {


    public function registerUser(array $params)
    {
        //проверяем необходимые параметры
        if(isset($params['login_email']) && isset($params['password_input']))
        {

            //проверяем существующих пользюков с такими email и tel
            $query='SELECT count(id) cid FROM users WHERE login_email = '."'".$params['login_email']."'";

            if(!is_null($params['login_phone']))
            {
                $query.= ' OR login_phone = '."'".$params['login_phone']."'";
            }

            if($res=$this->DB->query($query))
            {
                if(count($res) > 0)
                {
                    if($res[0]['cid'] > 0)
                    {
                        $this->errors[]='Пользователь с таким email или телефоном уже существует';
                        return false;
                    }
                }
            }

            //генерируем хэш пароля
            $params['password']=password_hash($params['password_input'], PASSWORD_DEFAULT);

            //пробуем сохранить пользюка
            if($this->createRecord($params))
            {
                $this->errors[]='Не удалось зарегистрировать пользователя';
                return true;
            }

        }

        return false;

    }

    public function authorizeUser($pass, $email)
    {
        if((is_null($pass) || $pass == '') && (is_null($email) || $email == ''))
        {
            //еси еть php сессия
            if(isset($_SESSION['authorize']))
            {
                if($_SESSION['authorize'])
                {
                    $this->id=$_SESSION['authorize'];
                    $this->getRecord($this->id);
                    return($this->id);
                }
            }
            else
            {
                $_SESSION['authorize']=false;
            }
        }
        else
        {
            //проверяем запись пользователя
            $query='SELECT * FROM users WHERE login_email = \''.$this->DB->mysqli->real_escape_string($email).'\'';

            //visualijop($query);

            if($res = $this->DB->query($query))
            {
                if(count($res) > 0)
                {
                    $user=$res[0];

                    //проверяем пароль
                    if($ver=password_verify($pass, $user['password']))
                    {
                        $this->id=$user['id'];
                        $this->getRecord($this->id);

                        $_SESSION['authorize']=$this->id;

                        return($this->id);
                    }
                    return false;
                }
            }
            return false;
        }
    }

    public function deauthorizeUser()
    {
        unset($_SESSION['authorize']);
        $this->id = null;
    }

    public function getRole(){
        $query='SELECT * from user_roles WHERE id = '.$this->params['user_role'];
        if($res=$this->DB->query($query))
        {
            $this->params['role']=$res[0];
        }
    }

    //метод для переработки в конкретном объекте
    public function fullRecord(){
        $this->getRole();
    }

    public function __construct(iceDB $DB, $id=null, $settings=null)
    {
        $this->doConstruct($DB, 'users', $id, $settings);
    }
}

//TODO список пользователей
class iceUserList extends iObjectList {
}

//TODO материал
class iceMat extends iObject {

    public $files = [];
    public $extraValues = [];

    public static function price($price){

        return number_format($price, 2, '<small>,', '&nbsp;').'&nbsp;₽</small>';

    }

    public function getExtraValues(){

        $conditions = [];
        $sort = [];

        $conditions[] = [
            'string' => false,
            'type' => '=',
            'col' => 'material_id',
            'val' => $this->id
        ];

        $sort[] = ['col' => 'param_name', 'sort' => 'ASC'];

        $extraValues = new iceMatExtraValuesList($this->DB, $conditions, $sort, 1, 100);
        $this->extraValues = $extraValues->getRecords();

    }

    public function getMatTypeData(){
        $query='SELECT name, template_list, template_item, template_admin FROM material_types WHERE id = '.$this->params['material_type_id'];
        if($res=$this->DB->query($query))
        {
            $this->params['material_type_name']=$res[0]['name'];

            //проверяем идентификаторы шаблонов и устанавливаем дефолтные значения
            if($res[0]['template_list'] == ''){
                $res[0]['template_list'] = 2; //по дефолту шаблон списка новостей
            }
            if($res[0]['template_item'] == ''){
                $res[0]['template_item'] = 3; //по дефолту шаблон детализации новости
            }
            if($res[0]['template_admin'] == ''){
                $res[0]['template_admin'] = 4; //по дефолту шаблон редактирования новости
            }

            $listTemplate = new iceTemplate($this->DB, $res[0]['template_list']);
            $listTemplate->getRecord($res[0]['template_list']);

            $itemTemplate = new iceTemplate($this->DB, $res[0]['template_item']);
            $itemTemplate->getRecord($res[0]['template_item']);

            $adminTemplate = new iceTemplate($this->DB, $res[0]['template_admin']);
            $adminTemplate->getRecord($res[0]['template_admin']);

            //заносим шаблоны в свойства материала
            $this->params['templates'] = [
                'template_list' => [
                    'id' => $res[0]['template_list'],
                    'name' => $listTemplate->params['name'],
                    'filename' => $listTemplate->params['filename']
                ],
                'template_item' => [
                    'id' => $res[0]['template_item'],
                    'name' => $itemTemplate->params['name'],
                    'filename' => $itemTemplate->params['filename']
                ],
                'template_admin' => [
                    'id' => $res[0]['template_admin'],
                    'name' => $adminTemplate->params['name'],
                    'filename' => $adminTemplate->params['filename']
                ]
            ];
        }
    }
    public function getUserName(){
        $query='SELECT full_name FROM users WHERE id = '.$this->params['user_id'];
        if($res=$this->DB->query($query))
        {
            $this->params['user_name']=$res[0]['full_name'];
        }
    }

    public function getFiles(){

        //получение списка файлов материала (прямой запрос, так как есть обязательная связка и нам надо поле индивидуальной сортировки)
        $query = 'SELECT f.*, m.ordernum 
        FROM files f, material_files m 
        WHERE f.id = m.file_id AND m.material_id = '.$this->params['id'].'
        ORDER BY m.ordernum ASC, f.id ASC';

        if($res=$this->DB->query($query))
        {
            if(count($res) > 0){
                $this->files = $res;
            }
        }

    }

    //расширяем стиандартный метод - к полям БД добавляем связанные данные
    public function fullRecord(){
        $this->getMatTypeData();
        $this->getUserName();
        $this->getFiles();
        $this->getExtraValues();
    }

    //функция дает иконку статуса материала
    public static function statusIcon($id){
        $id = (int)$id;
        switch ($id){
            case 0:
                return '<i class="material-icons md-16 md-grey">visibility_off</i>';
                break;
            case 1:
                return '<i class="material-icons md-16 md-green">visibility</i>';
                break;
            case 2:
                return '<i class="material-icons md-16 md-red">delete</i>';
                break;
        }
    }

    //функция дает название статуса материала TODO языки
    public static function statusName($id){
        $id = (int)$id;
        switch ($id){
            case 0:
                return 'скрытый';
                break;
            case 1:
                return 'актывный';
                break;
            case 2:
                return 'архивный';
                break;
        }
    }

    //подменяем создание объекта - прописываем железно целевую таблицу
    public function __construct(iceDB $DB, $id=null, $settings=null)
    {
        $this->doConstruct($DB, 'materials', $id, $settings);
    }

    //формирование списка параметров для редактирования/добавления запись из $this->values
    public function paramsFromValues($values) {

        $params = $this->params;
        foreach ($this->cols as $col)
        {
            $valueName = $col['Field'];
            if($valueName != 'id') {
                if(isset($values->$valueName) && $values->$valueName !== ''){
                    $params[$col['Field']] = $values->$valueName;
                }
                //обьявляем дефолтные значения некоторых переменных
                else {
                    switch ($valueName){
                        case 'id_char':
                            $params[$col['Field']] = iceTextFunctions::makeCharId($values->name);
                            break;
                        default:
                            if(!isset($params[$col['Field']])){
                                $params[$col['Field']] = null;
                            }
                            break;
                    }
                }
            }
        }

        return $params;

    }

}

//TODO список материалов
class iceMatList extends iObjectList {
    public function __construct(iceDB $DB, $conditions=null, $sort=null, $page=1, $perpage=20, $cachetime=0, $settings=null)
    {
        $this->doConstruct($DB, 'materials', $conditions, $sort, $page, $perpage, $cachetime, $settings);
    }

    public function moreQuery(){
        $query=',   (SELECT mt.name FROM material_types mt WHERE mt.id = dbtable.material_type_id) material_type_name,
                    (SELECT fm.file_id FROM material_files fm, files f 
                        WHERE f.filetype = \'image\' AND f.id = fm.file_id AND fm.material_id = dbtable.id 
                        ORDER BY fm.ordernum ASC, f.id ASC
                        LIMIT 1) favicon
        ';
        return $query;
    }

}

//Список языков сайта
class iceLanguageList extends iObjectList {

    public function __construct(iceDB $DB, $conditions=null, $sort=null, $page=1, $perpage=20, $cachetime=0, $settings=null)
    {
        $this->doConstruct($DB, 'languages', $conditions, $sort, $page, $perpage, $cachetime, $settings);
    }

}

//файл
class iceFile extends iObject {

    //подменяем создание объекта - прописываем железно целевую таблицу
    public function __construct(iceDB $DB, $id=null, $settings=null)
    {
        $this->doConstruct($DB, 'files', $id, $settings);
    }

    public static function formatIcon($DB, $file, $link = false, $ifArray = false){

        $fileObj = new iceFile($DB, $file['id']);
        $fileObj->getRecord();
        $file = $fileObj->params;

        if($file['filetype'] == 'image'){
            //visualijop($file, $fileObj);
            $icon = '<img src="'.$fileObj->getFileCacheUrl(48,48).'" />';
        }
        else {
            $icon = '<i class="material-icons md-48 md-dark">insert_drive_file</i>';
        }


        //вешаем на иконку ссылку
        if($link){
            if($file['filetype'] == 'image'){
                $icon='<a href="'.$fileObj->getFileCacheUrl(800,0).'" data-toggle="lightbox">'.$icon.'</a>';
            }
            else {
                $icon = '<a href="'.$fileObj->getFileUrl().'">'.$icon.'</a>';
            }
        }

        if($ifArray){
            return ['icon' => $icon, 'link' => $fileObj->getFileCacheUrl(800,0)];
        }

        return $icon;

    }

    public static function formateSize($size){

        if($size < 1024){
            $size.='б';
        }
        elseif ($size < (1024*1024)){
            $size = '<strong>'.round($size/1024,1).'</strong>Кб';
        }
        elseif ($size < (1024*1024*1024)){
            $size = '<strong>'.round($size/(1024*1024),1).'</strong>Мб';
        }
        else {
            $size = '<strong>'.round($size/(1024*1024*1024),1).'</strong>Гб';
        }

        return $size;

    }

    public static function getFileExtension($filename)
    {
        $path_info = pathinfo($filename);
        if(!isset($path_info['extension']))
        {
            return('');
        }
        return $path_info['extension'];
    }

    public function upload($paramName, $type = 'file', $private = false, $userId, $materialConnect = false){

        if($paramName != ''){
            if(isset($_FILES[$paramName])){

                $tmp_name = $_FILES[$paramName]["tmp_name"];
                $name = $_FILES[$paramName]['name'];
                $extension = iceFile::getFileExtension($name);
                $size = $_FILES[$paramName]['size'];
                list($width, $height, $imgtype, $attr) = getimagesize($tmp_name);

                if(is_null($imgtype)) {
                    $imgtype = false;
                }

                if($type == 'image' && !$imgtype){
                    $this->errors[] = 'Переданный файл не является изображением, либо его формат не поддерживается';
                    return false;
                }

                if($type == 'auto' && $imgtype){
                    $type = 'image';
                }
                elseif ($type == 'auto'){
                    $type = 'file';
                }

                //если тип - image, проверяем расширение
                if($type == 'image'){

                    switch ($imgtype){
                        case '2':
                            $extension = 'jpg';
                            break;
                        case '3':
                            $extension = 'png';
                            break;
                        case '1':
                            $extension = 'gif';
                            break;
                        default:
                            $this->errors[] = 'Переданный файл не является изображением, либо его формат не поддерживается';
                            return false;
                            break;
                    }

                }

                $url = '/files/';
                if($private){
                    $url.='private/';
                }

                $url.=date('Ym').'/';
                $dirpatch = $this->settings->path.$url;
                //visualijop($dirpatch);


                if(!is_dir($dirpatch)){
                    mkdir($dirpatch, 0750);
                }

                $this->paramsFromPost();

                if($private){
                    $privateInt = 1;
                }
                else {
                    $privateInt = 2;
                }

                //создаем запись в бд
                $this->params = [
                    'id' => null,
                    //'name' => '',
                    'filename' => $name,
                    //'anons' => '',
                    //'date_add' => '',
                    //'date_edit' => '',
                    'user_id' => $userId,
                    'status_id' => 2,
                    'filetype' => $type,
                    'extension' => $extension,
                    'url' => $url,
                    'size' => $size,
                    'image_width' => $width,
                    'image_height' => $height,
                    'private' => $privateInt
                ];

                if(!isset($this->params['name'])){
                    $this->params['name'] = $name;
                }
                if(!isset($this->params['anons'])){
                    $this->params['anons'] = '';
                }

                //пробуем сделать запись в БД
                if($id = $this->createRecord()){

                    //физически копируем файл
                    $filename = $dirpatch.$id;
                    if($extension != ''){
                        $filename.='.'.$extension;
                    }

                    if(move_uploaded_file($tmp_name, $filename)){

                        //создаём кэши изображений
                        $this->createImageCaches();

                        //если все хорошо - связываем файл с материалом
                        if($materialConnect){
                            $query = 'INSERT INTO material_files(file_id, material_id, ordernum) VALUES('.$id.', '.$materialConnect.', NULL)';
                            $res = $this->DB->query($query);
                        }

                        return $id;
                    }

                }
                else{
                    $this->errors[] = 'Ошибка сохранения записи о файле';
                    return false;
                }

                //visualijop($tmp_name, $extension, $name, $size, $width, $height, $imgtype, $attr);
                //visualijop($this->settings);


            }
            else {
                $this->errors[] = 'Файл не передан';
                return false;
            }
        }
        $this->errors[] = 'Нет файловой переменной';
        return false;

    }


    public function getFilePath(){

        if($this->params['extension'] != ''){
            return $this->settings->path.$this->params['url'].$this->id.'.'.$this->params['extension'];
        }
        return $this->settings->path.$this->params['url'].$this->id;

    }

    public function getFileCachePath($x, $y){

        $folder=$x.'x'.$y;

        $dirpatch = $this->settings->path.$this->params['url'].$folder.'/';
        //visualijop($dirpatch);

        if(!is_dir($dirpatch)){
            mkdir($dirpatch, 0750);
        }

        if($this->params['extension'] != ''){
            return $this->settings->path.$this->params['url'].$folder.'/'.$this->id.'.'.$this->params['extension'];
        }
        return $this->settings->path.$this->params['url'].$folder.'/'.$this->id;

    }

    public function getFileCacheUrl($x, $y){
        $folder=$x.'x'.$y;
        $dirpatch = $this->params['url'].$folder.'/';
        if($this->params['extension'] != ''){
            return $dirpatch.'/'.$this->id.'.'.$this->params['extension'];
        }
        return $dirpatch.'/'.$this->id;
    }

    public function getFileUrl(){
        $dirpatch = $this->params['url'];
        if($this->params['extension'] != ''){
            return $dirpatch.'/'.$this->id.'.'.$this->params['extension'];
        }
        return $dirpatch.'/'.$this->id;
    }

    public function SaveImageSize($newx, $newy, $extension, $crop = 0, $watermark = 0, $wx = 0, $wy = 0)
    {

        $from = $this->getFilePath(); // файл оригинал
        $to = $this->getFileCachePath($newx, $newy); // файл кэша

        //определение размеров
        $originalx = $this->params['image_width'];
        $originaly = $this->params['image_height'];
        if($newx == 0){
            $newx = round($originalx*$newy/$originaly);
        }elseif ($newy == 0){
            $newy = round($originaly*$newx/$originalx);
        }


        //echo $from;
        switch ($extension) {
            case 'jpg':
                $im = imagecreatefromjpeg($from);
                $im1 = imagecreatetruecolor($newx, $newy);
                break;
            case 'jpeg':
                $im = imagecreatefromjpeg($from);
                $im1 = imagecreatetruecolor($newx, $newy);
                break;
            case 'png':
                $im = imagecreatefrompng($from);

                $im1 = imagecreatetruecolor($newx, $newy);
                imagealphablending($im1, false);
                imagesavealpha($im1, true);
                break;
            case 'gif':
                $im = imagecreatefromgif($from);
                $im1 = imagecreatetruecolor($newx, $newy);
                break;
        }

        if ($crop == 0) {
            imagecopyresampled($im1, $im, 0, 0, 0, 0, $newx, $newy, imagesx($im), imagesy($im));
        } else {

            //просчитываем с какой стороны обрезать (формируем переменные для обрезания)
            $sootn1 = $newx / $newy;
            $sootn2 = imagesx($im) / imagesy($im);

            //режим по x
            if ($sootn1 >= $sootn2) {
                $ix = imagesx($im);
                $iy = round($newy * imagesx($im) / $newx);
            } else {

                $iy = imagesy($im);
                $ix = round($newx * imagesy($im) / $newy);

            }

            //die($ix.' - '.$iy.' --- '.imagesx($im).' - '.imagesy($im));

            //смещения
            $startx = (int)((imagesx($im) - $ix) / 2);
            //$starty=(int)((imagesy($im)-$iy)/2);
            $starty = 0;

            imagecopyresampled($im1, $im, 0, 0, $startx, $starty, $newx, $newy, $ix, $iy);

        }

        //наносим watermark
        if($watermark > 0){

            $wimg = new iceFile($this->DB, $watermark);
            $stamp = imagecreatefrompng($wimg->getFilePath());

            $sx = imagesx($stamp);
            $sy = imagesy($stamp);

            imagecopy($im1, $stamp, imagesx($im1) - $sx - $wx, imagesy($im1) - $sy - $wy, 0, 0, imagesx($stamp), imagesy($stamp));

        }

        imagejpeg($im1, $to, 100);

    }

    public function createImageCache($cache){

        $nw = $cache['width'];
        $nh = $cache['height'];
        $watermark = $cache['watermark'];
        $wx = $cache['w_x'];
        $wy = $cache['w_y'];

        $this->SaveImageSize($nw, $nh, $this->params['extension'], 1, $watermark, $wx, $wy);

    }

    public function createImageCaches(){

        if(isset($this->params['filetype']) && $this->params['filetype'] == 'image'){

            //список кэшей для изображения
            $imageCaches = new iceImageCacheList($this->DB, null, null, 1, 1000);
            $imageCaches = $imageCaches->getRecords();

            if(is_array($imageCaches) && count($imageCaches) > 0){

                foreach ($imageCaches as $cache){
                    $this->createImageCache($cache);
                }

            }

        }

        return false;
    }

}

//список файлов
class iceFileList extends iObjectList {

    public function __construct(iceDB $DB, $conditions=null, $sort=null, $page=1, $perpage=20, $cachetime=0, $settings=null)
    {
        $this->doConstruct($DB, 'files', $conditions, $sort, $page, $perpage, $cachetime, $settings);
    }

}

//TODO файл изображение
class icePhotoFile extends iceFile {

}

//TODO список файлов изображений
class icePhotoFileList extends iceFileList {

}

//различные преобразования строк
class iceTextFunctions {

    public $text;

    public static function mb_transliterate($string)
    {
        $table = array(
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D',
            'Е' => 'E', 'Ё' => 'YO', 'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I',
            'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
            'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C', 'Ч' => 'CH',
            'Ш' => 'SH', 'Щ' => 'SCH', 'Ь' => '', 'Ы' => 'Y', 'Ъ' => '',
            'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA',

            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
            'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
            'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
            'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'sch', 'ь' => '', 'ы' => 'y', 'ъ' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
        );

        $output = str_replace(
            array_keys($table),
            array_values($table),$string
        );

        // таеже те символы что неизвестны
        $output = preg_replace('/[^-a-z0-9._\[\]\'"]/i', ' ', $output);
        $output = preg_replace('/ +/', '-', $output);

        return $output;
    }

    //формирование char_id из текста
    public static function makeCharId($text){

        $id_char = iceTextFunctions::mb_transliterate($text);
        $id_char = str_replace('"', '-quot-', $id_char);
        $id_char = trim(preg_replace('/-{2,}/', '-', $id_char), '-');
        $id_char = str_replace(' ','_',$id_char);

        return $id_char;

    }

    //проверка сложности пароля
    public function checkSecurePass()
    {
        if(mb_strlen($this->text, 'utf8') < 6)
        {
            return false;
        }

        if (preg_match("/([0-9]+)/", $this->text))
        {
            if (preg_match("/([a-zA-Z]+)/", $this->text))
            {
                return true;
            }
            return false;
        }
        return false;
    }

    //проверка e-mail адреса по маске
    public function checkEmail()
    {
        $pattern = '/^[a-z0-9_.\-]+@[a-z0-9_.\-]+\.[a-z0-9_.\-]+$/i';
        $res = preg_match($pattern, $this->text);
        return (bool)$res;
    }

    public function __construct($text=null)
    {
        $this->text=$text;
    }

}

//TODO класс - переводчик текста с русского языка на другой
class iceTranslator {

    public $text;
    public $language;
    private $DB;
    public $result;

    public function translate()
    {

    }

    public function __construct(iceDB $DB, $text='', $language=2)
    {
        $this->text=$text;
        $this->language=$language;
        $this->DB = $DB;
        $this->result='';

        if($text != '')
        {
            $this->translate();
        }
    }

}

//определяет с каким модулем работать и настройки модуля
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

class iceHeaderBuilder {
    public $headers;

    public function printHeaders()
    {
        if(is_array($this->headers) && count($this->headers) > 0)
        {
            foreach ($this->headers as $header)
            {
                header($header);
            }
        }
    }

    public function standartHeaders()
    {
        $this->headers=array(
            'X-Powered-By: newtons',
            'Server: Summit',
            'expires: mon, 26 jul 2000 05:00:00 GMT',
            'cache-control: no-cache, must-revalidate',
            'pragma: no-cache',
            'last-modified: '.gmdate('d, d m y h:i:s').' GMT',
            'X-Frame-Options: SAMEORIGIN',
            'X-XSS-Protection: 1; mode=block;',
            'X-Content-Type-Options: nosniff'
        );
    }

    public function addHeader(string $str)
    {
        if(!is_array($this->headers))
        {
            $this->headers=array();
        }
        $this->headers[]=$str;
    }

    public function addHeaders(array $arr)
    {
        if(!is_array($this->headers))
        {
            $this->headers=array();
        }
        foreach ($arr as $str)
        {
            $this->addHeader($str);
        }
    }

    public function __construct()
    {

    }
}

//сохраняем, выводим список CSS стилей, для печати в шаблоне
class iceStylesBuilder {

    public $styles;

    public function printStyles()
    {
        if(is_array($this->styles) && count($this->styles) > 0)
        {
            foreach ($this->styles as $style)
            {
                echo '<link rel="stylesheet" href="'.$style.'">';
            }
        }
    }

    public function addStyle(string $str)
    {
        if(!is_array($this->styles))
        {
            $this->styles=array();
        }
        $this->styles[]=$str;
    }

    public function addStyles(array $arr)
    {
        if(!is_array($this->styles))
        {
            $this->styles=array();
        }
        if(!is_null($arr) && is_array($arr) && count($arr) > 0){
            foreach ($arr as $str)
            {
                $this->addStyle($str);
            }
        }
    }

    public function __construct()
    {
    }

}

//сохраняем, выводим список JS скриптов, для печати в шаблоне
class iceJScriptBuilder {

    public $scripts;

    public function printScripts()
    {
        if(is_array($this->scripts) && count($this->scripts) > 0)
        {
            foreach ($this->scripts as $script)
            {
                echo '<script src="'.$script.'"></script>';
            }
        }
    }

    public function addScript(string $str)
    {
        if(!is_array($this->scripts))
        {
            $this->scripts=array();
        }
        $this->scripts[]=$str;
    }

    public function addScripts($arr)
    {
        if(!is_array($this->scripts))
        {
            $this->scripts=array();
        }
        if(!is_null($arr) && is_array($arr) && count($arr) > 0){
            foreach ($arr as $str)
            {
                $this->addScript($str);
            }
        }
    }

    public function __construct()
    {
    }

}

//класс для получения REQUEST значений
class iceRequestValues {

    public $values;

    public function getRequestValue($valuename, $mode = 0)
    {
        if ($valuename != '')
        {
            if (isset($_REQUEST[$valuename]))
            {
                if(is_array($_REQUEST[$valuename]))
                {
                    $this->values->$valuename = array();
                    foreach ($_REQUEST[$valuename] as $val)
                    {
                        if($mode == 0)
                        {
                            $this->values->$valuename[] = htmlspecialchars($val, ENT_QUOTES);
                        }
                        else
                        {
                            $this->values->$valuename[] = $val;
                        }
                    }
                }
                else
                {
                    if($mode == 0)
                    {
                        $this->values->$valuename = htmlspecialchars($_REQUEST[$valuename], ENT_QUOTES);
                    }
                    else
                    {
                        $this->values->$valuename = $_REQUEST[$valuename];
                    }
                }
            }
            else
            {
                $this->values->$valuename = '';
            }
        }
    }

    public function getRequestValues($valuesnames, $mode = 0)
    {
        if (is_array($valuesnames))
        {
            foreach($valuesnames as $valuename)
                $this->getRequestValue($valuename, $mode);
        }
        else
        {
            $this->getRequestValue($valuesnames, $mode);
        }
    }

    public function __construct(stdClass $values)
    {
        $this->values = $values;
    }

    public function returnValues()
    {
        return($this->values);
    }

}

class iceFlashVars {
    public $vars = [];

    public function __construct()
    {
        if(isset($_SESSION['flashVars'])){
            $this->vars = $_SESSION['flashVars'];
        }
    }

    public function set($name,$value){
        $this->vars[$name]=$value;
        $_SESSION['flashVars'] = $this->vars;
    }

    public function get($name){
        if(isset($this->vars[$name])){
            $value = $this->vars[$name];
            unset($this->vars[$name]);
            unset($_SESSION['flashVars']);
            $_SESSION['flashVars'] = $this->vars;
            return $value;
        }
        return false;
    }

}

//TODO основной класс - выполняет все что надо и выводит, что получилось
class iceRender {

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
    public $path_info;//результат разбора строки
    public $authorize;//авторизация пользюка
    public $values;//переменные
    public $materialTypes;//типы материалов
    public $textfunctions;
    public $version;
    public $cacher;
    public $parser;

    public function moduleAccess(){
        if($this->authorize->autorized){
            //return true;
            if((int)$this->authorize->user->params['role']['secure'] === 1){
                return true;
            }
        }
        $this->module['name']='404';
        $this->loadModule();
        return false;
    }

    public function setFlash($name,$value){
        $flash = new iceFlashVars();
        $flash->set($name,$value);
    }
    public function getFlash($name){
        $flash = new iceFlashVars();
        return ($flash->get($name));
    }

    public function redirect($url, $code = null){
        $redirect = new iceRedirect($url, $code);
        die();
    }

    public function getRequestValue($valuename, $mode = 0)
    {

        $rv = new iceRequestValues($this->values);
        $rv->getRequestValue($valuename, $mode);

        $this->values = $rv->returnValues();

    }

    public function getRequestValues($valuesnames, $mode = 0)
    {

        $rv = new iceRequestValues($this->values);
        $rv->getRequestValues($valuesnames, $mode);

        $this->values = $rv->returnValues();

    }

    public function unsetValues(){
        if(is_object($this->values)){
            if(count($this->values) > 0){
                foreach ($this->values as $key=>$value){
                    $this->values->$key = '';
                }
            }
        }
        return true;
    }

    public function showErrors()
    {
        if(is_array($this->errors) && count($this->errors) > 0)
        {
            visualijop($this->errors);
        }
    }

    //TODO функция разбора REST
    public function parseREST()
    {

    }

    //функция разбора юрла
    public function parseURL()
    {
        $path = array();
        if (isset($_SERVER['REQUEST_URI'])) {
            $request_path = explode('?', $_SERVER['REQUEST_URI']);

            $path['base'] = rtrim(dirname($_SERVER['SCRIPT_NAME']), '\/');
            $path['call_utf8'] = substr(urldecode($request_path[0]), strlen($path['base']) + 1);
            $path['call'] = utf8_decode($path['call_utf8']);
            if ($path['call'] == basename($_SERVER['PHP_SELF'])) {
                $path['call'] = '';
            }
            $path['call_parts'] = explode('/', $path['call']);

            if(isset($request_path[1])){
                $path['query_utf8'] = urldecode($request_path[1]);
                $path['query'] = utf8_decode(urldecode($request_path[1]));}
            else{
                $path['query_utf8']='';
                $path['query']='';
            }
            $vars = explode('&', $path['query']);
            foreach ($vars as $var) {
                $t = explode('=', $var);
                if(isset($t[1]))
                {
                    $path['query_vars'][$t[0]] = $t[1];
                }
                else{
                    $path['query_vars'][$t[0]] = '';
                }

            }
        }
        $this->path_info=$path;

        $this->module=new iceParseModule($this->DB, $path);
        $this->module=$this->module->module;
    }

    //функция загрузки модуля
    public function loadModule()
    {
        if(is_array($this->module) && $this->module['name'] != '')
        {
            //проверяем наличае файла модуля
            $modulePatch=$this->settings->path.'/modules/'.$this->module['name'].'.php';

            if(!file_exists($modulePatch))
            {
                $this->errors[]='Нет файла подключаемого модуля '.$modulePatch;
            }
            else
            {
                require ($modulePatch);
            }
        }
        else
        {
            $this->errors[]='Не выбран модуль для загрузки';
        }
    }

    public function loadTemplate()
    {
        if(is_array($this->module) && $this->module['name'] != '')
        {
            //проверяем наличае файла модуля
            $templatePatch=$this->settings->path.'/templates/'.$this->settings->template.'/module/t_'.$this->module['name'].'.php';
            if(!file_exists($templatePatch))
            {
                $this->errors[]='Нет файла подключаемого шаблона '.$templatePatch;
            }
            else
            {
                ob_start();
                require ($templatePatch);
                $this->body=ob_get_contents();
                ob_end_clean();
            }
        }
        else
        {
            $this->errors[]='Не подключен ни один модуль';
        }
    }

    public function printSite()
    {
        //если есть ошибки сайта - выводим 500 ошибку
        if(is_array($this->errors) && count($this->errors) > 0)
        {
            $errors=$this->errors;
            $this->module['name']='500';
            $this->loadModule();
            $this->loadTemplate();
            $this->errors=null;
            $this->printSite();
            $this->errors=$errors;
        }
        else
        {
            //выводим заголовки
            if(is_null($this->headers))
            {
                $this->headers = new iceHeaderBuilder();
                $this->headers->standartHeaders();
            }

            $this->headers->printHeaders();

            //выводим тело сайта (формируется в шаблоне)
            if(!is_null($this->body))
            {
                echo $this->body;
            }
        }

    }

    public function destroy()
    {
        if($this->DB->mysqli){
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
        unset($this->textfunctions);
    }

    public function getMaterialTypesTree(){

        $parser = new icePathParser($this->DB, [], $this->settings);

        $key = $parser->getMTTCacheKey();
        if($this->cacher->has($key)){
            return $parser->getMTTCache($key);
        }

        $materialTypes = new iceMatTypeList($this->DB, null, null, 1, null, 0, null);
        $materialTypes = $materialTypes->getRecordsTree('all');
        $parser->setMTTCache($key,$materialTypes,1*24*60*60);
        return $materialTypes;

    }

    public function __construct($setup, $makeMaterialTypes = false)
    {
        $this->version = '1.01b';
        $this->settings = new iceSettings($setup);
        $this->DB = new iceDB($this->settings);
        $this->styles = new iceStylesBuilder();
        $this->jscripts = new iceJScriptBuilder();
        $this->errors=Array();
        $this->values = new stdClass();
        $this->textfunctions = new iceTextFunctions();
        $this->authorize = new iceAuthorize($this->DB);

        if(is_object($this->settings) && isset($this->settings->cache->host) && isset($this->settings->cache->port))
        {
            $this->cacher = new iceCacher($this->settings->cache->host,$this->settings->cache->port);
        }
        else
        {
            $this->cacher = new iceCacher();
        }

        //проверяем ошибки БД
        if(isset($this->DB->errors) && $this->DB->errors->flag == 1)
        {
            $this->errors[]=$this->DB->errors->text;
        }
        //формируем типы материалов (для меню, парсинга итд итп)
        elseif ($makeMaterialTypes){

            $this->materialTypes = $this->getMaterialTypesTree();
            $this->parser = new icePathParser($this->DB, $this->materialTypes, $this->settings);

        }



    }

}