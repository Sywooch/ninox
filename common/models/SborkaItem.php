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

    public static $DISCOUNT_TYPES = [
        '0' =>  'Не выбрано',
        '1' =>  'Отнять сумму',
        '2' =>  'Отнять процент'
    ];

    public $price;
    public $addedCount = 0;
    public $storeID = 0;

    public function getGood(){
        return $this->hasOne(Good::className(), ['ID' => 'itemID']);
    }

    public function getCategory(){
        return $this->hasOne(Category::className(), ['Code' => 'categoryCode']);
    }

    public function getPhoto(){
        \Yii::trace($this->itemID);
        return $this->good->photo;
    }

    public function afterFind(){
        switch($this->discountType){
            case '1':
                //Размер скидки в деньгах
                $this->price = $this->originalPrice - $this->discountSize;
                break;
            case '2':
                //Размер скидки в процентах
                $this->price = round($this->originalPrice - ($this->originalPrice / 100 * $this->discountSize), 2);
                break;
            default:
                $this->price = $this->originalPrice;
                break;
        }

        return parent::afterFind();
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
