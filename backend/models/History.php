<?php

namespace backend\models;

use backend\components\Sms;
use common\models\Comment;
use common\models\PaymentParam;
use common\models\Siteuser;
use yii;
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
 * @property boolean $isWholesale
 * @property SborkaItem[] notControlledGoods
 * @property string $confirmDate
 * @property string $statusChangedDate
 */
class History extends \common\models\History
{

    private $real_summ;
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


    public static function find()
    {
        return parent::find()->with('items')->with('customer');
    }

    /**
     * Товары заказа
     *
     * @param bool $returnAll
     * @return yii\db\ActiveQuery
     */
    public function getItems($returnAll = true){
        return $this->hasMany(SborkaItem::className(), ['orderID' => 'ID']);
    }

    /**
     * Пользователь, забравший деньги
     *
     * @return yii\db\ActiveQuery
     */
    public function getMoneyCollector(){
        return $this->hasOne(Siteuser::className(), ['id' => 'moneyCollectorUserId']);
    }

    /**
     * Клиент, оформивший заказ
     *
     * @return yii\db\ActiveQuery
     */
    public function getCustomer(){
        return $this->hasOne(Customer::className(), ['ID' => 'customerID']);
    }

    /**
     * Комментарии к заказу
     *
     * @return yii\db\ActiveQuery
     */
    public function getComments(){
        $className = self::className();

        if(count(explode('\\', $className)) > 1){
            $t = explode('\\', $className);
            $t = array_reverse($t);
            $className = $t[0];
        }

        return $this->hasMany(Comment::className(), ['modelID' => 'ID'])->with('commenter')->andWhere(['model' => $className]);
    }

    /**
     * Менеджер заказа
     *
     * @return yii\db\ActiveQuery
     */
    public function getResponsibleUser(){
        return $this->hasOne(Siteuser::className(), ['ID' => 'responsibleUserID']);
    }
    
    /**
     * Параметр оплаты
     *
     * @return yii\db\ActiveQuery
     */
    public function getPaymentParamInfo(){
        return $this->hasOne(PaymentParam::className(), ['id' => 'paymentParam']);
    }

    /**
     * Возвращает ID заказа
     *
     * @return integer
     */
    public function getID(){
        return $this->id;
    }

    /**
     * Возвращает статус, новый-ли покупатель
     *
     * @return bool
     */
    public function getNewCustomer(){
        if(empty($this->customer)){
            return false;
        }

        return count($this->customer->orders) <= 1;
    }

    /**
     * Товар оформлен с оплатой  на карту?
     *
     * @return bool
     */
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

    /**
     * Оптовый-ли заказ
     *
     * @return bool
     */
    public function getIsWholesale(){
        return $this->orderSum >= 800;
    }

    /**
     * Возвращает изначальную сумму заказа (без скидки и добавленых товаров)
     * 
     * @return double
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
     * Возвращает сумму заказа (без скидки)
     * 
     * @return double
     */
    public function getSumWithoutDiscount(){
        $sumWithoutDiscount = 0;

        foreach($this->items as $item){
            $sumWithoutDiscount += ($item->originalPrice * $item->count);
        }

        return $sumWithoutDiscount;
    }

    /**
     * Возвращает сумму клиентской скидки в заказе
     * 
     * @return double
     */
    public function getSumCustomerDiscount(){
        $sumCustomerDiscount = 0;

        foreach($this->availableItems as $item){
            if($item->customerDiscounted){
                $sumCustomerDiscount += ($item->originalPrice - $item->price) * $item->count;
            }
        }

        return $sumCustomerDiscount;
    }

    /**
     * Возвращает сумму скидки (без клиентского дисконта) в заказе
     *
     * @return double
     */
    public function getSumDiscount(){
        $discountSum = 0;

        foreach($this->availableItems as $item){
            if(!$item->customerDiscounted){
                $discountSum += ($item->originalPrice - $item->price) * $item->count;
            }
        }

        return $discountSum;
    }

    /**
     * Возвращает итемы, которые сборщики смогли найти
     *
     * @return SborkaItem[]
     */
    public function getAvailableItems(){
        $availableItems = [];

        foreach($this->items as $item){
            if($item->nalichie == 1){
                $availableItems[] = $item;
            }
        }

        return $availableItems;
    }

    /**
     * Возвращает итемы, которые сборщики не смогли найти
     *
     * @return SborkaItem[]
     */
    public function getMissingItems(){
        $missing = [];

        foreach($this->items as $item){
            if($item->nalichie == 0){
                $missing[] = $item;
            }
        }

        return $missing;
    }

    /**
     * Возвращает сумму товаров, которые не смогли найти сборщики
     *
     * @return double
     */
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

    /**
     * Возвращает непроконтролированые товары в заказе
     *
     * @return array
     */
    public function getNotControlledGoods(){
        $items = [];

        foreach($this->availableItems as $item){
            if(!$item->controlled){
                $items[] = $item;
            }
        }

        return $items;
    }

    /**
     * Возвращает колличество непроконтролированых товаров (позиций)
     *
     * @return integer
     */
    public function getNotControlledGoodsCount(){
        return count($this->notControlledGoods);
    }

    /**
     * Колличество непроконтролированых товаров
     *
     * @return int
     */
    public function getNotControlledItemsCount(){
        $count = 0;

        foreach($this->notControlledGoods as $item){
            $count += ($item->originalCount - $item->realyCount);
        }

        return $count;
    }

    /**
     * Возвращает проконтролированые товары
     *
     * @return SborkaItem[]
     */
    public function getControlledGoods(){
        $items = [];

        foreach($this->availableItems as $item){
            if($item->controlled){
                $items[] = $item;
            }
        }

        return $items;
    }

