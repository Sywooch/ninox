<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 09.06.16
 * Time: 22:11
 */

namespace backend\models;


use common\models\SborkaItem;

class Siteuser extends \common\models\Siteuser
{

    public static function getActive()
    {
        return Siteuser::find()
            ->joinWith('accessDomains')
            ->andWhere(['`siteusers`.`active`' => '1', '`siteusers`.`showInStat`' => '1', '`subDomainsAccess`.`subDomainId`' => \Yii::$app->params['configuration']->id])
            ->with('unfinishedOrders')
            ->all();
    }

    public function getOrders()
    {
        return $this->hasMany(HistoryWithoutRelations::className(), ['responsibleUserID' => 'id'])->andWhere(['deleted' => 0]);
    }

    public function getDoneOrders()
    {
        return $this->getOrders()->andWhere(['done' => 1]);
    }

    public function getOrdersCount()
    {
        return $this->getDoneOrders()->count();
    }

    public function getCompletedOrdersCount($dateFrom = null, $dateTo = null)
    {
        $query = $this->getDoneOrders();

        if (!empty($dateFrom)) {
            $query->andWhere("`added` > '{$dateFrom}'");
        }

        if (!empty($dateTo)) {
            $query->andWhere("`added` < '{$dateTo}'");
        }

        return $query->count();
    }

    public function getTodayOrders(){
        $today = strtotime(date('Y-m-d'));

        return $this->getOrders()->andWhere("`added` > '{$today}'");
    }

    public function getTodayDoneOrdersItemsCount(){
        $itemsCount = 0;

        foreach($this->todayDoneOrders as $order){
            $itemsCount += count($order->items);
        }

        return $itemsCount;
    }

    public function getUnfinishedOrders(){
        return $this->getOrders()->andWhere("`done` != '1'");
    }

    public function getUnfinishedItemsCount(){
        $ordersIDs = [];

        foreach ($this->unfinishedOrders as $order) {
            $ordersIDs[] = $order->id;
        }

        return SborkaItem::find()->where(['in', 'orderID', $ordersIDs])->count();
    }

    public function getTodayDoneOrders(){
        $orders = [];

        foreach($this->todayOrders as $order){
            if($order->done == 1){
                $orders[] = $order;
            }
        }

        return $orders;
    }

    public function getYesterdayOrders(){
        $today = strtotime(date('Y-m-d'));
        $yesterday = strtotime(date('Y-m-d')) - 86400;

        return $this->getOrders()->andWhere("`added` > '{$yesterday}' AND `added` < '{$today}'");
    }

    public function getYesterdayDoneOrders(){
        return $this->getYesterdayOrders()->andWhere(['done' => 1]);
    }

    public function getTodayItems(){
        $itemsCount = 0;

        foreach ($this->todayOrders as $todayOrder) {
            $itemsCount += count($todayOrder->items);
        }

        return $itemsCount;
    }

}