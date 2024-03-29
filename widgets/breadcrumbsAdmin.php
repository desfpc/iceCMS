<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Widget $this
 */

/*[
    'name' => 'Материалы',
    'param' => 'menu',
    'value' => 'materials'
]*/

$lastActive = false;
$text = '';
$url = '';
$dirs = '/';

if (is_array($this->params) && count($this->params) > 0) {
    $i = 0;
    $cnt = count($this->params);
    foreach ($this->params as $param) {
        ++$i;

        if ($i == $cnt) {
            $text .= '<li class="breadcrumb-item active" aria-current="page">' . $param['name'] . '</li>';
        } elseif (isset($param['dir']) && $param['dir'] != '') {
            if ($param['dir'] == 'none') {
                $text .= '<li class="breadcrumb-item"><a href="/">' . $param['name'] . '</a></li>';
            } else {
                $dirs .= $param['dir'] . '/';
                $text .= '<li class="breadcrumb-item"><a href="' . $dirs . $url . '">' . $param['name'] . '</a></li>';
            }
        } else {
            if ($url == '') {
                $url .= '?';
            } else {
                $url .= '&';
            }
            $url .= $param['param'] . '=' . $param['value'];

            if (isset($param['param2'])) {
                $url .= '&' . $param['param2'] . '=' . $param['value2'];
            }

            $text .= '<li class="breadcrumb-item"><a href="' . $dirs . $url . '">' . $param['name'] . '</a></li>';
        }
    }
}

//выводим
if ($text != '') {
    echo '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
    echo $text;
    echo '</ol></nav>';
}