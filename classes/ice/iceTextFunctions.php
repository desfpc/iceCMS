<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Text Functions Class
 *
 */

namespace ice;

class iceTextFunctions {

    public $text;

    public static function mb_transliterate($string)
    {
        $table = array(
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D',
            'Е' => 'E', 'Ё' => 'YO', 'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I',
            'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
            'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C', 'Ч' => 'CH',
            'Ш' => 'SH', 'Щ' => 'SCH', 'Ь' => '', 'Ы' => 'Y', 'Ъ' => '',
            'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA',

            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
            'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
            'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
            'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'sch', 'ь' => '', 'ы' => 'y', 'ъ' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
        );

        $output = str_replace(
            array_keys($table),
            array_values($table),$string
        );

        // таеже те символы что неизвестны
        $output = preg_replace('/[^-a-z0-9._\[\]\'"]/i', ' ', $output);
        $output = preg_replace('/ +/', '-', $output);

        return $output;
    }

    //формирование char_id из текста
    public static function makeCharId($text){

        $id_char = iceTextFunctions::mb_transliterate($text);
        $id_char = str_replace('"', '-quot-', $id_char);
        $id_char = trim(preg_replace('/-{2,}/', '-', $id_char), '-');
        $id_char = str_replace(' ','_',$id_char);

        return $id_char;

    }

    //проверка сложности пароля
    public function checkSecurePass()
    {
        if(mb_strlen($this->text, 'utf8') < 6)
        {
            return false;
        }

        if (preg_match("/([0-9]+)/", $this->text))
        {
            if (preg_match("/([a-zA-Z]+)/", $this->text))
            {
                return true;
            }
            return false;
        }
        return false;
    }

    //проверка e-mail адреса по маске
    public function checkEmail()
    {
        $pattern = '/^[a-z0-9_.\-]+@[a-z0-9_.\-]+\.[a-z0-9_.\-]+$/i';
        $res = preg_match($pattern, $this->text);
        return (bool)$res;
    }

    public function __construct($text=null)
    {
        $this->text=$text;
    }

}