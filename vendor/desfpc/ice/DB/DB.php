<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * DB Class
 *
 */

namespace ice\DB;

use ice\Settings\Settings;
use stdClass;
use Throwable;

class DB
{
    public $settings;
    public $errors;
    public $warning;
    public $mysqli;
    public $status = 0;
    public $connected = 0;

    public function __construct(Settings $settings)
    {
        $this->status = new stdClass();
        $this->errors = new stdClass();
        $this->warning = new stdClass();

        $this->status->flag = 0;
        $this->status->text = 'Соединение с БД не установлено';

        $this->settings = $settings->db;

        $this->connect();
    }

    //соединение с БД
    public function connect()
    {

        $this->errors->flag = '1';
        $this->errors->text = 'Выбранный тип БД (' . $this->settings->type . ') не поддерживается';

        switch ($this->settings->type) {
            case 'mysql':

                try {
                    if (!$this->mysqli = mysqli_connect($this->settings->host, $this->settings->login, $this->settings->pass)) {
                        $this->errors->flag = '1';
                        $this->errors->text = 'Нет возможности установить соединение с БД';
                    } else {
                        $this->connected = 1;
                        if (!$this->mysqli->select_db($this->settings->name)) {
                            $this->errors->flag = 1;
                            $this->errors->text = 'Нет возможности выбрать БД "' . $this->settings->name . '"';
                        } else {
                            /* изменение набора символов на заданный */
                            if (!$this->mysqli->set_charset($this->settings->encoding)) {
                                //printf("Ошибка при загрузке набора символов utf8: %s\n", $mysqli->error);
                                $this->warning->flag = 1;
                                $this->warning->text = 'Ошибка выбора кодировки: ' . $this->settings->encoding;
                            } else {
                                $this->errors->flag = 0;
                                $this->errors->text = 'Соединение с БД установлено';

                                $this->status->flag = 1;
                                $this->status->text = 'Соединение с БД установлено';

                            }
                        }
                    }
                } catch (Throwable $t) {
                    $this->errors->flag = 1;
                    $this->errors->text = 'Не удалось установить соединение с БД: ' . $t->getMessage();
                }

                break;
        }

        if ($this->errors->flag == 0) {
            return true;
        }
        return false;

    }

    //выполнение запроса к БД
    public function query($query, $free = true, $cnt = false, $forced = false)
    {

        if ($this->status->flag || $forced) {
            switch ($this->settings->type) {

                //обработка запроса к мускулю
                case 'mysql':

                    if (!$res = $this->mysqli->query($query)) {
                        $this->warning->flag = 1;

                        if (!isset($this->warning->text)) {
                            $this->warning->text = [];
                        }

                        $this->warning->text[] = 'Ошибка выполнения запроса: ' . $query;
                        return false;
                    }

                    //есди запрос - select show WITH RECURSIVE
                    if (preg_match("/^select/i", trim($query)) || preg_match("/^show/i", trim($query)) || preg_match("/^with recursive/i", trim($query))) {
                        if (!$cnt) {
                            $result = [];
                            while ($row = $res->fetch_assoc()) {
                                $result[] = $row;
                            }

                            if ($free) {
                                $res->free();
                            }
                            return ($result);
                        }

                        $result = $res->num_rows;

                        if ($free) {
                            $res->free();
                        }
                        return $result;

                    }

                    /*if($free)
                    {
                        $res->free();
                    }*/

                    //прочие запросы
                    return (true);


                    break;
            }
        }

        return false;
    }

    //выполнение мультизапроса к БД
    public function multiQuery($query)
    {
        if ($this->status->flag) {
            switch ($this->settings->type) {

                //обработка запроса к мускулю
                case 'mysql':

                    if (!$res = $this->mysqli->multi_query($query)) {

                        do{

                            $this->warning->flag = 1;

                            if (!isset($this->warning->text)) {
                                $this->warning->text = [];
                            }

                            $this->warning->text[] = 'Ошибка выполнения запроса: ' . $query;
                            return false;

                        } while(mysqli_more_results($this->mysqli) && mysqli_next_result($this->mysqli));

                    }

                    return true;


                    break;
            }
        }
    }
}