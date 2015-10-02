<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 15.05.15
 * Time: 10:51
 */

namespace common\helpers;


class TranslitHelper {

    public static function to($string, $options = []){

        if(!isset($options['lang'])){
            $options['lang'] = 'ru';
        }

        if(!isset($options['delimeter'])){
            $options['delimeter'] = '-';
        }

        if($string){
            $string = trim($string);
        }else{
            return $string;
        }

        $customChars=array(
            'ru' => array("и"=>"i","И"=>"i"),
            'uk' => array("и"=>"y","И"=>"y"),
        );

        $replace = array(
            "'"=>"",
            "`"=>"",
            "а"=>"a","А"=>"a",
            "б"=>"b","Б"=>"b",
            "в"=>"v","В"=>"v",
            "г"=>"g","Г"=>"g",
            "д"=>"d","Д"=>"d",
            "е"=>"e","Е"=>"e",
            "ж"=>"zh","Ж"=>"zh",
            "з"=>"z","З"=>"z",
            "й"=>"y","Й"=>"y",
            "к"=>"k","К"=>"k",
            "л"=>"l","Л"=>"l",
            "м"=>"m","М"=>"m",
            "н"=>"n","Н"=>"n",
            "о"=>"o","О"=>"o",
            "п"=>"p","П"=>"p",
            "р"=>"r","Р"=>"r",
            "с"=>"s","С"=>"s",
            "т"=>"t","Т"=>"t",
            "у"=>"u","У"=>"u",
            "ф"=>"f","Ф"=>"f",
            "х"=>"h","Х"=>"h",
            "ц"=>"c","Ц"=>"c",
            "ч"=>"ch","Ч"=>"ch",
            "ш"=>"sh","Ш"=>"sh",
            "щ"=>"shch","Щ"=>"shch",
            "ъ"=>"","Ъ"=>"",
            "ый"=>"iy","ЫЙ"=>"iy",
            "ы"=>"y","Ы"=>"y",
            "ё"=>"e","Ё"=>"e",
            "ь"=>"","Ь"=>"",
            "э"=>"e","Э"=>"e",
            "ю"=>"yu","Ю"=>"yu",
            "я"=>"ya","Я"=>"ya",
            "і"=>"i","І"=>"i",
            "ї"=>"yi","Ї"=>"yi",
            "є"=>"e","Є"=>"e"
        );
        $replace = array_merge($replace, $customChars[$options['lang']]);
        $str=iconv("UTF-8", "UTF-8//IGNORE", strtr($string, $replace));
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $options['delimeter'], $clean);
        return $clean;
    }


}