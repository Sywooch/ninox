<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 14.10.15
 * Time: 17:11
 */

namespace frontend\models;

use common\helpers\Formatter;

class Good extends \common\models\Good{

    public $wholesale_price = 0;            //Оптовая цена текущая
    public $retail_price = 0;               //Розничная цена текущая
	public $wholesale_real_price = 0;       //Оптовая цена без скидки
	public $retail_real_price = 0;          //Розничная цена без скидки
	public $category = '';                  //Код категории
	public $priceRuleID = 0;                //ID примененного ценового правила
	public $priceForOneItem = 0;            //Цена за единицу товара
	public $reviewsCount = 0;               //Количество отзывов
	public $priceModified = false;          //Триггер, срабатывающий на модификацию цен ценовым правилом
	public $isNew = false;                  //Флаг-новинка
    public $canBuy = true;
	public $customerRule = 0;               //Персональное правило

    public function afterFind()
    {
	    parent::afterFind();

        //на товар работает отлично
        //на категорию - суммарно очень большой запрос получается :с
/*        foreach(Message::getGoodTranslate($this->ID) as $key => $value){
            $this->$key = $value;
        }*/

	    $this->wholesale_real_price = $this->PriceOut1;
	    $this->retail_real_price = (($this->priceRuleID == 0 && $this->discountType > 0) ? $this->PriceOut1 : $this->PriceOut2);
	    $this->num_opt = preg_replace('/D+/', '', $this->num_opt);

        switch($this->discountType){
            case 1:
                //Размер скидки в деньгах
                $this->wholesale_price = $this->wholesale_real_price - $this->discountSize;
                $this->retail_price = $this->retail_real_price - $this->discountSize;
                break;
            case 2:
                //Размер скидки в процентах
                $this->wholesale_price = round($this->wholesale_real_price - ($this->wholesale_real_price / 100 * $this->discountSize), 2);
                $this->retail_price = round($this->retail_real_price - ($this->retail_real_price / 100 * $this->discountSize), 2);
                break;
            default:
                $this->wholesale_price = $this->wholesale_real_price;
                $this->retail_price = $this->retail_real_price;
                break;
        }

	    if($this->priceRuleID == 0 && $this->discountType > 0){
		    $this->wholesale_real_price = $this->wholesale_price;
		    $this->retail_real_price = $this->retail_price;
	    }

	    $this->priceForOneItem = (!empty($this->num_opt) && $this->num_opt > 1) ? Formatter::getFormattedPrice(($this->wholesale_price/$this->num_opt)) : 0;
	    $this->isNew = (time() - strtotime($this->photodate)) <= (86400 * 10);
    }

	public static function find(){
		return parent::find()->
			select('`goods`.*, `goodsgroups`.`Code` AS `category`')->
			leftJoin('goodsgroups', '`goods`.`GroupID` = `goodsgroups`.`ID`', []);
	}

    public function __get($name){
	    switch($name){
		    case 'inCart':
			    return \Yii::$app->cart->has($this->ID);
			    break;
            case 'video':
                return true; //TODO: return video from dopVideo table
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