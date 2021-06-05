<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Base Object List Class
 *
 */

namespace ice\Models;

use ice\DB\Cacher;
use ice\DB\DB;

class ObjectList
{

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

    public function __construct(DB $DB, $dbtable, $conditions = null, $sort = null, $page = 1, $perpage = 20, $cachetime = 0, $settings = null)
    {
        $this->doConstruct($DB, $dbtable, $conditions, $sort, $page, $perpage, $cachetime, $settings);
    }

    public function doConstruct(DB $DB, $dbtable, $conditions = null, $sort = null, $page = null, $perpage = null, $cachetime = 0, $settings = null)
    {
        $this->DB = $DB;
        $this->dbtable = $dbtable;
        $this->conditions = $conditions;
        $this->settings = $settings;
        $this->cachetime = $cachetime;
        $this->sort = $sort;
        $this->page = $page;
        $this->perpage = $perpage;

        if (is_object($this->settings) && isset($this->settings->cache->host) && isset($this->settings->cache->port)) {
            $this->cacher = new Cacher($this->settings->cache->host, $this->settings->cache->port);
        } else {
            $this->cacher = new Cacher();
        }
    }

    public function getCnt($baseQuery = null, $parentQuery = null)
    {
        return $this->prepareRecords($baseQuery, $parentQuery, true);
    }

    //получение кол-ва записей

    public function prepareRecords($baseQuery = null, $parentQuery = null, $cnt = false)
    {
        //формирование запроса
        if (is_null($baseQuery)) {
            $query = 'SELECT dbtable.* ';

            $query .= $this->moreQuery();

            $query .= ' FROM ' . $this->dbtable . ' dbtable
        WHERE 1=1 ';
        } else {
            $query = $baseQuery;
        }

        //условия из переданных условий выборки
        //col - поле таблицы
        //value - как ограничиваем
        //type - =/<>/</>/in/not in/is/is not/like/not like/
        //string - true/false
        if (is_array($this->conditions) && count($this->conditions) > 0) {
            foreach ($this->conditions as $condition) {
                if ($condition['string']) {
                    $condition['val'] = "'" . $this->DB->mysqli->real_escape_string($condition['val']) . "'";
                }

                switch ($condition['type']) {
                    case 'NOT IN':
                    case 'IN':
                    case 'LIKE':
                    case 'NOT LIKE':
                        $query .= ' AND ' . $condition['col'] . ' ' . $condition['type'] . ' (' . $condition['val'] . ')';
                        break;

                    default:
                        $query .= ' AND ' . $condition['col'] . ' ' . $condition['type'] . ' ' . $condition['val'];
                        break;
                }
            }
        }

        if (!$cnt) {
            if (!is_array($this->sort)) {
                $defsort = [
                    'col' => 'id',
                    'sort' => 'DESC'
                ];
                $this->sort[] = $defsort;
            }

            if (is_array($this->sort) && count($this->sort) > 0) {
                $query .= ' ORDER BY ';

                $i = 0;
                foreach ($this->sort as $sort) {
                    ++$i;
                    if ($i > 1) {
                        $query .= ', ';
                    }
                    $query .= $sort['col'] . ' ' . $sort['sort'];
                }
            }

            if (!is_null($this->perpage)) {

                $query .= ' LIMIT ' . $this->perpage;

                if (!is_null($this->page)) {
                    $offset = $this->perpage * ($this->page - 1);
                    $query .= ' OFFSET ' . $offset;
                }

            }

        }

        //если запрос обёрнут в родительский запрос (например для рекурсии)
        if (!is_null($parentQuery)) {
            $query = str_replace('%subQuery%', $query, $parentQuery);
        }

        //\visualijoper\visualijoper::visualijop($query);

        if ($this->cacher->has($this->getCacheKey($query))) {
            $records = $this->cacher->get($this->cacheKey, true);
        } else {
            if ($cnt) {
                if ($res = $this->DB->query($query, true, true)) {
                    $records = $res;
                } else {
                    $records = false;
                }
            } else {
                if ($res = $this->DB->query($query)) {
                    $records = $res;
                } else {
                    $records = false;
                }
            }
        }

        $this->records = $records;
        $this->cacheRecords();

        return $records;
    }

    //получение записей

    public function moreQuery()
    {
        return '';
    }

    //кэширование списка

    public function getCacheKey($query)
    {
        //ключ начало
        $this->cacheKey = $this->DB->settings->name . '_list_' . $this->dbtable . ':';

        //ключ-запрос
        if ($query != '') {
            $this->cacheKey .= $query;
        }

        return ($this->cacheKey);

    }

    public function cacheRecords()
    {
        if (!is_null($this->cachetime) && $this->cachetime > 0) {
            $this->cacher->set($this->cacheKey, json_encode($this->records), $this->cachetime);
        }
    }

    public function getRecords($baseQuery = null, $parentQuery = null)
    {
        return $this->prepareRecords($baseQuery, $parentQuery, false);
    }

    public function uncacheRecords()
    {
        $this->getCacheKey(null);

        $keys = $this->cacher->findKeys($this->cacheKey . '*');

        if (is_array($keys) && count($keys) > 0) {
            foreach ($keys as $key) {
                $this->cacher->del($key);
            }
        }
    }

}