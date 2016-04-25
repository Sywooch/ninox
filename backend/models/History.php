<?php

namespace backend\models;

use backend\components\Sms;
use common\models\Siteuser;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\ConflictHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class History
 * @package backend\models
 * @author  Nikolai Gilko   <n.gilko@gmail.com>
 * @property Customer $customer
 * @property Siteuser $responsibleUser
 */
class History extends \common\models\History
{

    private $real_summ;
    private $isWholesale;
    public $summ;


    public $sum;

    public $status_1     =   'Не звонили';
    public $status_2     =   'Подготовка заказа';
    public $status_3     =   'Абонент не отвечает';
    public $status_4     =   '<b>Ожидается оплата</b>';
    public $status_5     =   'Заказ отправлен';
    public $status_6     =   'Оплачено';
    public $status_7     =   'Возврат';
    public $status_8     =   '<small>Отправлен - оплачено</small>';
    public $status_9     =   '<small><b>Отправлен - оплаты нет</b></small>';
    public $status_10    =   'Ожидает отправки';

    private $_customer;
    private $_responsibleUser = null;

    public static function find()
    {
        return parent::find()->with('items')->with('customer');
    }


    public function getID(){
        return $this->id;
    }

    public function getCustomer(){
        return $this->hasOne(Customer::className(), ['ID' => 'customerID']);
    }

    public function getNewCustomer(){
        return sizeof($this->customer->orders) > 1;
    }

    public function getPayOnCard(){
        switch($this->paymentType){
            case 2:
                return true;
                break;
            case 1:
            case 3:
            default:
                return false;
                break;
        }
    }

    public function sendMessage($type){
        switch($type){
            case 'card':
                return $this->sendCardSms();
                break;
            case 'sms':
                return $this->sendSms();
                break;
        }
    }

    public function getResponsibleUser(){
        if(empty($this->_responsibleUser)){
            $user = Siteuser::findOne($this->responsibleUserID);

            if(!$user){
                $user = new Siteuser();
            }

            $this->_responsibleUser = $user;
        }

        return $this->_responsibleUser;
    }

    public function sendSms(){
        $messageID = 0;

        switch($this->status){
            case self::STATUS_NOT_CALLED:
                $messageID = Sms::MESSAGE_CANT_CALL_ID;
                break;
            case self::STATUS_NOT_PAYED:
                //$messageID = Sms::MESSAGE_CANT_CALL_ID;
                //отправить смс с номером карты
                break;
            case self::STATUS_WAIT_DELIVERY:
                $messageID = Sms::MESSAGE_ORDER_WAIT_DELIVERY_ID;
                //
                break;
            case self::STATUS_DELIVERED:
                //$messageID = Sms::MESSAGE_CANT_CALL_ID;
                break;
        }

        $result = \Yii::$app->sms->sendPreparedMessage($this, $messageID);

        if($result == 200){
            $this->smsSendDate = date('Y-m-d H:i:s');
        }

        return $result;
    }

    public function sendCardSms(){
        $messageID = 0;

        switch($this->status){
            case self::STATUS_NOT_CALLED:
                break;
            case self::STATUS_PROCESS:
                break;
            case self::STATUS_NOT_PAYED:
                break;
            case self::STATUS_WAIT_DELIVERY:
                $messageID = Sms::MESSAGE_PAYMENT_CONFIRMED_ID;
                break;
            case self::STATUS_DELIVERED:
                break;
        }

        $result = \Yii::$app->sms->sendPreparedMessage($this, $messageID);

        if($result == 200){
            $this->smsSendDate = date('Y-m-d H:i');
        }

        return $result;
    }

