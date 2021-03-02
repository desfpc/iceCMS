<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * CSS Styles Builder Class
 *
 */

namespace ice\Web;

class StylesBuilder
{

    public $styles;

    public function __construct()
    {
    }

    public function printStyles()
    {
        if (is_array($this->styles) && count($this->styles) > 0) {
            foreach ($this->styles as $style) {
                echo '<link rel="stylesheet" href="' . $style . '">';
            }
        }
    }

    public function addStyles(array $arr)
    {
        if (!is_array($this->styles)) {
            $this->styles = array();
        }
        if (!is_null($arr) && is_array($arr) && count($arr) > 0) {
            foreach ($arr as $str) {
                $this->addStyle($str);
            }
        }
    }

    public function addStyle(string $str)
    {
        if (!is_array($this->styles)) {
            $this->styles = array();
        }
        $this->styles[] = $str;
    }

}