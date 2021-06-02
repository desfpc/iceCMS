<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 */

$template_folder = $this->settings->path . '/templates/' . $this->settings->template . '';

//подключаем стили и скрипты
include_once($template_folder . '/partial/t_jsandcss.php');
//$this->styles->addStyle('');
//$this->jscripts->addScript('');

//js document.load
include_once($template_folder . '/partial/t_jsreadyglobal.php');
//$this->jsready .= '';

include_once($template_folder . '/partial/t_header.php');

//получение параметров
$this->getRequestValues(['mode']);

?>
    <div class="container sitebody">
        <div class="row">
            <div class="col">
                <?php
                //выводим ошибки
                include_once($template_folder . '/partial/t_alert.php');
                ?>
            </div>
        </div>
        <?php
        //TODO выводим настройки магазина
        if(!empty($this->moduleData->settings)) {
            ?>
            <div class="row">
                <div class="col">
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th>Id</th>
                                <th>Наименование</th>
                                <th>Значение</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <?php
                        foreach ($this->moduleData->settings as $setting) {
                            ?><tr>
                                <td><?= $setting['id'] ?></td>
                                <td><?= $setting['name'] ?></td>
                                <td><?= $setting['anons'] ?></td>
                                <td></td>
                            </tr><?php
                        }
                        ?>
                    </table>
                </div>
            </div>
        <?php } ?>
        <hr>
        <div class="row">
            <div class="col">
                <h2>Добавить настройку:</h2>
                <form method="post" action="/admin/shop_settings">
                    <input type="hidden" name="menu" value="shop_settings">
                    <input type="hidden" name="mode" value="add">
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Наименование настройки:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" name="name" value="" placeholder="Наименование настройки">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="value" class="col-sm-3 col-form-label">Значение:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="value" name="value" value="" placeholder="Значение">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Добавить</button>
                </form>
            </div>
        </div>
    </div>
<?php include_once($template_folder . '/partial/t_footer.php');