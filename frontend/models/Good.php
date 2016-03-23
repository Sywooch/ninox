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
use common\models\GoodsPhoto;
use yii\db\Query;

class Good extends \common\models\Good{

    use PriceHelper;
    /**
     * @deprecated use wholesalePrice
     */
    public $wholesale_price = 0;            //Оптовая цена текущая

    /**
     * @deprecated use retailPrice
     */
    public $retail_price = 0;               //Розничная цена текущая

    /**
     * @deprecated use wholesaleRealPrice
     */
	public $wholesale_real_price = 0;       //Оптовая цена без скидки

    /**
     * @deprecated use retailRealPrice
     */
	public $retail_real_price = 0;          //Розничная цена без скидки
	//public $category = '';                //Код категории //Выпилено: Николай Гилко. Для получения кода категории используйте $categorycode
	public $priceRuleID = 0;                //ID примененного ценового правила
	public $priceForOneItem = 0;            //Цена за единицу товара
	//public $reviewsCount = 0;               //Количество отзывов
	public $priceModified = false;          //Триггер, срабатывающий на модификацию цен ценовым правилом
	public $isNew = false;                  //Флаг-новинка
    public $canBuy = true;
	public $customerRule = 0;               //Персональное правило

    private $_options = [];

	public function getReviewsCount(){
		return GoodsComment::find()->where(['goodID' => $this->ID])->count();
	}

	public function getReviews(){
		return GoodsComment::find()->where(['goodID' => $this->ID])->all();
	}

    public function getRealRetailPrice()
    {
        return $this->priceRuleID == 0 && $this->discountType > 0 ? parent::getRealWholesalePrice() : parent::getRealRetailPrice();
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
            case 'dopPhoto':
                return GoodsPhoto::find()->where(['itemid' => $this->ID])->all();
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