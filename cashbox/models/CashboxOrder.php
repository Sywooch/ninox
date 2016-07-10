<?php

namespace cashbox\models;

use backend\models\Customer;
use common\models\CashboxMoney;
use backend\models\History;
use common\models\Siteuser;
use yii;

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
 * @property int $source
 * @property int $createdOrderID
 * @property int $itemsCount
 * @property History $createdOrder
 * @property int|mixed customer
 * @property int return
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

    public function getCreatedItems(){
        return $this->getCreatedOrderItems();
    }

    public function getItems(){
        return $this->hasMany(CashboxItem::className(), ['orderID' => 'id']);
    }

    public function getCreatedOrder(){
        return $this->hasOne(Order::className(), ['id' => 'createdOrderID']);
    }

    public function getCreatedOrderSum(){
        if(empty($this->createdOrder)){
            return 0;
        }

        return $this->createdOrder->actualAmount;
    }

    public function getCustomer(){
        return $this->hasOne(Customer::className(), ['ID' => 'customerID']);
    }

    public function getManager(){
        return $this->hasOne(Siteuser::className(), ['id' => 'responsibleUser']);
    }

    public function getItemsCount(){
        return count($this->items);
    }

    public function getSum(){
        return $this->calcSum();
    }

    public function getToPay(){
        return $this->calcToPay();
    }

    public function getCreatedOrderItemsCount(){
        return $this->calcCreatedOrderItems();
    }

    /**
     * @param $itemID
     * @return CashboxItem|bool
     */
    public function getItem($itemID){
        foreach($this->items as $item){
            if($item->itemID == $itemID){
                return $item;
            }
        }

        return false;
    }

    public function getCreatedOrderItems(){
        if(empty($this->createdOrderID)){
            return [];
        }

        return AssemblyItem::findAll(['orderID' => $this->createdOrderID]);
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
        return CashboxMoney::find()->select('amount')->where(['order' => $this->createdOrderID])->scalar();
    }

    public function beforeSave($insert){
        if(empty($this->source)){
            $this->source = \Yii::$app->params['configuration']->ID;
        }

        if($this->isNewRecord){
            $this->setAttributes([
                'id'            =>  hexdec(uniqid()),
                'createdTime'   =>  date('Y-m-d H:i:s')
            ]);
            if(empty($this->responsibleUser)){
                $this->responsibleUser = \Yii::$app->request->cookies->getValue('cashboxManager', 0);
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
            [['id', 'customerID', 'responsibleUser', 'priceType', 'deleted', 'postpone', 'createdOrderID'], 'integer'],
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
            'id'                => Yii::t('common', 'ID'),
            'actualAmount'      => Yii::t('common', 'Сумма'),
            'customerID'        => Yii::t('common', 'Клиент'),
            'responsibleUser'   => Yii::t('common', 'Менеджер'),
            'createdTime'       => Yii::t('common', 'Дата создания'),
            'doneTime'          => Yii::t('common', 'Дата завершения'),
            'priceType'         => Yii::t('common', 'Тип цены'),
            'deleted'           => Yii::t('common', 'Удалён'),
        ];
    }
}
