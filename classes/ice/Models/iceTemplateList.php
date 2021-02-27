<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Template List Class
 *
 */

namespace ice;

class iceTemplateList extends iceObjectList {
    public function __construct(iceDB $DB, $conditions=null, $sort=null, $page=1, $perpage=20, $cachetime=0, $settings=null)
    {
        $this->doConstruct($DB, 'templates', $conditions, $sort, $page, $perpage, $cachetime, $settings);
    }
}