<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * SQL Query Builder Class
 *
 */

namespace ice\DB;

/**
 * Class QueryBuilder
 * @package ice\DB
 */
class QueryBuilder
{

    public $cols;
    public $params;
    public $table;
    private $DB;

    /**
     * QueryBuilder constructor.
     *
     * @param DB $DB
     * @param $cols
     * @param $params
     * @param $table
     */
    public function __construct(DB $DB, $cols, $params, $table)
    {
        $this->params = $params;
        $this->cols = $cols;
        $this->table = $table;
        $this->DB = $DB;
    }

    /**
     * Создание Update SQL строки
     *
     * @return string
     */
    public function update(): string
    {
        $out = 'UPDATE ' . $this->table . ' SET';

        $sout = '';

        foreach ($this->cols as $col) {
            if ($col['Field'] != 'id' && $col['Field'] != 'date_add') {

                if ($sout != '') {
                    $sout .= ',';
                }

                $sout .= ' ' . $col['Field'] . '=';

                //в зависимости от типа колонки, рисуем ковычки на значение
                if (
                    mb_stripos($col['Type'], 'char', 0, 'UTF-8') !== false ||
                    mb_stripos($col['Type'], 'text', 0, 'UTF-8') !== false ||
                    mb_stripos($col['Type'], 'enum', 0, 'UTF-8') !== false
                ) {
                    if (is_null($this->params[$col['Field']])) {
                        $sout .= 'NULL';
                    } else {
                        $sout .= "'" . $this->DB->mysqli->real_escape_string($this->params[$col['Field']]) . "'";
                    }
                } else {
                    //$sout.=$this->DB->mysqli->real_escape_string($this->params[$col->Field]);
                    if (is_null($this->params[$col['Field']])) {
                        //if($col->Field == 'date_edit')
                        if ($col['Field'] == 'date_add' || $col['Field'] == 'date_event' || $col['Field'] == 'date_end' || $col['Field'] == 'date_edit') {
                            $sout .= 'NOW()';
                        } else {
                            $sout .= 'NULL';
                        }
                    } else {

                        if (
                            ((mb_strpos($col['Type'], 'int', 0, 'UTF-8') !== false) || (mb_strpos($col['Type'], 'real', 0, 'UTF-8') !== false)) &&
                            (!isset($this->params[$col['Field']]) || is_null($this->params[$col['Field']]) || $this->params[$col['Field']] == '')
                        ) {
                            $sout .= 'NULL';
                        } elseif ($col['Field'] == 'date_edit') {
                            $sout .= 'NOW()';
                        } elseif ($col['Field'] == 'date_event' || $col['Field'] == 'date_end') {
                            if ($this->params[$col['Field']] == '') {
                                $sout .= 'NULL';
                            } else {
                                $sout .= "'" . $this->DB->mysqli->real_escape_string($this->params[$col['Field']]) . "'";
                            }
                        } else {

                            $sout .= $this->DB->mysqli->real_escape_string($this->params[$col['Field']]);
                        }
                    }
                }

            }
        }

        $out .= $sout . ' WHERE id = ' . $this->params['id'];

        return $out;
    }

    /**
     * Создание Insert SQL строки
     *
     * @return string
     * @throws \Exception
     */
    public function insert(): string
    {
        $out = 'INSERT INTO ' . $this->table . ' (';

        $sout = '';
        $pout = '';

        //visualijoper\visualijoper::visualijop($this->cols);

        if (empty($this->cols)) throw new \Exception('Wrong DB Table "'.$this->table.'" Cols');

        foreach ($this->cols as $col) {
            $col = (array)$col;

            if ($sout != '') {
                $sout .= ',';
                $pout .= ',';
            }

            $sout .= $col['Field'];

            //вносим значение автоинкремента
            if ($col['Extra'] == 'auto_increment') {
                $pout .= 'NULL';
            } //вносим обычное значение
            else {

                //в зависимости от типа колонки, рисуем кавычки на значение
                if (mb_stripos($col['Type'], 'char', 0, 'UTF-8') !== false || mb_stripos($col['Type'], 'text', 0, 'UTF-8') !== false || mb_stripos($col['Type'], 'enum', 0, 'UTF-8') !== false) {

                    if (!isset($this->params[$col['Field']]) || is_null($this->params[$col['Field']])) {
                        $pout .= 'NULL';
                    } else {
                        $pout .= "'" . $this->DB->mysqli->real_escape_string($this->params[$col['Field']]) . "'";
                    }
                } else {
                    if (!isset($this->params[$col['Field']]) || is_null($this->params[$col['Field']]) || ($this->params[$col['Field']] == 'CURRENT_TIMESTAMP')) {
                        if ($col['Field'] == 'date_add' || $col['Field'] == 'date_event' || $col['Field'] == 'date_end' || $col['Field'] == 'date_edit') {
                            $pout .= 'NOW()';
                        } else {
                            $pout .= 'NULL';
                        }
                    } else {

                        if (((mb_strpos($col['Type'], 'int', 0, 'UTF-8') === false) || (mb_strpos($col['Type'], 'real', 0, 'UTF-8') === false) || (mb_strpos($col['Type'], 'decimal', 0, 'UTF-8') === false)) && (!isset($this->params[$col['Field']]) || is_null($this->params[$col['Field']]) || $this->params[$col['Field']] === '')) {
                            $this->params[$col['Field']] = 'NULL';
                        }

                        $pout .= $this->DB->mysqli->real_escape_string($this->params[$col['Field']]);
                    }
                }
            }
        }

        $out .= $sout . ') VALUES (' . $pout;


        $out .= ')';

        //visualijoper\visualijoper::visualijop($out);

        return $out;
    }

}