<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Store Request List Class
 *
 */

namespace ice\Models;

use ice\DB\DB;

class StoreRequestList extends ObjectList
{

    /**
     * StoreRequestList constructor.
     * @param DB $DB
     * @param null $conditions
     * @param null $sort
     * @param int $page
     * @param int $perpage
     * @param int $cachetime
     * @param null $settings
     */
    public function __construct(DB $DB, $conditions = null, $sort = null, $page = 1, $perpage = 20, $cachetime = 0, $settings = null)
    {
        $this->doConstruct($DB, 'store_requests', $conditions, $sort, $page, $perpage, $cachetime, $settings);
    }

}