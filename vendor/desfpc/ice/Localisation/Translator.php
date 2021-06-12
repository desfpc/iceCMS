<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Translator Class
 *
 */

namespace ice\Localisation;

/**
 * Class Translator
 * @package ice\Localisation
 */
class Translator
{
    /**
     * перевод строки на переданный язык по ключу
     *
     * @param $language
     * @param $key
     * @return false|mixed
     */
    public static function translate($language,$key){

        $keyArr = explode('/',$key);
        $patch = '../../../translations/'.$language.'/'.$keyArr[0].'.php';

        if(!is_file($patch)){

            if($language == 'ru'){
                return false;
            }

            $patch = '../../../translations/ru/'.$keyArr[0].'.php';
            if(!is_file($patch)){
                return false;
            }

        }

        include_once ($patch);

        if(!isset($langArr)){
            return false;
        }

        $keyArr = array_shift($keyArr);

        foreach ($keyArr as $key) {
            if(!isset($langArr[$key])){
                return false;
            }

            $langArr = $langArr[$key];
            if(!is_array($langArr)){
                return $langArr;
            }
        }
        return false;
    }

}