    public function beforeSave($insert){
        if($this->isAttributeChanged('confirmed') && $this->confirmed == 1){
            $this->confirmDate = date('Y-m-d H:i:s');
        }

        if($this->isAttributeChanged('done') && $this->done == 1){
            $this->doneDate = date('Y-m-d H:i:s');
        }

        if($this->isAttributeChanged('takeOrder') && $this->takeOrder == 1){
            $this->takeOrderDate = date('Y-m-d H:i:s');
        }

        if($this->isAttributeChanged('takeTTNMoney') && $this->takeTTNMoney == 1){
            $this->takeTTNMoneyDate = date('Y-m-d H:i:s');
        }

        if($this->isAttributeChanged('moneyConfirmed') && $this->moneyConfirmed == 1){
            $this->moneyConfirmed = date('Y-m-d H:i:s');
            \Yii::$app->sms->sendPreparedMessage($this, Sms::MESSAGE_PAYMENT_CONFIRMED_ID);
        }

        if($this->status != $this->getCurrentStatus()) {
            $this->status = $this->getCurrentStatus();
        }

        if($this->isAttributeChanged('status')){
            $this->statusChangedDate = date('Y-m-d H:i:s');
        }

        //$this->status = $this->getCurrentStatus();

        $this->hasChanges = 1;

        return parent::beforeSave($insert);
    }

    public function behaviors(){
        if(!$this->isNewRecord){
            return [
                'LoggableBehavior' => [
                    'class' => 'sammaye\audittrail\LoggableBehavior',
                    'ignored' => [
                        'Name2',
                        'added'
                    ],
                ]
            ];
        }else{
            return [];
        }
    }

    public static function ordersQuery($options = []){
        $query = self::find()->orderBy('id DESC');

        if(isset($options['thisOrder'])){
            $query->andWhere('id != '.$options['thisOrder']);
        }

        if(isset($options['queryParts']) && !empty($options['queryParts'])){
            foreach($options['queryParts'] as $part){
                $query->andWhere($part);
            }
        }

        if(isset($options['where'])){
            $query->andWhere($options['where']);
        }

        return $query;
    }

    public static function ordersDataProvider($options = []){
        $query = self::ordersQuery($options);

        $ADPConfig = [
            'query' =>  $query,
        ];

        if(isset($options['limit'])){
            $ADPConfig['pagination']['pageSize'] = $options['limit'];
        }

        $ordersDataProvider = new ActiveDataProvider($ADPConfig);

        return $ordersDataProvider;
    }

    /**
     * @param string $priceType
     * @return bool
     */
    public function recalculatePrices($priceType = 'opt')
    {
        switch ($priceType) {
            case 'opt':
            case 'wholesale':
            case '1':
                $priceType = 'PriceOut1';
                break;
            case 'rozn':
            case 'retail':
            case '0':
                $priceType = 'PriceOut2';
                break;
        }

        foreach ($this->items as $item) {
            if(!empty($item->good)) {
                $item->originalPrice = $item->good->$priceType;
                $item->save(false);
            }
        }

        return true;
    }

    /**
     * @deprecated
     * @return bool
     */
    public function isOpt(){
        return $this->isWholesale();
    }

    /**
     * Возвращает, оптовый-ли заказ
     *
     * @return bool
     */
    public function isWholesale(){
        if(empty($this->isWholesale)){
           $this->isWholesale = ($this->orderSum >= 800);
        }

        return $this->isWholesale;
    }

    public function getOrderSum(){
        if(empty($this->sum)){
            $this->sum = SborkaItem::find()->select("SUM((`originalPrice` * `originalCount`))")->where(['orderID' => $this->id])->scalar();
        }

        return $this->sum;
    }

    /**
     * @deprecated
     * @return double
     */
    public function orderSumm(){
        return $this->orderSum;
    }

    /**
     * @return mixed
     * @deprecated use $this->getRealSum() or $this->realSum
     */
    public function orderRealSumm(){
        if(!empty($this->real_summ)){
            return $this->real_summ;
        }

        foreach(SborkaItem::findAll(['orderID' => $this->id]) as $item){
            $this->real_summ += ($item->price * $item->count);
        }

        return $this->real_summ;
    }

    /**
     * Возвращает реальную стоимость заказа
     *
     * @return double
     */
    public function getRealSum(){
        if(!empty($this->real_summ)){
            return $this->real_summ;
        }

        $this->real_summ = 0;

        foreach($this->items as $item){
            $this->real_summ += ($item->price * $item->count);
        }

        return $this->real_summ;
    }

    public function clearControl(){
        foreach($this->items as $item) {
            $item->realyCount = 0;

            $item->save(false);
        }
    }

