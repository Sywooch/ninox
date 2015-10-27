<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "history".
 *
 * @property integer $id
 * @property string $customerEmail
 * @property string $customerName
 * @property string $customerSurname
 * @property string $customerPhone
 * @property string $deliveryAddress
 * @property string $deliveryRegion
 * @property integer $added
 * @property string $customerFathername
 * @property integer $deliveryType
 * @property string $deliveryCity
 * @property string $customerComment
 * @property integer $customerID
 * @property string $deliveryInfo
 * @property string $coupon
 * @property integer $paymentType
 * @property string $paymentInfo
 * @property integer $callback
 * @property integer $canChangeItems
 * @property integer $actualAmount
 * @property double $amountDeductedOrder
 * @property integer $moneyCollectorUserId
 * @property string $nakladna
 * @property integer $globalmoney
 * @property integer $nakladnaSendState
 * @property integer $done
 * @property integer $responsibleUserID
 * @property integer $confirmed
 * @property integer $moneyConfirmed
 * @property integer $confirm_otd
 * @property string $moneyConfirmedDate
 * @property integer $processed
 * @property string $doneDate
 * @property string $sendDate
 * @property string $receivedDate
 * @property integer $smsState
 * @property integer $deleted
 * @property integer $takeOrder
 * @property string $takeOrderDate
 * @property integer $takeTTNMoney
 * @property string $takeTTNMoneyDate
 * @property integer $boxesCount
 * @property integer $isNew
 * @property string $deleteDate
 * @property integer $transactionSended
 * @property string $confirmedDate
 * @property string $sendSmsDate
 * @property integer $callsCount
 */
class History extends \yii\db\ActiveRecord
{

    private $isOpt;
    private $real_summ;
    private $items;
    public $summ;
    public $status;

    public static $status_1     =   'Не звонили';
    public static $status_2     =   'Подготовка заказа';
    public static $status_3     =   'Абонент не отвечает';
    public static $status_4     =   '<b>Ожидается оплата</b>';
    public static $status_5     =   'Заказ отправлен';
    public static $status_6     =   'Оплачено';
    public static $status_7     =   'Возврат';
    public static $status_8     =   '<small>Отправлен - оплачено</small>';
    public static $status_9     =   '<small><b>Отправлен - оплаты нет</b></small>';
    public static $status_10    =   'Ожидает отправки';

    public function beforeSave($insert){
        if($this->oldAttributes['confirmed'] != $this->confirmed && $this->confirmed == 1){
            //$this->confirmDate = date('Y-m-d H:i:s');
        }
        return parent::beforeSave($insert);
    }

    public function afterFind(){
        if($this->deleted == '4'){
            $this->status = '7';
        }else{
            if($this->confirmed != '0'){
                if($this->confirmed == '2'){
                    $this->status = '3';
                }else{
                    if($this->done == '1'){
                        if(strlen($this->nakladna) > '1' && $this->nakladnaSendState == '1' && $this->actualAmount != '0'){
                            if($this->moneyConfirmed == '1'){
                                if($this->paymentType == '2' || $this->paymentType == '3' || $this->paymentType == '4'){
                                    $this->status = '8';
                                }elseif($this->paymentType == '1'){
                                    $this->status = '6';
                                }
                            }elseif($this->paymentType == '1'){
                                $this->status = '9';
                            }
                        }elseif($this->paymentType == '2' || $this->paymentType == '3' || $this->paymentType == '4' || $this->paymentType == '5'){
                            $this->status = $this->moneyConfirmed == '1' ? '6' : '4';
                        }elseif($this->paymentType == '1'){
                            $this->status = '10';
                        }
                    }elseif($this->paymentType == '1' && strlen($this->nakladna) > '1' && $this->nakladnaSendState != '0'){
                        $this->status = '4';
                    }else{
                        $this->status = '2';
                    }
                }
            }else{
                $this->status = '1';
            }
        }
    }

