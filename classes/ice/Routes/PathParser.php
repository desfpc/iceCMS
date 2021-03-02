<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * ULR Patch Parser Class
 *
 */

namespace ice\Routes;

use ice\DB\Cacher;
use ice\DB\DB;
use ice\Models\Mat;
use ice\Web\Redirect;

class PathParser
{

    public $settings;
    public $errors;
    public $DB;
    public $mtypes = [];
    public $material;
    private $cacher;
    private $types;

    public function __construct(DB $DB, $types, $settings = null)
    {
        $this->errors = [];
        $this->settings = $settings;

        if (is_object($this->settings) && isset($this->settings->cache->host) && isset($this->settings->cache->port)) {
            $this->cacher = new Cacher($this->settings->cache->host, $this->settings->cache->port);
        } else {
            $this->cacher = new Cacher();
        }

        $this->DB = $DB;
        $this->types = $types;
    }

    public function getMTTCache($key)
    {
        return json_decode($this->cacher->get($key), true);
    }

    public function setMTTCache($key, $value, $expire)
    {
        $this->cacher->set($key, json_encode($value), $expire);
    }

    public function delMTTCache()
    {
        return $this->cacher->del($this->getMTTCacheKey());
    }

    public function getMTTCacheKey()
    {
        return $this->DB->settings->name . 'materialTypesTree';
    }

    public function delMTUCache($id)
    {
        return $this->cacher->del($this->getMTUCacheKey($id));
    }

    public function getMTUCacheKey($id)
    {
        return $this->DB->settings->name . 'materialTypeURL_' . $id;
    }

    //функция строит URL типа материала, зная список типов материала и id

    public function parseURL($call_parts)
    {

        $mtypes = [];
        $mtype = null;
        $material = null;
        $parent = 0;

        //если пусто - то главный раздел
        if (count($call_parts) == 1 && $call_parts[0] == '') {
            $call_parts[0] = 'main';
        }

        //проверяем на существование типа материала
        foreach ($call_parts as $part) {

            $finded = false;

            //проверяем существование типа материала
            if (isset($this->types['childs'][$parent]) && is_array($this->types['childs'][$parent]) && count($this->types['childs'][$parent]) > 0) {
                foreach ($this->types['childs'][$parent] as $type) {
                    if ($type['id_char'] == $part) {
                        $mtypes[] = $type;
                        $parent = $type['id'];
                        $mtype = $type;
                        $finded = true;
                    }
                }
            }

            //проверяем материал
            if (!$finded) {
                $query = 'SELECT id FROM materials WHERE material_type_id = ' . $parent . ' AND id_char = ' . "'$part'";
                if ($res = $this->DB->query($query)) {
                    if (count($res) > 0) {
                        $mid = $res[0]['id'];
                        $material = new Mat($this->DB, $mid);
                        if ($material->getRecord()) {
                            $finded = true;
                        }
                    }
                }
            }

            if (!$finded) {
                $redirect = new Redirect('/404', 302);
            }

        }

        $this->mtypes = $mtypes;
        $this->material = $material;

    }

    public function getMatTypeURL($id)
    {

        $url = '';

        $key = $this->getMTUCacheKey($id);
        if ($this->cacher->has($key)) {
            return $this->cacher->get($key);
        }

        //проходим массив типов, находим всех предков и строим URL
        if (key_exists($id, $this->types['types'])) {
            if ($this->types['types'][$id]['id_char'] != 'main') {
                $url = '/' . $this->types['types'][$id]['id_char'];
            }

            if ($this->types['types'][$id]['parent_id'] > 0) {
                $url = $this->getMatTypeURL($this->types['types'][$id]['parent_id']) . $url;
            }

        }

        if ($url == '') {
            $url = '/';
        }

        $expired = 1 * 24 * 60 * 60;
        $this->cacher->set($key, $url, $expired);

        return $url;

    }

}