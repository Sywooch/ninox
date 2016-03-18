<?php

namespace cashbox\models;

use common\models\CashboxMoney;
use Yii;

/**
 * This is the model class for table "cashboxOrders".
 *
 * @property string $id
 * @property string $customerID
 * @property integer $responsibleUser
 * @property string $createdTime
 * @property string $doneTime
 * @property integer $priceType
 * @property integer $deleted
 * @property double $sum
 * @property double $toPay
 * @property double $actualAmount
 * @property array $items
 * @property integer $postpone
 * @property string $promoCode
 */
class CashboxOrder extends \yii\db\ActiveRecord
{

    public $_items = [];
    public $_createdItems = [];
    public $discountPercent = 0;
    public $discountSize = 0.00;
    private $sum = 0.00;
    private $toPay = 0.00;
    private $itemsCount = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cashboxOrders';
    }

    public function __get($name){
        switch($name){
            case 'items':
                return $this->getItems();
                break;
            case 'createdItems':
                return $this->getCreatedOrderItems();
                break;
            case 'createdOrderSum':
                return $this->calcCreatedOrderSum();
                break;
            case 'createdOrderItemsCount':
                return $this->calcCreatedOrderItems();
                break;
            case 'itemsCount':
                return $this->itemsCount = count($this->getItems());
                break;
            case 'sum':
                return $this->sum = $this->calcSum();
                break;
            case 'toPay':
                return $this->toPay = $this->calcToPay();
                break;
        }

        return parent::__get($name);
    }

    public function getItems(){
        $items = [];

        foreach(CashboxItem::find()->where(['orderID' =>  $this->id])->each() as $item){
            $items[$item->itemID] = $item;
        }

        return $items;
    }

    public function getCreatedOrderItems(){
        if(empty($this->createdOrder)){
            return [];
        }

        return AssemblyItem::findAll(['orderID' => $this->createdOrder]);
    }

    public function calcCreatedOrderSum(){
        $sum = 0;

        foreach($this->createdItems as $item){
            $sum += $item->price * $item->count;
        }

        return $sum;
    }

    public function calcCreatedOrderItems(){
        return count($this->createdItems);
    }

    public function calcSum(){
        $sum = 0;

        foreach($this->items as $item){
            $sum += $item->originalPrice * $item->count;
        }

        return $this->sum = $sum;
    }

    public function calcToPay(){
        $sum = 0;

        foreach($this->items as $item){
            $sum += $item->price * $item->count;
        }

        return $this->toPay = $sum;
    }

    public function getAmount(){
        return CashboxMoney::find()->select('amount')->where(['order' => $this->id])->scalar();
    }

    public function beforeSave($insert){
        if($this->isNewRecord){
            \Yii::trace('new record!');
            $this->id = hexdec(uniqid());

            if(empty($this->responsibleUser)){
                $this->responsibleUser = \Yii::$app->request->cookies->getValue("cashboxManager", 0);
            }
        }elseif($this->isAttributeChanged('priceType')){
            $this->changePriceType($this->priceType);
        }

        return parent::beforeSave($insert);
    }

    /**
     * Меняет тип цен на товары в кассе
     *
     * @param string $priceType
     *
     * @return bool всегда true
     */
    public function changePriceType($priceType = 'wholesale'){
        switch($priceType){
            case 0:
            case 'rozn':
            case 'retail':
                $priceType = 'PriceOut2';
                break;
            case 1:
            case 'opt':
            case 'wholesale':
                $priceType = 'PriceOut1';
                break;
        }

        $cashboxItems = CashboxItem::findAll(['orderID' => $this->id]);

        $itemsIDs = $goods = [];

        foreach($cashboxItems as $item){
            $itemsIDs[] = $item->itemID;
        }

        foreach(Good::find()->where(['in', 'ID', $itemsIDs])->each() as $good){
            $goods[$good->ID] = $good;
        }

        foreach($cashboxItems as $item){
            if(isset($goods[$item->itemID])){
                $item->originalPrice = $goods[$item->itemID]->$priceType;
                $item->save(false);
            }
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'customerID', 'responsibleUser', 'priceType', 'deleted', 'postpone'], 'integer'],
            [['actualAmount'], 'double'],
            [['createdTime', 'doneTime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'actualAmount' => Yii::t('common', 'actualAmount'),
            'customerID' => Yii::t('common', 'Customer ID'),
            'responsibleUser' => Yii::t('common', 'Responsible User'),
            'createdTime' => Yii::t('common', 'Created Time'),
            'doneTime' => Yii::t('common', 'Done Time'),
            'priceType' => Yii::t('common', 'Price Type'),
            'deleted' => Yii::t('common', 'Deleted'),
        ];
    }
}