<?php

namespace cashbox\models;

use common\helpers\PriceHelper;
use common\models\Good;
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
 * @property Good good
 * @property float salePrice
 */
class CashboxItem extends \yii\db\ActiveRecord
{

    use PriceHelper;

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

    public function __set($name, $value){
        switch($name){
            case 'count':
                $this->changedValue = $value - $this->getOldAttribute('count');
                break;
        }

        return parent::__set($name, $value);
    }

    public function getSalePrice(){
        if(empty($this->good)){
            return 0;
        }

        return \Yii::$app->cashbox->priceType == 1 ? $this->good->wholesalePrice : $this->good->retailPrice;
    }

    public function getPriceForPiece(){
        $pieces = filter_var($this->good->num_opt, FILTER_SANITIZE_NUMBER_INT);

        if(empty($pieces)){
            return false;
        }

        return round(($this->salePrice/$pieces), 2);
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
     * @return float
     */
    public function getSum(){
        return $this->price * $this->count;
    }

    /**
     * @return float
     */
    public function getDiscountValue(){
        return ($this->originalPrice - $this->price) * $this->count;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGood(){
        return $this->hasOne(\backend\models\Good::className(), ['ID' => 'itemID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder(){
        return $this->hasOne(Order::className(), ['id' => 'orderID']);
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
        if($this->isNewRecord){
            $this->setAttributes([
                'name'          =>  empty($this->name) && !empty($this->good) ? $this->good->name : $this->name,
                'originalPrice' =>  $this->price,
                'categoryCode'  =>  $this->good->categoryCode,
                'deleted'       =>  0,
                'added'         =>  date('Y-m-d H:i:s')
            ]);
        }

        /*if(empty($this->orderID)){
            $this->orderID = \Yii::$app->request->cookies->getValue("cashboxOrderID");
        }

        if($this->isNewRecord || $this->isAttributeChanged('count')){
            $this->added = date('Y-m-d H:i:s');
        }

        $this->price = $this->originalPrice;*/

        return parent::beforeSave($insert);
    }
}
