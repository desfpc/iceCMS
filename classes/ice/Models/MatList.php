<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Material List Class
 *
 */

namespace ice\Models;

use ice\iceObjectList;
use ice\iceDB;

class MatList extends iceObjectList {
    public function __construct(iceDB $DB, $conditions=null, $sort=null, $page=1, $perpage=20, $cachetime=0, $settings=null)
    {
        $this->doConstruct($DB, 'materials', $conditions, $sort, $page, $perpage, $cachetime, $settings);
    }

    public function moreQuery(){
        $query=',   (SELECT mt.name FROM material_types mt WHERE mt.id = dbtable.material_type_id) material_type_name,
                    (SELECT fm.file_id FROM material_files fm, files f 
                        WHERE f.filetype = \'image\' AND f.id = fm.file_id AND fm.material_id = dbtable.id 
                        ORDER BY fm.ordernum ASC, f.id ASC
                        LIMIT 1) favicon
        ';
        return $query;
    }

}