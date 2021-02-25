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
    $this->moduleData->errors[]='Для регистрации нужно <a href="/exit">выйти</a> из текущей учетной записи';
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
        <?php if($showform){
            $action = '/registration';

            include_once ($template_folder.'/partial/t_user_form.php');

        } ?>
    </div>
<?php include_once ($template_folder.'/partial/t_footer.php');