<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 */

?><nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top" id="securepanel">
    <a class="navbar-brand" href="/admin/iceFW">iceFW</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#adminBar" aria-controls="adminBar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="adminBar">
        <?php

        //функция вывода меню древовидно
        function bootstrapMenuPrint($subraaray, $level, $menuarr)
        {

            //как формировать меню
            $adminAsDir = true;
            $adminDir = 'admin';

            //выводим ul
            if($level == 0)
            {
                echo '<ul class="navbar-nav">';
            }
            else
            {
                echo '<ul class="dropdown-menu">';
            }

            foreach ($subraaray as $item)
                {

                    if($adminAsDir){
                        $link='/admin/'.$item['name'];
                    }
                    else {
                        $link='/?menu='.$item['name'];
                    }

                    //костыль iceFW пунктом, что бы не смотреть подразделы
                    if($item['name'] != 'iceFW')
                    {
                        if(isset($menuarr[$item['id']]))
                        {
                            echo '<li class="dropdown-submenu"><a class="dropdown-item" href="'.$link.'">'.$item['content'].'</a><div class="dropdown-submenu-treug">▼</div>';

                            bootstrapMenuPrint($menuarr[$item['id']],($level+1),$menuarr);

                            echo '</li>';
                        }
                        else
                        {
                            echo '<li><a class="dropdown-item" href="'.$link.'">'.$item['content'].'</a></li>';
                        }
                    }
                }

            echo '</ul>';

        }

        $query='WITH RECURSIVE amodules AS
(

SELECT * /*, CAST(id AS CHAR(500)) path*/
FROM modules
WHERE secure = 1 AND parent_id IS NULL 
AND (special_rights IS NULL OR '.$this->authorize->user->params['user_role'].' IN (JSON_EXTRACT(special_rights, \'$\'))) 

UNION ALL

SELECT mm.* /*, CONCAT(am.id, \'_\', mm.id) path*/
FROM modules mm, amodules am
WHERE am.id=mm.parent_id AND mm.secure = 1 
AND (mm.special_rights IS NULL OR '.$this->authorize->user->params['user_role'].' IN (JSON_EXTRACT(mm.special_rights, \'$\'))) 

)
SELECT * FROM amodules ORDER BY name ASC';

        $menuarr=array();
        if($res=$this->DB->query($query))
        {

            if(count($res) > 0)
            {
                echo '<ul class="navbar-nav mr-auto">';
                foreach ($res as $row)
                {
                    //формируем меню с ключами по parent_id
                    if($row['parent_id'] == '')
                    {
                        $row['parent_id']=0;
                    }
                    $menuarr[$row['parent_id']][]=$row;
                }

                //krumo($menuarr);

                bootstrapMenuPrint($menuarr[0],0, $menuarr);


                echo '</ul>';
            }
        }

        ?>
    </div>
</nav>