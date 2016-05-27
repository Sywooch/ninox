<?php

namespace backend\models;

use backend\components\Sms;
use common\models\Comment;
use common\models\PaymentParam;
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
 * @property SborkaItem[] $availableItems
 * @property SborkaItem[] $missedItems
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

    private $_responsibleUser = null;

    /**
     * @param History $order
     */
    public function mergeWith($order){
        foreach($this->items as $item){
            $delegatedItem = $order->findItem($item->itemID);

            if($delegatedItem){
                $delegatedItem->count += $item->count;

                if($delegatedItem->save(false)){
                    $item->delete(false);
                }
            }else{
                $item->orderID = $order->id;

                $item->save(false);
            }
        }

        $this->deleted = 1;

        $this->save(false);
    }

    public static function find()
    {
        return parent::find()->with('items')->with('customer');
    }

    public function getItems($returnAll = true){
        return $this->hasMany(SborkaItem::className(), ['orderID' => 'ID']);
    }

    public function getMoneyCollector(){
        return $this->hasOne(Siteuser::className(), ['id' => 'moneyCollectorUserId']);
    }

    public function findItem($itemID){
        foreach($this->items as $item){
            if($item->itemID == $itemID){
                return $item;
            }
        }

        return false;
    }

    public function findItemByUniqID($uniqID){
        foreach($this->items as $item){
            if($item->ID == $uniqID){
                return $item;
            }
        }

        return false;
    }

    public function getID(){
        return $this->id;
    }

    public function getCustomer(){
        return $this->hasOne(Customer::className(), ['ID' => 'customerID']);
    }

    public function getNewCustomer(){
        if(empty($this->customer)){
            return false;
        }

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

    public function getComments(){
        $className = self::className();

        if(sizeof(explode('\\', $className)) > 1){
            $t = explode('\\', $className);
            $t = array_reverse($t);
            $className = $t[0];
        }

        return $this->hasMany(Comment::className(), ['modelID' => 'ID'])->with('commenter')->andWhere(['model' => $className]);
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
                break;
            case self::STATUS_WAIT_DELIVERY:
                $messageID = Sms::MESSAGE_ORDER_WAIT_DELIVERY_ID;
                break;
            case self::STATUS_DELIVERED:
            case self::STATUS_DONE:
                $messageID = Sms::MESSAGE_ORDER_DELIVERED;
                break;
        }

        $result = \Yii::$app->sms->sendPreparedMessage($this, $messageID);

        if($result == 200){
            $this->smsSendDate = date('Y-m-d H:i:s');
            $this->save(true);
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
                if(empty($this->paymentParamInfo)){
                    return false;
                }
                $messageID = Sms::MESSAGE_ORDER_DONE_ID;
                break;
            case self::STATUS_WAIT_DELIVERY:
                $messageID = Sms::MESSAGE_PAYMENT_CONFIRMED_ID;
                break;
            case self::STATUS_DELIVERED:
                break;
        }

        $result = \Yii::$app->sms->sendPreparedMessage($this, $messageID);
        //TODO: SmS state and date;
        if($result == 200){
            $this->smsSendDate = date('Y-m-d H:i');
            $this->save(true);
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

        if($this->oldAttributes['moneyConfirmed'] != $this->moneyConfirmed && $this->moneyConfirmed == 1){
            $this->moneyCollectorUserId = \Yii::$app->user->identity->id;
            $this->moneyConfirmedDate = date('Y-m-d H:i:s');

            if($this->sourceType != self::SOURCETYPE_SHOP && $this->deliveryType != 3 && $this->paymentType == 2){
                \Yii::$app->sms->sendPreparedMessage($this, Sms::MESSAGE_PAYMENT_CONFIRMED_ID);
            }
        }

        if($this->status != $this->getCurrentStatus()) {
            $this->status = $this->getCurrentStatus();
        }

        if($this->isAttributeChanged('status')){
            $this->statusChangedDate = date('Y-m-d H:i:s');
        }

        if($this->status == self::STATUS_WAIT_DELIVERY && (empty($this->sendDate) || $this->sendDate == '0000-00-00 00:00:00')){
            $this->sendDate = date('Y-m-d H:i:s');
        }

        if($this->paymentParam == null){
            $this->paymentParam = 0;
        }

        if($this->deliveryParam == null){
            $this->deliveryParam = 0;
        }

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



    /**
     * @return mixed
     */
    public function getOrderSum(){
        if(empty($this->sum)){
            $sum = 0;

            foreach($this->items as $item){
                $sum += $item->originalPrice * $item->originalCount;
            }

            $this->sum = $sum;
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
        return $this->realSum;
    }

    public function getSumWithoutDiscount(){
        $sumWithoutDiscount = 0;

        foreach($this->items as $item){
            $sumWithoutDiscount += ($item->originalPrice * $item->count);
        }

        return $sumWithoutDiscount;
    }

    public function getSumCustomerDiscount(){
        $sumCustomerDiscount = 0;

        foreach($this->availableItems as $item){
            if(!empty($this->customer) && $item->discountSize == $this->customer->getDiscount() && $item->discountType == 2 && $item->priceRuleID == 0){ //TODO: находить, что скидка именно присвоена пользователю за карту
                $sumCustomerDiscount += ($item->originalPrice - $item->price) * $item->count;
            }
        }

        return $sumCustomerDiscount;
    }

    public function getSumDiscount(){
        $discountSum = 0;

        foreach($this->availableItems as $item){
            if((!empty($this->customer) && $item->discountSize != $this->customer->getDiscount() && $item->discountType == 2) && $item->priceRuleID != 0){ //TODO: находить, что скидка именно присвоена пользователю за карту
                $discountSum += ($item->originalPrice - $item->price) * $item->count;
            }
        }

        return $discountSum;
    }

    public function getAvailableItems(){
        $availableItems = [];

        foreach($this->items as $item){
            if($item->nalichie == 1){
                $availableItems[] = $item;
            }
        }

        return $availableItems;
    }

    public function getMissingItems(){
        $missing = [];

        foreach($this->items as $item){
            if($item->nalichie == 0){
                $missing[] = $item;
            }
        }

        return $missing;
    }

    public function getMissingItemsSum(){
        $missingItemsSum = 0;

        foreach($this->missingItems as $item){
            $missingItemsSum += $item->price * $item->count;
        }

        return $missingItemsSum;
    }

    /**
     * Возвращает реальную стоимость заказа
     *
     * @return double
     */
    public function getRealSum(){
        if(empty($this->real_summ)){
            $this->real_summ = 0;

            foreach($this->availableItems as $item){
                $this->real_summ += ($item->price * $item->count);
            }
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
        $item = $this->findItem($itemID);

        if(!$item){
            throw new NotFoundHttpException("Товар с ID {$itemID} не найден в заказе #{$this->number} (ID {$this->ID})");
        }

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

    public function getPaymentParamInfo(){
        return $this->hasOne(PaymentParam::className(), ['id' => 'paymentParam']);
    }

}
