<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * User List Class
 *
 */

namespace ice\Models;

use ice\iceObjectList;
use ice\iceDB;

class UserList extends iceObjectList {

    public function __construct(iceDB $DB, $conditions=null, $sort=null, $page=1, $perpage=20, $cachetime=0, $settings=null)
    {
        $this->doConstruct($DB, 'users', $conditions, $sort, $page, $perpage, $cachetime, $settings);
    }

    public function moreQuery(){
        $query=',   (SELECT ur.name FROM user_roles ur WHERE ur.id = dbtable.user_role) user_role_name,
                    (SELECT fm.file_id FROM user_files fm, files f 
                        WHERE f.filetype = \'image\' AND f.id = fm.file_id AND fm.user_id = dbtable.id 
                        ORDER BY fm.ordernum ASC, f.id ASC
                        LIMIT 1) favicon
        ';
        return $query;
    }

}