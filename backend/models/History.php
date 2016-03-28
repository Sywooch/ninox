<?php

namespace backend\models;

use common\models\SborkaItem;
use Yii;
use common\models\Category;
use common\models\DeliveryType;
use common\models\PaymentType;
use yii\data\ActiveDataProvider;

class History extends \common\models\History
{

    private $real_summ;
    private $items;
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

    public function afterFind(){
        $this->getStatus();

        return parent::afterFind();
    }

    public function getID(){
        return $this->id;
    }

    public function getStatusDescription(){
        $statuses = [
            'Не прозвонен',
            'В обработке',
            'Не оплачен',
            'Ожидает доставку',
            'Отправлен',
            'Выполнен'
        ];

        if(!isset($statuses[$this->status])){
            return '';
        }

        return $statuses[$this->status];
    }

    public function beforeSave($insert){
        if($this->isAttributeChanged('confirmed') && $this->confirmed == 1){
            //$this->confirmDate = date('Y-m-d H:i:s');
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
    public function recalculatePrices($priceType = 'opt'){
        switch($priceType){
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

        $assemblyItems = SborkaItem::findAll(['orderID' => $this->id]);

        $itemsIDs = $goods = [];

        foreach($assemblyItems as $item){
            $itemsIDs[] = $item->itemID;
        }

        foreach(Good::find()->where(['in', 'ID', $itemsIDs])->each() as $good){
            $goods[$good->ID] = $good;
        }

        foreach($assemblyItems as $item){
            if(isset($goods[$item->itemID])){
                $item->originalPrice = $goods[$item->itemID]->$priceType;
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

    public function orderRealSumm(){
        if(!empty($this->real_summ)){
            return $this->real_summ;
        }

        foreach(SborkaItem::findAll(['orderID' => $this->id]) as $item){
            $this->real_summ += ($item->price * $item->count);
        }

        return $this->real_summ;
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

}
