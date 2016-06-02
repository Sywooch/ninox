<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 14.10.15
 * Time: 17:11
 */

namespace frontend\models;

use common\helpers\Formatter;
use common\helpers\PriceHelper;
use common\models\GoodOptions;
use common\models\GoodOptionsValue;
use common\models\GoodOptionsVariant;
use common\models\GoodsComment;
use yii\db\Query;

class Good extends \common\models\Good{

    use PriceHelper;

	public $priceRuleID = 0;                //ID примененного ценового правила
	public $priceForOneItem = 0;            //Цена за единицу товара
	public $priceModified = false;          //Триггер, срабатывающий на модификацию цен ценовым правилом
	public $isNew = false;                  //Флаг-новинка
	public $customerRule = 0;               //Персональное правило

    private $_options = [];

	public static function find(){
		return parent::find()
			->with('photos')
			->with('category')
            ->with('translations');
	}

	public function getFilters(){
		return $this->hasMany(GoodOptionsValue::className(), ['good' => 'ID'])
			->joinWith('goodOptions');
	}

	public function getReviews(){
		return $this
			->hasMany(GoodsComment::className(), ['goodID' => 'ID'])
			->where(['type' => 1])->orderBy(['date' => SORT_DESC])
			->with('childs');
	}

    public function getRelatedProducts(){
        return SborkaItem::find()
            ->where(['in', 'orderID',
                SborkaItem::find()
                    ->select('orderID')
                    ->where(['itemID' => $this->ID])
            ])
            ->andWhere("itemID != {$this->ID}")
            ->groupBy('itemID')
            ->orderBy('count(`itemID`) DESC')
            ->limit(10)
            ->all();
    }

    public function getRealRetailPrice(){
        return $this->priceRuleID == 0 && $this->discountType > 0 && $this->customerRule == 0 ? parent::getRealWholesalePrice() : parent::getRealRetailPrice();
    }

    public function afterFind(){
        parent::afterFind();

	    $this->num_opt = filter_var($this->num_opt, FILTER_SANITIZE_NUMBER_INT);

        $this->priceForOneItem = (!empty($this->num_opt) && $this->num_opt > 1) ? Formatter::getFormattedPrice(($this->wholesalePrice/$this->num_opt)) : 0;
        $this->isNew = (time() - strtotime($this->photodate)) <= (86400 * 10);
    }

    public function getOptions(){
        if(!empty($this->_options)){
            return $this->_options;
        }

        $options = [];

        $options = array_merge($options, $this->getDefaultOptions());

        $query = Query::create(new Query())
            ->select(['goodsoptions.name as option', 'goodsoptions_variants.value as value'])
            ->from(GoodOptionsValue::tableName().' goodsoptions_values')
            ->leftJoin(GoodOptionsVariant::tableName().' goodsoptions_variants', 'goodsoptions_values.value = goodsoptions_variants.id')
            ->leftJoin(GoodOptions::tableName().' goodsoptions', 'goodsoptions_values.option = goodsoptions.id')
            ->where(['goodsoptions_values.good' => $this->ID]);

        foreach($query->each() as $option){
            $options[$option['option']] = $option['value'];
        }

        return $this->_options = $options;
    }

	public function getCanBuy(){
/*		if($this->category->enabled){
			$parents = $this->category->parents;
			foreach($parents as $category){
				if(!$category->enabled){
					return false;
				}
			}
		}else{
			return false;
		}TODO: Большая нагрузка при хоть каком-то количестве товара в корзине*/

		return $this->enabled && ($this->count > 0 || $this->isUnlimited) && $this->PriceOut1 > 0 && $this->PriceOut2 > 0;
	}

    protected function getDefaultOptions(){
        $options = [];

        if(!empty($this->num_opt)){
            $options[\Yii::t('shop', 'Количество в упаковке')] = $this->num_opt.' '.\Yii::t('shop', $this->measure);
        }

        if(!empty($this->dimensions)){
            $options[\Yii::t('shop', 'Размеры')] = $this->dimensions;
        }

        if(!empty($this->height)){
            $options[\Yii::t('shop', 'Высота')] = $this->height;
        }

        if(!empty($this->width)){
            $options[\Yii::t('shop', 'Ширина')] = $this->width;
        }

        if(!empty($this->length)){
            $options[\Yii::t('shop', 'Длина')] = $this->length;
        }

        if(!empty($this->diameter)){
            $options[\Yii::t('shop', 'Диаметр')] = $this->diameter;
        }

        return $options;
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

	public function getMetaTitle(){
		return \Yii::t('shop',
			'{name}: купить оптом по цене {price} в интернет-магазине Krasota-Style.',
			[
				'name'  =>  $this->name,
				'price' =>  Formatter::getFormattedPrice($this->PriceOut1)
			]);
	}

	public function getMetaDescription(){
		return \Yii::t('shop',
			'Купить по оптовым ценам {name} на сайте Krasota-Style. Доставка по Украине, большой ассортимент товаров, мелкий и крупный опт.',
			['name'  =>  mb_strtolower($this->name)]);
	}

	public function getMetaKeywords(){
		return \Yii::t('shop',
			'{name}, {name2}, оптом, интернет-магазин, Krasota-Style',
			[
				'name'  =>  $this->name,
				'name2' =>  mb_strtolower($this->category->name)
			]);
	}

}