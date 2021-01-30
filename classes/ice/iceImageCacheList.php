<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Image Cache List Class
 *
 */

namespace ice;

class iceImageCacheList extends iceObjectList {
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