    /**
     * Проконтролирован-ли заказ
     *
     * @return bool
     */
    public function getControlled(){
        return count($this->notControlledGoods) > 0;
    }

    /**
     * Находит товар в заказе
     *
     * @param integer $itemID ID товара
     * @param bool $onlyAvailable возвращает только включеный товар
     * @return SborkaItem|bool
     */
    public function findItem($itemID, $onlyAvailable = false){
        $items = $onlyAvailable ? $this->availableItems : $this->items;

        foreach($items as $item){
            if($item->itemID == $itemID){
                return $item;
            }
        }

        return false;
    }

    public function findAvailableItem($itemID){
        return $this->findItem($itemID, true);
    }

    public function findItemByUniqID($uniqID, $onlyAvailable = false){
        $items = $onlyAvailable ? $this->availableItems : $this->items;

        foreach($items as $item){
            if($item->ID == $uniqID){
                return $item;
            }
        }

        return false;
    }

    public function clearControl(){
        foreach($this->items as $item) {
            $item->realyCount = 0;

            $item->save(false);
        }
    }

    public function controlItem($itemID, $count = 1){
        $item = $this->findAvailableItem($itemID);

        if(!$item){
            throw new NotFoundHttpException("Товар с ID {$itemID} не найден в заказе #{$this->number} (ID {$this->ID})");
        }

        if($item->controlled){
            throw new ConflictHttpException('Нельзя подтвердить подтверждённый товар дважды!');
        }

        $item->realyCount += $count;
        $item->save(false);
    }

    /**
     * @param History $order
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
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


    public function sendMessage($type){
        switch($type){
            case 'card':
                return $this->sendCardSms();
                break;
            case 'sms':
                return $this->sendSms();
                break;
        }

        return false;
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
        if($this->confirmed == 1 && $this->isAttributeChanged('confirmed')){
            $this->confirmDate = date('Y-m-d H:i:s');
        }

        if($this->done == 1 && $this->isAttributeChanged('done')){
            $this->doneDate = date('Y-m-d H:i:s');
        }

        if($this->takeOrder == 1 && $this->isAttributeChanged('takeOrder')){
            $this->takeOrderDate = date('Y-m-d H:i:s');
        }

        if($this->takeTTNMoney == 1 && $this->isAttributeChanged('takeTTNMoney')){
            $this->takeTTNMoneyDate = date('Y-m-d H:i:s');
        }

        if($this->moneyConfirmed == 1 && $this->oldAttributes['moneyConfirmed'] != $this->moneyConfirmed){
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
        return [
            'LoggableBehavior' => [
                'class' => 'sammaye\audittrail\LoggableBehavior',
                'ignored' => [
                    'Name2',
                    'added'
                ],
            ]
        ];
    }

    public static function ordersQuery(array $options = []){
        $query = self::find()->orderBy('id DESC');

        if(array_key_exists('thisOrder', $options)){
            $query->andWhere('id != '.$options['thisOrder']);
        }

        if(array_key_exists('queryParts', $options) && !empty($options['queryParts'])){
            if(!is_array($options['queryParts'])){
                $options['queryParts'] = [$options['queryParts']];
            }

            foreach($options['queryParts'] as $part){
                $query->andWhere($part);
            }
        }

        if(array_key_exists('where', $options)){
            $query->andWhere($options['where']);
        }

        return $query;
    }

    public static function ordersDataProvider(array $options = []){
        $query = self::ordersQuery($options);

        $ADPConfig = [
            'query' =>  $query,
        ];

        if(array_key_exists('limit', $options)){
            $ADPConfig['pagination']['pageSize'] = $options['limit'];
        }

        return new ActiveDataProvider($ADPConfig);
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
     *
     * Возвращает колл-во заказов, сделаных из магазина и из сайта
     * @param null $period
     * @return array
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
            if(array_key_exists('min', $period)){
                $q->andWhere('added > '.strtotime($period['min']));
                $b->andWhere('added > '.strtotime($period['min']));
            }
            if(array_key_exists('max', $period)){
                $q->andWhere('added < '.strtotime($period['max']));
                $b->andWhere('added < '.strtotime($period['max']));
            }
        }

        $q = $q
            ->union($b)
            ->asArray()
            ->all();

        return [
            'shop'  =>  array_key_exists('a', $q['0']) ? $q['0']['a'] : 0,
            'site'  =>  array_key_exists('a', $q['1']) ? $q['1']['a'] : 0
        ];
    }

    //TODO
    public static function getPaymentStats($period = null){
        $p = [];

        $q = History::find()
            ->select(['COUNT(`id`) as `count`', 'paymentType'])
            ->groupBy('paymentType');

        if($period != null){
            if(array_key_exists('min', $period)){
                $q->andWhere('added > '.strtotime($period['min']));
            }
            if(array_key_exists('max', $period)){
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
            if(array_key_exists('min', $period)){
                $q->andWhere('UNIX_TIMESTAMP(`a`.`Date`) > '.strtotime($period['min']));
            }
            if(array_key_exists('max', $period)){
                $q->andWhere('UNIX_TIMESTAMP(`a`.`Date`) < '.strtotime($period['max']));
            }
        }

        foreach($q->asArray()->all() as $i){
            $r[$i['GroupID']] = $i['count'];
        }

        return $r;
    }

    /**
     * Используется в /modules/charts
     *
     * @param null $period
     * @return array
     */
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
                'name'  =>  array_key_exists($k, $n) ? $n[$k] : '',
                'count' =>  $v
            ];
        }

        return $r;
    }


}
