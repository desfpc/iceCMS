<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Cache Class
 *
 */

namespace ice;

use redka\redka;

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

        //создаем объект redis
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