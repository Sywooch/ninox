<?php

namespace backend\models;

use Yii;

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
 * @property string $category
 * @property integer $customerRule
 * @property integer $deleted
 */
class CashboxItem extends \yii\db\ActiveRecord
{

    public $price = 0;
    public $changedValue = 0;

    public function afterFind(){
        $this->price = $this->originalPrice;

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


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cashboxItems';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['itemID', 'orderID'], 'required'],
            [['itemID', 'orderID', 'count', 'discountType', 'discountSize', 'priceRuleID', 'customerRule', 'deleted'], 'integer'],
            [['originalPrice'], 'number'],
            [['name', 'category'], 'string', 'max' => 255],
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
            'category' => Yii::t('common', 'Category'),
            'customerRule' => Yii::t('common', 'Customer Rule'),
            'deleted' => Yii::t('common', 'Deleted'),
        ];
    }

    public function beforeSave($insert){
        if($this->isNewRecord && empty($this->orderID)){
            $this->orderID = \Yii::$app->request->cookies->getValue("cashboxOrderID");
        }

        if($this->changedValue != 0){
            $good = Good::findOne(['ID' => $this->itemID]);
            $good->count -= $this->changedValue;

            $good->save(false);
        }

        $this->price = $this->originalPrice;

        return parent::beforeSave($insert);
    }
}
