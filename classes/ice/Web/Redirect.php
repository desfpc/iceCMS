<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * URL Redirect Class
 *
 */

namespace ice\Web;

class Redirect {

    public function __construct($url,$code = null)
    {
        header('Location: '.$url,true, $code);
        die();
    }

}