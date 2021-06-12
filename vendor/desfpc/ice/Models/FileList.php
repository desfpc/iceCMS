<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * File List Class
 *
 */

namespace ice\Models;

use ice\DB\DB;
use ice\Settings\Settings;

/**
 * Class FileList
 * @package ice\Models
 */
class FileList extends ObjectList
{
    /**
     * FileList constructor.
     *
     * @param DB $DB
     * @param null|array $conditions
     * @param null|array $sort
     * @param int $page
     * @param int $perpage
     * @param int $cachetime
     * @param null|Settings $settings
     */
    public function __construct(DB $DB, $conditions = null, $sort = null, $page = 1, $perpage = 20, $cachetime = 0, $settings = null)
    {
        $this->doConstruct($DB, 'files', $conditions, $sort, $page, $perpage, $cachetime, $settings);
    }

}