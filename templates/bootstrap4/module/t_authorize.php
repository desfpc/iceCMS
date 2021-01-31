<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\iceRender $this
 */

$template_folder=$this->settings->path.'/templates/'.$this->settings->template.'';

//подключаем стили и скрипты
include_once ($template_folder.'/partial/t_jsandcss.php');
//$this->styles->addStyle('');
//$this->jscripts->addScript('');

//js document.load
include_once ($template_folder.'/partial/t_jsreadyglobal.php');
$this->jsready.='

    $(".form-group").has("input#regLogin").hide();

';

include_once ($template_folder.'/partial/t_header.php');

//если пользователь авторизирован, не показываем форму
if($this->authorize->autorized)
{
    $showform=false;
    //$this->moduleData->errors[]='Вы уже авторизированы';
}
else
{
    $showform=true;
}

?>
    <div class="container sitebody">
        <div class="row">
            <div class="col">
                <?php
                //выводим ошибки
                include_once ($template_folder.'/partial/t_alert.php');
                ?>
            </div>
        </div>
        <?php if($showform){ ?>
        <form id="regForm" action="/?menu=authorize" method="post">
            <div class="form-group">
                <label for="auEmail">Email адрес</label>
                <input type="email" class="form-control" id="auEmail" name="auEmail" aria-describedby="auEmailHelp" placeholder="Введите email" required value="<?= $this->values->auEmail; ?>">
            </div>
            <div class="form-group">
                <label for="auPass">Пароль</label>
                <input type="password" class="form-control" id="auPass" name="auPass" placeholder="введите Пароль" required>
            </div>
            <p>&nbsp;</p><input type="hidden" name="action" value="login">
            <button type="submit" class="btn btn-primary">Авторизироваться</button>
        </form>
        <?php } ?>
    </div>
<?php include_once ($template_folder.'/partial/t_footer.php');