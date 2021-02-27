<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * TODO Translator Class
 *
 */

namespace ice;

use ice\DB\DB;

class iceTranslator {

    public $text;
    public $language;
    private $DB;
    public $result;

    public function translate()
    {

    }

    public function __construct(DB $DB, $text='', $language=2)
    {
        $this->text=$text;
        $this->language=$language;
        $this->DB = $DB;
        $this->result='';

        if($text != '')
        {
            $this->translate();
        }
    }

}