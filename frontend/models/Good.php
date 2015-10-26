<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 14.10.15
 * Time: 17:11
 */

namespace frontend\models;


use app\models\Message;
use common\helpers\GoodHelper;

class Good extends \common\models\Good{

    public $wholesale_price = 0;            //Оптовая цена текущая
    public $retail_price = 0;               //Розничная цена текущая
	public $wholesale_real_price = 0;       //Оптовая цена без скидки
	public $retail_real_price = 0;          //Розничная цена без скидки
	public $category = '';                  //Код категории
	public $priceRuleID = 0;                //ID примененного ценового правила
	public $priceForOneItem = 0;            //Цена за единицу товара
	public $reviewsCount = 0;               //Количество отзывов

	public $discountBlock = false;          //Блок скидки на товаре

    public function afterFind()
    {
	    parent::afterFind();

        //на товар работает отлично
        //на категорию - суммарно очень большой запрос получается :с
/*        foreach(Message::getGoodTranslate($this->ID) as $key => $value){
            $this->$key = $value;
        }*/

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

	    $this->wholesale_real_price = $this->$wholesale_price;
	    $this->retail_real_price = $this->$retail_price;

	    $this->num_opt = preg_replace('/D+/', '', $this->num_opt);

        switch($this->discountType){
            case 1:
                //Размер скидки в деньгах
                $this->wholesale_price = $this->$wholesale_price - $this->discountSize;
                $this->retail_price = $this->$retail_price - $this->discountSize;
                break;
            case 2:
                //Размер скидки в процентах
                $this->wholesale_price = round($this->$wholesale_price - ($this->$wholesale_price / 100 * $this->discountSize), 2);
                $this->retail_price = round($this->$retail_price - ($this->$retail_price / 100 * $this->discountSize), 2);
                break;
            default:
                $this->wholesale_price = $this->$wholesale_price;
                $this->retail_price = $this->$retail_price;
                break;
        }

	    $this->priceForOneItem = (!empty($this->num_opt) && $this->num_opt > 1) ? GoodHelper::getPriceFormat(($this->wholesale_price/$this->num_opt)) : 0;

    }

    public function __get($name){
	    switch($name){
		    case 'inCart':
			    return \Yii::$app->cart->has($this->ID);
			    break;
		    default:
			    return parent::__get($name);
			    break;
	    }
    }

    public function __set($name, $value){
	    parent::__set($name, $value);
        if($name == 'discountSize' || $name == 'discountType'){
	        $this->afterFind();
        }
    }

}