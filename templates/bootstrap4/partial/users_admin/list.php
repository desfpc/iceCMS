<?php
/**
* Created by Sergey Peshalov https://github.com/desfpc
* PHP framework and CMS based on it.
* https://github.com/desfpc/iceCMS
* @var ice\Web\Render $this
*/

use ice\iceWidget;
use ice\Models\User;

$patch = '/admin/users_admin';

$this->jsready.='
        
    $("select#role").change(function(){
        document.location.href="'.$patch.'/?page=1&role="+$(this).val()+"&status="+$("select#status").val();
    });
    
    $("select#status").change(function(){
        document.location.href="'.$patch.'/?page=1&status="+$(this).val()+"&role="+$("select#role").val();
    });
    
    ';

?>
<div class="row">
    <div class="col-sm-12">
        <?php
        //выводим ошибки
        include_once ($template_folder.'/partial/t_alert.php');

        ?>
        <div class="form-group row">
            <label for="role" class="col-sm-1 col-form-label">Роль</label>
            <div class="col-sm-3">
                <select class="form-control selectpicker" data-live-search="true" id="role" name="role" aria-describedby="roleHelp" placeholder="Роль пользователя">
                    <option value="all">Все</option>
                    <?php

                    if(isset($this->moduleData->userRoles) && is_array($this->moduleData->userRoles) && count($this->moduleData->userRoles) > 0){
                        foreach ($this->moduleData->userRoles as $userRole){

                            if($this->values->role == $userRole['id']){
                                $selected = ' selected="selected"';
                            }
                            else {
                                $selected = '';
                            }

                            echo '<option value="'.$userRole['id'].'"'.$selected.'>'.$userRole['name'].'</option>';
                        }
                    }

                    ?>
                </select>
            </div>
            <label for="status" class="col-sm-1 col-form-label">Статус</label>
            <div class="col-sm-3">
                <select class="form-control selectpicker" data-live-search="true" id="status" name="status" aria-describedby="statusHelp" placeholder="Статус пользователя">
                    <option value="all">Все</option>
                    <?php

                    if(isset($this->moduleData->userStatuses) && is_array($this->moduleData->userStatuses) && count($this->moduleData->userStatuses) > 0){
                        foreach ($this->moduleData->userStatuses as $userStatus){

                            if($this->values->status == $userStatus['id']){
                                $selected = ' selected="selected"';
                            }
                            else {
                                $selected = '';
                            }

                            echo '<option value="'.$userStatus['id'].'"'.$selected.'>'.$userStatus['name'].'</option>';
                        }
                    }

                    ?>
                </select>
            </div>
        </div>
        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th style="width: 60px;">ID</th>
                <th>E-mail</th>
                <th>Имя</th>
                <th>Роль</th>
                <th style="width: 60px;">Статус</th>
                <th style="width: 60px;">Дата регистрации</th>
                <th style="width: 60px;">Действия</th>
            </tr>
            </thead>
            <tbody>
                <?php

                if(isset($this->moduleData->users) && is_array($this->moduleData->users) && count($this->moduleData->users) > 0){
                    foreach ($this->moduleData->users as $row){

                        if($row['status_id'] == 1){
                            $stat = '<i class="material-icons md-24 md-green" title="активный">person</i>';
                            $statBtn = '<a href="'.$patch.'/?mode=list&page='.$this->moduleData->page.'&id='.$row['id'].'&action=disable">
            <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Отключить">
                <i class="material-icons md-16 md-light">person_off</i>
            </button>
        </a>';
                        }
                        else {
                            $stat = '<i class="material-icons md-24 md-red" title="отключенный">person_outline</i>';
                            $statBtn = '<a href="'.$patch.'/?mode=list&page='.$this->moduleData->page.'&id='.$row['id'].'&action=enable">
            <button type="button" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Включить">
                <i class="material-icons md-16 md-light">person_add_alt</i>
            </button>
        </a>';
                        }

                        //вывод строки-записи пользователя
                        echo '
<tr>
    <td>'.$row['id'].'</td>
    <td>'.$row['login_email'].'</td>
    <td>'.$row['full_name'].'</td>
    <td>'.$row['user_role_name'].'</td>
    <td>'.$stat.'</td>
    <td>'.User::formatDate($row['date_add']).'</td>
    <td><a href="'.$patch.'/?mode=edit&id='.$row['id'].'">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Редактировать">
                <i class="material-icons md-16 md-light">edit</i>
            </button>
        </a><!--
        -->'.$statBtn.'</td>
</tr>';
                    }
                }

                ?>
            </tbody>
        </table>
        <?php
        $pages = new iceWidget($this->DB, 'pages', $this->settings);
        $pages->show([
            'count' => $this->moduleData->usersCnt,
            'perpage' => $this->moduleData->perpage,
            'page' => $this->moduleData->page,
            'url' => $_SERVER['REQUEST_URI']
        ]);
        ?>
        <a href="<?=$patch?>/?mode=add"><button type="button" class="btn btn-primary"><i class="material-icons md-24 md-light">person_add</i> Создать</button></a>
    </div>
</div>