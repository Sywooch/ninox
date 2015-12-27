<?php

namespace backend\models;

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
 */
class CashboxOrder extends \yii\db\ActiveRecord
{

    public $_items = [];
    private $sum = 0.00;
    public $discountPercent = 0;
    public $discountSize = 0.00;
    private $toPay = 0.00;
    private $itemsCount = 0;

    public function __get($name){
        switch($name){
            case 'items':
                if(empty($this->_items)){
                    \Yii::trace('getItems');
                    return $this->getItems();
                }

                return $this->_items;
                break;
            case 'itemsCount':
                \Yii::trace('itemsCount');
                return $this->itemsCount = count($this->_items);
                break;
            case 'sum':
                \Yii::trace('sum');
                return $this->calcSum();
                break;
            case 'toPay':
                \Yii::trace('toPay');
                return $this->calcToPay();
                break;
        }

        return parent::__get($name);
    }

    public function getItems(){
        if(!empty($this->_items)){
            return $this->_items;
        }

        $this->_items = CashboxItem::findAll(['orderID' =>  $this->id]);

        return $this->_items;
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

    public function beforeSave($insert){
        if($this->isNewRecord){
            $this->id = hexdec(uniqid());

            if(empty($this->responsibleUser)){
                $this->responsibleUser = \Yii::$app->request->cookies->getValue("cashboxManager", 0);
            }
        }elseif($this->isAttributeChanged('priceType')){
            $itemsIDs = $goods = [];

            $priceType = $this->priceType == 1 ? 'PriceOut1' : 'PriceOut2';

            foreach($this->items as $item){
                $itemsIDs[] = $item->itemID;
            }

            foreach(Good::find()->where(['in', 'ID', $itemsIDs])->each() as $good){
                $goods[$good->ID] = $good;
            }

            foreach($this->items as $item){
                $item->originalPrice = $goods[$item->itemID]->$priceType;
                $item->save();
            }
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cashboxOrders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'customerID', 'responsibleUser', 'priceType', 'deleted'], 'integer'],
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
            'customerID' => Yii::t('common', 'Customer ID'),
            'responsibleUser' => Yii::t('common', 'Responsible User'),
            'createdTime' => Yii::t('common', 'Created Time'),
            'doneTime' => Yii::t('common', 'Done Time'),
            'priceType' => Yii::t('common', 'Price Type'),
            'deleted' => Yii::t('common', 'Deleted'),
        ];
    }
}
