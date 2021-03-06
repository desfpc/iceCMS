<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 * @var string $template_folder
 */

$this->jsready .= '

    $(".form-group").has("input#regLogin").hide();
    $(".form-check").has("input#regPD").hide();

';

?>
<div class="row">
    <div class="col-sm-12">
        <?php
        //выводим ошибки
        include_once($template_folder . '/partial/t_alert.php');

        //выводим форму
        $action = '/admin/users_admin/?mode=edit';
        $buttonText = 'Сохранить';
        $buttonIcon = 'save';
        include_once($template_folder . '/partial/t_user_form.php');

        ?>
    </div>
</div>