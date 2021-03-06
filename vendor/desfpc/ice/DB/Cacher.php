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

namespace ice\DB;

use redka\redka;

/**
 * Class Cacher
 * @package ice\DB
 */
class Cacher
{
    public $key;
    public $value;
    public $expired;
    public $status;
    public $host;
    public $port;
    public $errors = [];
    private $redis;

    /**
     * Cacher constructor.
     *
     * @param string $host
     * @param int $port
     */
    public function __construct($host = 'localhost', $port = 6379)
    {
        $this->status = 0;

        $this->host = $host;
        $this->port = $port;

        //создаем объект redis
        $this->redis = new redka($this->host, $this->port);
        $this->redis->connect();

        if ($this->redis->connect()) {
            if ($this->redis->status == 1) {
                $this->status = 1;
                return true;
            }
        }

        $this->errors[] = 'Не получилось соединиться с Redis';
        return false;

    }

    /**
     * Проверка на наличае ключа в кэше
     *
     * @param $key
     * @return bool
     */
    public function has($key): bool
    {
        if ($this->status) {
            $this->key = $key;
            return ($this->redis->has($key));
        }
        return false;
    }

    /**
     * Поиск ключей в кэше
     *
     * @param string $pattern
     * @return mixed
     */
    public function findKeys($pattern = '*')
    {
        if ($this->status) {
            return ($this->redis->findKeys($pattern));
        }
        return false;
    }

    /**
     * Получение значения по ключу
     *
     * @param $key
     * @param bool $decode
     * @return mixed
     */
    public function get($key, $decode = false)
    {
        if ($this->status) {
            $this->key = $key;

            $this->value = $this->redis->get($key);

            if ($decode) {
                return (json_decode($this->value, true));
            }
            return ($this->value);

        }
        return false;
    }

    /**
     * Установка значения по ключу
     *
     * @param $key
     * @param $value
     * @param null $expired
     * @return bool
     */
    public function set($key, $value, $expired = null): bool
    {
        if ($this->status) {
            $this->key = $key;
            $this->value = $value;
            $this->expired = $expired;

            if ($this->redis->set($this->key, $this->value, $this->expired)) {
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * Удаление значения
     *
     * @param $key
     * @return bool
     */
    public function del($key): bool
    {
        if ($this->status) {
            $this->key = $key;
            if ($this->redis->del($this->key)) {
                return true;
            }
            return false;
        }
        return false;
    }

}