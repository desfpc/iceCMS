<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * TODO Helpers - Strings Class
 *
 */

namespace ice\Helpers;

class Strings
{

    public static function makeCharId($text)
    {

        $id_char = Strings::Transliterate($text);
        $id_char = str_replace('"', '-quot-', $id_char);
        $id_char = trim(preg_replace('/-{2,}/', '-', $id_char), '-');
        $id_char = str_replace(' ', '_', $id_char);

        return $id_char;

    }

    //формирование char_id из текста

    public static function Transliterate($string)
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
            array_values($table), $string
        );

        $output = preg_replace('/[^-a-z0-9._\[\]\'"]/i', ' ', $output);
        $output = preg_replace('/ +/', '-', $output);

        return $output;
    }

    //проверка сложности пароля

    public static function checkSecurePass($text)
    {
        if (mb_strlen($text, 'utf8') < 6) {
            return false;
        }

        if (preg_match("/([0-9]+)/", $text)) {
            if (preg_match("/([a-zA-Z]+)/", $text)) {
                return true;
            }
            return false;
        }
        return false;
    }

    public static function checkEmail($text)
    {
        $pattern = '/^[a-z0-9_.\-]+@[a-z0-9_.\-]+\.[a-z0-9_.\-]+$/i';
        $res = preg_match($pattern, $text);
        return (bool)$res;
    }

}