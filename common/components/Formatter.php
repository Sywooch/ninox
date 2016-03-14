<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 29.02.16
 * Time: 13:52
 */

namespace common\components;


class Formatter extends \yii\i18n\Formatter
{

    public function asPrice($value, $digitsAfterDec = 2){
        return round($value, $digitsAfterDec);
    }

}