<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 14.10.15
 * Time: 17:11
 */

namespace frontend\models;


use app\models\Message;

class Good extends \common\models\Good{

    public $wholesale_price = 0;    //Оптовая цена
    public $retail_price = 0;       //Розничная цена

    public function afterFind()
    {
        foreach(Message::getGoodTranslate($this->ID) as $key => $value){
            $this->$key = $value;
        }

        if(!\Yii::$app->user->isGuest && isset(\Yii::$app->user->identity['PriceGroup'])){
            switch(\Yii::$app->user->identity['PriceGroup']){
                case '2':
                    $wholesale_price = 'PriceOut3';
                    $retail_price = 'PriceOut4';
                    break;
                case '1':
                default:
                    $wholesale_price = 'PriceOut1';
                    $retail_price = 'PriceOut2';
                    break;
            }
        }else{
            $wholesale_price = 'PriceOut1';
            $retail_price = 'PriceOut2';
        }

        switch ($this->discountType) {
            case '1':
                //Размер скидки в деньгах
                $this->wholesale_price = $this->$wholesale_price - $this->discountSize;
                $this->retail_price = $this->$retail_price - $this->discountSize;
                break;
            case '2':
                //Размер скидки в процентах
                $this->wholesale_price = round($this->$wholesale_price - ($this->$wholesale_price / 100 * $this->discountSize), 2);
                $this->retail_price = round($this->$retail_price - ($this->$retail_price / 100 * $this->discountSize), 2);
                break;
            default:
                $this->wholesale_price = $this->$wholesale_price;
                $this->retail_price = $this->$retail_price;
                break;
        }
    }

    public function __set($name, $value){
        if($name == 'discountSize' || $name = 'discountType'){
            $this->afterFind();
        }

        parent::__set($name, $value);
    }

}