<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Store Request Good Class
 *
 */

namespace ice\Models;

use ice\DB\DB;

class StoreRequestGood extends Obj
{
    /**
     * StoreRequest constructor. (подменяем создание объекта - прописываем железно целевую таблицу)
     * @param DB $DB
     * @param null $id
     * @param null $settings
     */
    public function __construct(DB $DB, $id = null, $settings = null)
    {
        $this->doConstruct($DB, 'store_request_goods', $id, $settings);
    }
}