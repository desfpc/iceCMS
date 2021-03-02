<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Flash Variables Class
 *
 */

namespace ice\Web;

class FlashVars
{
    public $vars = [];

    public function __construct()
    {
        if (isset($_SESSION['flashVars'])) {
            $this->vars = $_SESSION['flashVars'];
        }
    }

    public function set($name, $value)
    {
        $this->vars[$name] = $value;
        $_SESSION['flashVars'] = $this->vars;
    }

    public function get($name)
    {
        if (isset($this->vars[$name])) {
            $value = $this->vars[$name];
            unset($this->vars[$name]);
            unset($_SESSION['flashVars']);
            $_SESSION['flashVars'] = $this->vars;
            return $value;
        }
        return false;
    }
}