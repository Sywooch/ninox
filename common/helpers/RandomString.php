<?php
/**
 * Created by PhpStorm.
 * User: krava
 * Date: 07.07.15
 * Time: 17:55
 */

namespace common\helpers;


class RandomString {

    static $length = 8;

    private static $codeSymbols = [
        'num'   => [48, 57],
        'upper' => [65, 90],
        'lower' => [97, 122]
    ];

    private static $arrSymbols = [
        [48, 57],
        [65, 90],
        [97, 122]
    ];

    static function setSymbols($arrFlags){
        self::$arrSymbols = [];
        foreach($arrFlags as $flag){
            self::$arrSymbols[] = self::$codeSymbols[$flag];
        }
    }

    static function get(){
        $string = '';

        for ($i = 0; $i < self::$length; $i++) {
            $randArr = self::$arrSymbols[rand(0, sizeof(self::$arrSymbols))];
            $string .= chr(rand($randArr[0], $randArr[1]));
        }

        return $string;
    }

}