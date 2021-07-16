<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * TODO Helpers - Numbers Class
 *
 */

namespace ice\Helpers;

/**
 * Class Numbers
 * @package ice\Helpers
 */
class Numbers
{
    /**
     * Функция преобразования триады цифр в прописной
     *
     * @param int $num целое число от 0 до 999
     * @param string[] $words массив названий чисел (пример для рублей: ['один', 'два', 'рубль', 'рубля', 'рублей'])
     * @return string прописной вид входящего числа
     */
    public static function parseTriad(int $num, array $words): string
    {
        $hundreds = array('', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот');
        $decads = array('двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто');
        $fdecads = array('десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать');
        $ones = array('', $words[0], $words[1], 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять');

        $result = '';
        $h = floor($num / 100);
        $result .= $hundreds[$h];

        $d = floor(($num - $h * 100) / 10);
        $c = ($num - $h * 100 - $d*10);

        $result .= ($result != '') ? ' ' : '';

        if($d == 1) { $result .= $fdecads[$c]; }
        else {
            if($d > 1) $result .= $decads[$d-2] . ' ';
            $result .= $ones[$c];
        }
        $result .= ($result != '') ? ' ' : '';

        switch ($c) {
            case 1:
                $result .= ($d != 1) ? $words[2] : $words[4];
                break;
            case 2:
            case 3:
            case 4:
                $result .= ($d != 1) ? $words[3] : $words[4];
                break;
            default:
                if ($num > 0) { $result .= $words[4]; }
        }
        return $result;
    }

    /**
     * Функция преобразования целочисленной суммы денег в прописной вид
     *
     * @param int $num сумма денег (целое число)
     * @param bool $upcase флаг необходимости преобразовать первый символ в верхний регистр
     * @return string прописной вид суммы
     */
    public static function parseSum(int $num, bool $upcase = false): string
    {
        $md = floor($num/1e9); //миллиарды
        $m = floor(($num - $md*1e9)/1e6); //миллионы
        $t = floor(($num - $md*1e9 - $m*1e6)/1e3); //тысячи
        $h = floor($num - $md*1e9 - $m*1e6 - $t*1e3); //сотни

        $result = self::parseTriad($md, array('один', 'два', 'миллиард', 'миллиарда', 'миллиардов'));
        $result .= ($result != '') ? ' ' : '';
        $result .= self::parseTriad($m, array('один', 'два', 'миллион', 'миллиона', 'миллионов'));
        $result .= ($result != '') ? ' ' : '';
        $result .= self::parseTriad($t, array('одна', 'две', 'тысяча', 'тысячи', 'тысяч'));
        $result .= ($result != '') ? ' ' : '';
        $result .= self::parseTriad($h, array('один', 'два', 'рубль', 'рубля', 'рублей'));

        $result = $upcase ? ucfirst($result) : $result;
        return $result;
    }

    /**
     * Функция преобразование полной (в том числе дробной) суммы денег в прописной вид
     *
     * @param float $cost
     * @return string
     */
    public static function parseCost(float $cost): string
    {
        $sumRub=(int)$cost;
        $sumKop=($cost*100)%100;

        if ($sumKop < 10 && $sumKop > 0) {
            $sumKop='0' . $sumKop;
        }
        $sumRub=self::parseSum($sumRub, false);

        return $sumRub . ' ' . $sumKop . ' коп.';
    }

}