<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 09.11.15
 * Time: 13:48
 */

namespace backend\components;

use yii\helpers\Json;

class Formatter extends \yii\i18n\Formatter{

    public function asPhone($string){
        $string = preg_replace('/\D+/', '', $string);

        $number = substr($string, '-7');
        $string = substr($string, '0', '-7');

        $m = [];
        preg_match("/([0-9]{3})([0-9]{2})([0-9]{2})/", $number, $m);

        unset($m[0]);

        $number = implode('-', $m);

        if(strlen($string) > 3){
            $code = substr($string, '-3');
            $string = substr($string, '0', '-3');

            return '+'.$string.'('.$code.')'.$number;
        }else{
            return '+38('.$string.')'.$number;
        }

    }

}