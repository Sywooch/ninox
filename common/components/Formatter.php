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

    public function asPhone($string){
        $string = preg_replace('/\D+/', '', $string);

        $number = substr($string, '-7');
        $string = substr($string, '0', '-7');

        $m = [];
        preg_match('/([0-9]{3})([0-9]{2})([0-9]{2})/', $number, $m);

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

    public function asLeftTime($leftTime, $format = 'Y M D H I'){
        $time = [
            'seconds'   =>  0,
            'minutes'   =>  0,
            'hours'     =>  0,
            'days'      =>  0,
            'months'    =>  0,
            'years'     =>  0
        ];


        if($leftTime < 60){
            $time['seconds'] = $leftTime;
            return $time;
        }

        $time['minutes'] = floor($leftTime / 60);

        $leftTime -= ($time['minutes'] * 60);

        $time['seconds'] = $leftTime;

        if(!empty($time['minutes'])){
            $time['hours'] = floor($time['minutes'] / 60);
            $time['minutes'] -= $time['hours'] * 60;
        }

        if(!empty($time['hours'])){
            $time['days'] = floor($time['hours'] / 24);
            $time['hours'] -= $time['days'] * 24;
        }

        if(!empty($time['days'])){
            $time['months'] = floor($time['days'] / 30);
            $time['days'] -= $time['months'] * 30;
        }

        if(!empty($time['months'])){
            $time['years'] = floor($time['months'] / 12);
            $time['months'] -= $time['years'] * 12;
        }

        return preg_replace_callback(['/Y(.|)/', '/m(.|)/', '/M(.|)/', '/d(.|)/', '/D(.|)/', '/H(.|)/', '/i(.|)/', '/I(.|)/', '/s(.|)/', '/S(.|)/'], function($matches) use($time){
            if(!array_key_exists(0, $matches)){
                return '';
            }

            $searchAttribute = preg_replace('/\W/', '', $matches['0']);

            $val = '';

            switch($searchAttribute){
                case 'Y':
                    if(!empty($time['years'])){
                        $val = $time['years'];
                    }
                    break;
                case 'm':
                    if(!empty($time['months'])){
                        $val = $time['months'];
                    }
                    break;
                case 'M':
                    if(!empty($time['months'])){
                        $val = \Yii::t('backend', '{months} месяцев', ['months' => $time['months']]);
                    }
                    break;
                case 'd':
                    if(!empty($time['days'])){
                        $val = $time['days'];
                    }
                    break;
                case 'D':
                    if(!empty($time['days'])){
                        $val = \Yii::t('backend', '{days} дней', ['days' => $time['days']]);
                    }
                    break;
                case 'h':
                    if(!empty($time['hours'])){
                        $val = $time['hours'];
                    }
                    break;
                case 'H':
                    if(!empty($time['hours'])){
                        $val = \Yii::t('backend', '{hours} часов', ['hours' => $time['hours']]);
                    }
                    break;
                case 'i':
                    if(!empty($time['minutes'])){
                        $val = $time['minutes'];
                    }
                    break;
                case 'I':
                    if(!empty($time['minutes'])){
                        $val = \Yii::t('backend', '{minutes} минут', ['minutes' => $time['minutes']]);
                    }
                    break;
                case 's':
                    if(!empty($time['seconds'])){
                        $val = $time['seconds'];
                    }
                    break;
                case 'S':
                    if(!empty($time['seconds'])){
                        $val = \Yii::t('backend', '{seconds} секунд', ['seconds' => $time['seconds']]);
                    }
                    break;
            }

            if(empty($val)){
                return $val;
            }

            return preg_replace("/{$searchAttribute}/", $val, $matches[0]);
        }, $format);
    }

    public function asPrice($value, $digitsAfterDec = 2){
        return round($value, $digitsAfterDec);
    }

}