    public static function ordersQuery($options = []){
        $query = History::find()->orderBy('id DESC');

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

    public function recalculatePrices($priceType = 'opt'){
        switch($priceType){
            case 'opt':
                $priceType = 'PriceOut1';
                break;
            case 'rozn':
                $priceType = 'PriceOut2';
                break;
        }

        $sborkaItems = SborkaItem::findAll(['orderID' => $this->id]);

        $items = $ggoods = [];

        foreach($sborkaItems as $item){
            $items[] = $item->itemID;
        }

        $goods = Good::find()->where(['in', 'ID', $items])->all();

        foreach($goods as $good){
            $ggoods[$good->ID] = $good;
        }

        foreach($sborkaItems as $item){
            if(isset($ggoods[$item->itemID])){
                $item->originalPrice = $ggoods[$item->itemID]->$priceType;
                $item->save(false);
            }
        }

        return true;
    }

    public function isOpt(){
        if(!empty($this->isOpt)){
            return $this->isOpt;
        }

        $this->isOpt = ($this->orderSumm() >= 800);

        return $this->isOpt;
    }

    public function orderSumm(){
        if(!empty($this->summ)){
            return $this->summ;
        }

        $this->summ = SborkaItem::find()->select("SUM((`originalPrice` * `originalCount`))")->where(['orderID' => $this->id])->scalar();

        return $this->summ;
    }

    public function orderRealSumm(){
        if(!empty($this->real_summ)){
            return $this->real_summ;
        }

        foreach(SborkaItem::findAll(['orderID' => $this->id]) as $item){
            $this->real_summ += ($item->price * $item->count);
        }

        return $this->real_summ;
    }

    public function paymentType(){
        return PaymentTypes::getName($this->paymentType);
    }

    public function deliveryType(){
        return DeliveryTypes::getName($this->deliveryType);
    }

    public function getItems($returnAll = true){
        if(!empty($this->items) && $returnAll){
            return $this->items;
        }

        $q = SborkaItem::find()->where(['orderid' => $this->id]);

        if(!$returnAll){
            return $q;
        }

        $this->items = $q->all();


        return $this->items;
    }


    /**
     *
     * Возвращает колл-во заказов, сделаных из магазина и из сайта
     *
     */
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

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['added', 'deliveryType', 'customerID', 'paymentType', 'callback', 'canChangeItems', 'actualAmount', 'moneyCollectorUserId', 'globalmoney', 'nakladnaSendState', 'done', 'responsibleUserID', 'confirmed', 'moneyConfirmed', 'confirm_otd', 'processed', 'smsState', 'deleted', 'takeOrder', 'takeTTNMoney', 'boxesCount', 'isNew', 'transactionSended', 'callsCount'], 'integer'],
            [['customerComment'], 'string'],
            [['amountDeductedOrder'], 'number'],
            [['nakladna', 'takeOrderDate', 'takeTTNMoneyDate'], 'required'],
            [['moneyConfirmedDate', 'doneDate', 'sendDate', 'receivedDate', 'takeOrderDate', 'takeTTNMoneyDate', 'deleteDate', 'confirmedDate', 'smsSendDate'], 'safe'],
            [['customerEmail', 'deliveryAddress', 'deliveryRegion', 'deliveryCity', 'deliveryInfo', 'coupon', 'paymentInfo'], 'string', 'max' => 255],
            [['customerName', 'customerSurname', 'customerPhone', 'customerFathername'], 'string', 'max' => 64],
            [['nakladna'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                    => 'ID',
            'customerEmail'         => 'email клиента',
            'customerName'          => 'Имя клиента',
            'customerSurname'       => 'Фамилия клиента',
            'customerPhone'         => 'Телефон клиента',
            'deliveryAddress'       => 'Адрес доставки',
            'deliveryRegion'        => 'Область доставки',
            'added'                 => 'Дата',
            'customerFathername'    => 'Отчество клиента',
            'deliveryType'          => 'Способ доставки',
            'deliveryCity'          => 'Город доставки',
            'customerComment'       => 'Комментарий клиента',
            'customerID'            => 'ID клиента',
            'deliveryInfo'          => 'Номер склада',
            'coupon'                => 'Промокод',
            'paymentType'           => 'Тип оплаты',
            'paymentInfo'           => 'Опция оплаты',
            'callback'              => 'Перезванивали?',
            'canChangeItems'        => 'Можно делать замену?',
            'actualAmount'          => 'Фактическая сумма',
            'amountDeductedOrder'   => 'Списано со счёта пользователя',
            'moneyCollectorUserId'  => 'Забрал деньги',
            'nakladna'              => 'Номер ТТН',
            'globalmoney'           => 'Globalmoney',
            'nakladnaSendState'     => 'Отправлена накладная',
            'done'                  => 'Заказ выполнен',
            'responsibleUserID'     => 'Менеджер заказа',
            'confirmed'             => 'Подтверждён',
            'moneyConfirmed'        => 'Подтверждена оплата',
            'confirm_otd'           => 'Confirm Otd',
            'moneyConfirmedDate'    => 'Money Confirmed Date',
            'processed'             => 'Processed',
            'doneDate'              => 'Дата выполнения заказа',
            'sendDate'              => 'Дата отправки заказа',
            'receivedDate'          => 'Received Date',
            'smsState'              => 'Отправлена смс',
            'deleted'               => 'Удалён',
            'takeOrder'             => 'Take Order',
            'takeOrderDate'         => 'Take Order Date',
            'takeTTNMoney'          => 'Take Ttnmoney',
            'takeTTNMoneyDate'      => 'Take Ttnmoney Date',
            'boxesCount'            => 'Количество коробок',
            'isNew'                 => 'Is New',
            'deleteDate'            => 'Дата удаления заказа',
            'transactionSended'     => 'Состояние транзакции Google Analytics',
            'confirmedDate'         => 'Confirmed Date',
            'smsSendDate'           => 'Send Sms Date',
            'callsCount'            => 'Calls Count',
        ];
    }
}
