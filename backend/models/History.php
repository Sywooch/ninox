<?php

namespace backend\models;

use common\models\SborkaItem;
use Yii;
use common\models\Category;
use common\models\DeliveryTypes;
use common\models\Good;
use common\models\PaymentTypes;
use yii\data\ActiveDataProvider;

class History extends \common\models\History
{

    private $isOpt;
    private $real_summ;
    private $items;
    public $summ;

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

    public function afterFind(){
        parent::afterFind();

        $this->getStatus();
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

    public function beforeSave($insert){
        if($this->oldAttributes['confirmed'] != $this->confirmed && $this->confirmed == 1){
            //$this->confirmDate = date('Y-m-d H:i:s');
        }

        $this->hasChanges = 1;

        return parent::beforeSave($insert);
    }

    public function behaviors()
    {
        return [
            'LoggableBehavior' => [
                'class' => 'sammaye\audittrail\LoggableBehavior',
                'ignored' => [
                    'Name2',
                    'displayorder'
                ],
            ]
        ];
    }
}
