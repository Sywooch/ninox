<?php

namespace cashbox\models;

use common\models\SborkaItem;
use Yii;
use yii\web\BadRequestHttpException;

/**
 * This is the model class for table "cashboxItems".
 *
 * @property string $itemID
 * @property string $orderID
 * @property string $name
 * @property integer $count
 * @property double $originalPrice
 * @property integer $discountType
 * @property integer $discountSize
 * @property integer $priceRuleID
 * @property string $categoryCode
 * @property integer $customerRule
 * @property integer $deleted
 * @property integer $added
 */
class CashboxItem extends \yii\db\ActiveRecord
{

    public $price = 0;
    public $changedValue = 0;
    public $return = false;
    public $priceModified = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cashboxItems';
    }

    public function afterFind(){
        switch($this->discountType){
            case '1':
                $this->price = $this->originalPrice - $this->discountSize;
                break;
            case '2':
                $this->price = round($this->originalPrice - ($this->originalPrice / 100 * $this->discountSize), 2);
                break;
            default:
                $this->price = $this->originalPrice;
                break;
        }

        return parent::afterFind();
    }

    public function __set($name, $value){
        switch($name){
            case 'count':
                $this->changedValue = $value - $this->getOldAttribute('count');
                break;
        }

        return parent::__set($name, $value);
    }

    public function loadAssemblyItem($assemblyItem, $orderID){
        if($assemblyItem instanceof SborkaItem == false){
            throw new BadRequestHttpException();
        }

        $attributes = [
            'categoryCode',
            'count',
            'customerRule',
            'discountSize',
            'discountType',
            'itemID',
            'name',
            'originalPrice',
            'priceRuleID'
        ];

        foreach($attributes as $key => $attribute){
            if(isset($this->$key)){
                $this->$key = $assemblyItem->$attribute;
            }else{
                $this->$attribute = $assemblyItem->$attribute;
            }
        }

        $this->orderID = $orderID;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['itemID', 'orderID'], 'required'],
            [['itemID', 'orderID', 'added', 'count', 'discountType', 'discountSize', 'priceRuleID', 'customerRule', 'deleted'], 'integer'],
            [['originalPrice'], 'number'],
            [['name', 'categoryCode'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'itemID' => Yii::t('common', 'Item ID'),
            'orderID' => Yii::t('common', 'Order ID'),
            'name' => Yii::t('common', 'Name'),
            'count' => Yii::t('common', 'Count'),
            'originalPrice' => Yii::t('common', 'Original Price'),
            'discountType' => Yii::t('common', 'Discount Type'),
            'discountSize' => Yii::t('common', 'Discount Size'),
            'priceRuleID' => Yii::t('common', 'Price Rule ID'),
            'categoryCode' => Yii::t('common', 'Category Code'),
            'customerRule' => Yii::t('common', 'Customer Rule'),
            'deleted' => Yii::t('common', 'Deleted'),
            'added' =>  \Yii::t('common', 'Added'),
        ];
    }

    public function afterDelete(){
        if($this->return){
            $good = Good::findOne($this->itemID);

            if($good){
                $good->count += $this->count;

                $good->save(false);
            }
        }

        return parent::afterDelete();
    }

    public function beforeSave($insert){
        if($this->isNewRecord && empty($this->orderID)){
            $this->orderID = \Yii::$app->request->cookies->getValue("cashboxOrderID");
        }

        if($this->isNewRecord || $this->isAttributeChanged('count')){
            $this->added = date('Y-m-d H:i:s');
            \Yii::trace($this->added);
        }

        $this->price = $this->originalPrice;

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes){
        if($this->changedValue != 0){
            $good = Good::findOne(['ID' => $this->itemID]);
            $good->count -= $this->changedValue;

            $good->save(false);
        }

        return parent::afterSave($insert, $changedAttributes);
    }
}
