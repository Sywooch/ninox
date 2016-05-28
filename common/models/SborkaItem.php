<?php

namespace common\models;

use Yii;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "sborka".
 *
 * @property integer $ID
 * @property integer $itemID
 * @property integer $orderID
 * @property string $name
 * @property integer $count
 * @property integer $added
 * @property integer $nalichie
 * @property integer $zamena
 * @property integer $nezakaz
 * @property integer $vzakaz
 * @property integer $realyCount
 * @property integer $originalCount
 * @property double $originalPrice
 * @property integer $discountSize
 * @property integer $discountType
 * @property Good $good
 */
class SborkaItem extends \yii\db\ActiveRecord
{

    const DISCOUNT_TYPE_UNDEFINED = 0;
    const DISCOUNT_TYPE_SUM = 1;
    const DISCOUNT_TYPE_PERCENT = 2;
    const DISCOUNT_TYPE_FIXED_SUM = 3;

    public static $DISCOUNT_TYPES = [
        self::DISCOUNT_TYPE_UNDEFINED =>  'Не выбрано',
        self::DISCOUNT_TYPE_SUM =>  'Отнять сумму',
        self::DISCOUNT_TYPE_PERCENT =>  'Отнять процент',
    ];

    public $addedCount = 0;
    public $storeID = 0;

    public function getGood(){
        return $this->hasOne(Good::className(), ['ID' => 'itemID']);
    }

    public function getCategory(){
        return $this->hasOne(Category::className(), ['Code' => 'categoryCode']);
    }

    public function getPrice(){
        switch($this->discountType){
            case self::DISCOUNT_TYPE_SUM:
                //Размер скидки в деньгах
                $price = $this->originalPrice - $this->discountSize;
                break;
            case self::DISCOUNT_TYPE_PERCENT:
                //Размер скидки в процентах
                $price = round($this->originalPrice - ($this->originalPrice / 100 * $this->discountSize), 2);
                break;
            case self::DISCOUNT_TYPE_FIXED_SUM:
                $price = $this->discountSize;
                break;
            default:
                $price = $this->originalPrice;
                break;
        }

        return $price;
    }

    public function getPhoto(){
        \Yii::trace($this->itemID);
        return $this->good->photo;
    }

    public function returnToStore($storeID = 0){
        $this->good->count += $this->count;
        $this->good->save(false);
        if(!empty($storeID)){
            $shopGood = ShopGood::find()->where(['shopID' => $storeID, 'itemID' => $this->good->ID])->one();
            if($shopGood){
                $shopGood->count += $this->count;
                $shopGood->save(false);
            }
        }

        return true;
    }

    public function beforeSave($insert){
        if(empty($this->realyCount)){
            $this->realyCount = 0;
        }

        if($this->isNewRecord){
            if(empty($this->added)){
                $this->added = time();
            }

            $this->originalCount = $this->count;
        }

        if($this->isAttributeChanged('count')){
            $this->good->count += $this->addedCount;
            $this->good->save(false);
            if(!empty($this->storeID)){
                $shopGood = ShopGood::find()->where(['shopID' => $this->storeID, 'itemID' => $this->good->ID])->one();
                if($shopGood){
                    $shopGood->count += $this->addedCount;
                    $shopGood->save(false);
                }
            }
        }

        return parent::beforeSave($insert);
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        return parent::save($runValidation, $attributeNames);
    }

    public function __set($name, $value){
        switch($name){
            case 'count':
                $this->addedCount = $value - $this->getOldAttribute('count');
                break;
        }

        return parent::__set($name, $value);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sborka';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['itemID', 'orderID', 'count', 'added', 'nalichie', 'zamena', 'nezakaz', 'vzakaz', 'realyCount', 'originalCount', 'discountSize', 'discountType'], 'integer'],
            [['name', 'categoryCode'], 'string'],
            [['name', 'count', 'originalPrice'], 'required'],
            [['originalPrice'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'itemID' => 'ID товара',
            'orderID' => 'ID заказа',
            'name' => 'Название',
            'count' => 'Количество',
            'added' => 'Добавлено',
            'nalichie' => 'Наличие',
            'zamena' => 'Zamena',
            'nezakaz' => 'Не заказывать',
            'vzakaz' => 'Vzakaz',
            'realyCount' => 'Realy Count',
            'originalCount' => 'Original Count',
            'originalPrice' => 'Полная цена',
            'discountSize' => 'Размер скидки',
            'discountType' => 'Тип скидки',
            'categoryCode' => 'Код категории',
        ];
    }
}
