<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 * @var string $action
 */

//проверка переменных для шаблона, заполнение их стандартными значениями
if (!isset($buttonIcon)) {
    $buttonIcon = 'person_add';
}
if (!isset($buttonText)) {
    $buttonText = 'Создать';
}
if (!isset($buttonColor)) {
    $buttonColor = 'btn-success';
}

?>
<form id="regForm" action="<?= $action ?>" method="post">
    <div class="form-group">
        <label for="regEmail">Email адрес</label>
        <input type="email" class="form-control" id="regEmail" name="regEmail" aria-describedby="regEmailHelp"
               placeholder="Введите email" required value="<?= $this->values->regEmail; ?>">
        <small id="emailHelp" class="form-text text-muted">Является login-ом на сайте. Другие пользователи его не
            увидят.</small>
    </div>
    <div class="form-group">
        <label for="regLogin">Логин</label>
        <input type="email" class="form-control" id="regLogin" name="regLogin" aria-describedby="regLoginHelp"
               placeholder="Введите login" value="<?= $this->values->regLogin; ?>">
        <small id="LoginHelp" class="form-text text-muted">Введите логин на сайте</small>
    </div>
    <div class="form-group">
        <label for="regPass">Пароль</label>
        <input type="password" class="form-control" id="regPass" name="regPass" placeholder="введите Пароль" required>
    </div>
    <div class="form-group">
        <label for="regPass2">Пароль повторно</label>
        <input type="password" class="form-control" id="regPass2" name="regPass2" placeholder="введите Пароль повторно"
               required>
    </div>
    <div class="form-group">
        <label for="regFIO">ФИО</label>
        <input type="text" class="form-control" id="regFIO" name="regFIO" aria-describedby="regFIOHelp"
               placeholder="Введите часть фИО" value="<?= $this->values->regFIO; ?>">
        <small id="FIOHelp" class="form-text text-muted">Ваше имя, чтобы мы знали, как к Вам обращаться</small>
    </div>
    <div class="form-group">
        <label for="regTel">Телефон</label>
        <input type="text" class="form-control" id="regTel" name="regTel" aria-describedby="regTelHelp"
               placeholder="Введите номер телефона" value="<?= $this->values->regTel; ?>">
        <small id="emailHelp" class="form-text text-muted">Заполняется опционально. Другие пользователи его не
            увидят.</small>
    </div>
    <div class="form-group">
        <label for="regNik">Псевдоним</label>
        <input type="text" class="form-control" id="regNik" name="regNik" aria-describedby="regNikHelp"
               placeholder="Введите псевдоним" value="<?= $this->values->regNik; ?>">
        <small id="emailHelp" class="form-text text-muted">Отображается в публичных комментариях, досках, чатах</small>
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="regPD" name="regPD" required
               value="1" <?php if ($this->values->regPD == '1') {
            echo 'checked';
        } ?>><?= $this->values->regPD ?>
        <label class="form-check-label" for="regPD">Согласен с соглашением на обработку персональных данных</label>
    </div>
    <p>&nbsp;</p><input type="hidden" name="action" value="add">
    <button type="submit" class="btn <?= $buttonColor ?>"><i
            class="material-icons md-24 md-light"><?= $buttonIcon ?></i><?= $buttonText ?></button>
</form>