    public function getNotControlledGoods(){
        $items = [];

        foreach($this->items as $item){
            if(!$item->controlled){
                $items[] = $item;
            }
        }

        return $items;
    }

    public function getNotControlledGoodsCount(){
        return sizeof($this->notControlledGoods);
    }

    public function getNotControlledItemsCount(){
        $count = 0;

        foreach($this->notControlledGoods as $item){
            $count += ($item->originalCount - $item->realyCount);
        }

        return $count;
    }

    public function getControlledGoods(){
        $items = [];

        foreach($this->items as $item){
            if($item->controlled){
                $items[] = $item;
            }
        }

        return $items;
    }


    public function getControlled(){
        if(sizeof($this->notControlledGoods) > 0){
            return false;
        }

        return true;
    }

    public function controlItem($itemID, $count = 1){
        if(!isset($this->items[$itemID])){
            throw new NotFoundHttpException("Товар с ID {$itemID} не найден в заказе #{$this->number} (ID {$this->ID})");
        }

        $item = $this->items[$itemID];

        if($item->controlled){
            throw new ConflictHttpException("Нельзя подтвердить подтверждённый товар дважды!");
        }

        $item->realyCount += $count;
        $item->save(false);
    }


    /**
     *
     * Возвращает колл-во заказов, сделаных из магазина и из сайта
     *
     */
    //TODO
    public static function getShopSiteOrdersCount($period = null){
        $q = History::find()
            ->select('COUNT(`ID`) as `a`')
            ->where(['deliveryType'  =>   5]);

        $b = History::find()
            ->select('COUNT(`ID`)')
            ->where('deliveryType != 5');

        if($period != null){
            if(isset($period['min'])){
                $q->andWhere('added > '.strtotime($period['min']));
                $b->andWhere('added > '.strtotime($period['min']));
            }
            if(isset($period['max'])){
                $q->andWhere('added < '.strtotime($period['max']));
                $b->andWhere('added < '.strtotime($period['max']));
            }
        }

        $q = $q
            ->union($b)
            ->asArray()
            ->all();

        return [
            'shop'  =>  isset($q['0']['a']) ? $q['0']['a'] : 0,
            'site'  =>  isset($q['1']['a']) ? $q['1']['a'] : 0
        ];
    }

    //TODO
    public static function getPaymentStats($period = null){
        $p = [];

        $q = History::find()
            ->select(['COUNT(`id`) as `count`', 'paymentType'])
            ->groupBy('paymentType');

        if($period != null){
            if(isset($period['min'])){
                $q->andWhere('added > '.strtotime($period['min']));
            }
            if(isset($period['max'])){
                $q->andWhere('added < '.strtotime($period['max']));
            }
        }

        foreach($q->asArray()->all() as $i){
            $p[$i['paymentType']] = $i['count'];
        }

        return $p;
    }

    //TODO
    public static function getStatsByCategories($period = null){
        $r = [];

        $q = History::find()
            ->select(['COUNT(`a`.`id`) as count', '`b`.`GroupID`'])
            ->from(['operations a', 'goods b'])
            ->where('b.ID = a.GoodID')
            ->groupBy('b.GroupID');

        if($period != null){
            if(isset($period['min'])){
                $q->andWhere('UNIX_TIMESTAMP(`a`.`Date`) > '.strtotime($period['min']));
            }
            if(isset($period['max'])){
                $q->andWhere('UNIX_TIMESTAMP(`a`.`Date`) < '.strtotime($period['max']));
            }
        }

        foreach($q->asArray()->all() as $i){
            $r[$i['GroupID']] = $i['count'];
        }

        return $r;
    }

    //TODO
    public static function getStatsByCategoriesWithCategoryName($period = null){
        $q = self::getStatsByCategories($period);

        if(empty($q)){
            return $q;
        }

        $keys = array_keys($q);

        $a = Category::find()->select(['ID', 'Name'])->where(['in', 'ID', $keys])->asArray()->all();
        $n = $r = [];

        foreach($a as $i){
            $n[$i['ID']] = $i['Name'];
        }

        foreach($q as $k => $v){
            $r[] = [
                'name'  =>  isset($n[$k]) ? $n[$k] : '',
                'count' =>  $v
            ];
        }

        return $r;
    }

}
