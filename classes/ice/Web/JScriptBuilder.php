<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * js Builder Class
 *
 */

namespace ice\Web;

class JScriptBuilder
{

    public $scripts;

    public function __construct()
    {
    }

    public function printScripts()
    {
        if (is_array($this->scripts) && count($this->scripts) > 0) {
            foreach ($this->scripts as $script) {
                echo '<script src="' . $script . '"></script>';
            }
        }
    }

    public function addScripts($arr)
    {
        if (!is_array($this->scripts)) {
            $this->scripts = array();
        }
        if (!is_null($arr) && is_array($arr) && count($arr) > 0) {
            foreach ($arr as $str) {
                $this->addScript($str);
            }
        }
    }

    public function addScript(string $str)
    {
        if (!is_array($this->scripts)) {
            $this->scripts = array();
        }
        $this->scripts[] = $str;
    }

}