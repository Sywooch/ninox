<?php

namespace app\modules\orders\controllers;

use app\models\Customer;
use app\models\Good;
use app\models\History;
use app\models\Pricerule;
use app\models\SborkaItem;
use app\models\Siteuser;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex($p1 = '', $p2 = '')
    {
        if($p1 == "" && $p2 == ""){
            return $this->runAction('actionindex');
        }else{
            if($p2 != ""){
                return $this->runAction($p1, [
                    'param' =>  $p2
                ]);
            }else{
                return $this->runAction($p1);
            }
        }
    }

    public function actionActionindex(){
        $queryParts = [];

        $date = time() - (date('H') * 3600 + date('i') * 60 + date('s'));

        $timeFrom = $timeTo = null;

        switch(\Yii::$app->request->get("ordersSource")){
            case 'all':
                break;
            case 'market':
                $queryParts[] = 'deliveryType = 5 AND paymentType = 6';
                break;
            case 'deleted':
                $queryParts[] = 'deleted != 0';
                break;
            case 'shop':
            default:
                $queryParts[] = 'deliveryType != 5 AND paymentType != 6';
                break;
        }

        switch(\Yii::$app->request->get("showDates")){
            case 'yesterday':
                $timeFrom = $date;
                $timeTo = $date - 86400;
                $queryParts[] = 'added <= '.$date.' AND added >= '.$timeTo;
                break;
            case 'thisweek':
                $timeTo = $date - (date("N") - 1) * 86400;
                $queryParts[] = 'added >= '.$timeTo;
                break;
            case 'thismonth':
                $timeTo = $date - (date("j") - 1) * 86400;
                $queryParts[] = 'added >= '.$timeTo;
                break;
            case 'alltime':
                break;
            case 'today':
            default:
                $queryParts[] = 'added >= '.$date;
                break;
        }

        if(!\Yii::$app->request->get("showDeleted") && \Yii::$app->request->get("ordersSource") != 'deleted'){
            $queryParts[] = 'deleted = 0';
        }

        if(\Yii::$app->request->get("responsibleUser")){
            $queryParts[] = 'responsibleUserID = '.\Yii::$app->request->get("responsibleUser");
        }

        $this->view->params['showDateButtons'] = true;

        $orders = History::ordersQuery([
            'queryParts'    =>  $queryParts
        ]);

        $ordersStats = [
            'totalOrders'       =>  0,
            'completedOrders'   =>  0,
            'notCalled'         =>  0,
            'ordersFaktSumm'        =>  0,
            'ordersSumm'        =>  0
        ];

        foreach($orders->each() as $order){
            $ordersStats['totalOrders']++;
            $ordersStats['completedOrders'] += $order->done;
            $ordersStats['notCalled']   += $order->callback != 1 ? 1 : 0;
            $ordersStats['ordersFaktSumm']   += $order->actualAmount;
            $ordersStats['ordersSumm']   += $order->originalSum;
        }


        return $this->render('index', [
            'collectors'        =>  Siteuser::getCollectorsWithData($timeTo, $timeFrom),
            'showUnfinished'    =>  !\Yii::$app->request->get("showDates") || \Yii::$app->request->get("showDates") == 'today',
            'ordersStats'       =>  $ordersStats,
            'orders'            =>  History::ordersDataProvider([
                    'queryParts'    =>  $queryParts,
                    'limit'         =>  '50'
            ])
        ]);
    }

    public function actionConfirmordercall(){
        if(\Yii::$app->request->isAjax){
            $order = History::findOne(['id' => \Yii::$app->request->post("OrderID")]);
            //TODO: сделать проверку на колл-во звонков (поле callsCount)

            if(\Yii::$app->request->post("confirm") == "true")
            {
                $order->confirmed = 1;
            }
            else
            {
                $order->confirmed = 2;
            }
            $order->save(false);
            return $order->confirmed;
        }
    }

    public function actionDoneorder(){
        if(\Yii::$app->request->isAjax){
            $o = History::findOne(['id' => \Yii::$app->request->post("OrderID")]);

            if($o){
                $o->done = $o->done == 1 ? 0 : 1;
                $o->doneDate = date('Y-m-d H:i:s');

                $o->save(false);
                return $o->done;
            }

            return 0;
        }
    }

    public function actionShoworder($param = ''){
        $order = History::findOne(['id' => $param]);

        if(!$order){
            return $this->render('/../../admin/views/default/error.php', [
                'name'  =>  'Заказа не существует',
                'message'   => 'Такого заказа нет на сайте! Вы можете <a onclick="window.history.back();">вернуться обратно</a>, или попробовать ещё раз'
            ]);
        }

        if(\Yii::$app->request->post("SborkaItem")){
            $p = \Yii::$app->request->post("SborkaItem");
            $item = SborkaItem::findOne(['id' => $p['id']]);
            $item->attributes = $p;
            if($item->count != $item->oldAttributes['count']){
                if($item->count < 0){
                    $item->count = 0;
                }

                $razn = $item->oldAttributes['count'] - $item->count;

                $good = Good::findOne($item->itemID);
                $good->count += $razn;

                $good->save(false);
            }
            $item->save();
        }

        $goodsAdditionalInfo = $st = [];
        $sborkaItems = SborkaItem::findAll(['orderID' => $order->id]);

        foreach($sborkaItems as $sItem){
            $st[] = $sItem->itemID;
        }

        foreach(Good::find()->where(['in', 'ID', $st])->each() as $sItem){
            $goodsAdditionalInfo[$sItem->ID] = $sItem;
        }

        return $this->render('order', [
            'order'     =>  $order,
            'items'     =>  $sborkaItems,
            'itemsDataProvider'  =>  new ActiveDataProvider([
                'query' =>  $order->getItems(false)
            ]),
            'priceRules'=>  Pricerule::find()->orderBy('priority')->all(),
            'goodsAdditionalInfo'   =>  $goodsAdditionalInfo,
            'customer'  =>  Customer::findOne(['id' => $order->customerID])
        ]);
    }

    public function actionGetorderpreview(){
        if(\Yii::$app->request->isAjax){
            $order = History::findOne(['id' => \Yii::$app->request->post("expandRowKey")]);
            return $this->renderAjax('_orderPreview', [
                'model' =>  $order
            ]);
        }
    }

    public function actionRestoreitemdata(){
        if(!\Yii::$app->request->isAjax){
            return false;
        }

        \Yii::$app->response->format = 'json';

        $m = \Yii::$app->request->post("itemID");
        $m = SborkaItem::findOne(['id' => $m]);

        if(!$m){
            return false;
        }

        $g = Good::findOne(['id' => $m->itemID]);
        $o = History::findOne(['id' => $m->orderID]);

        if(!$g){
            return false;
        }

        $m->name = $g->Name;
        //$m->originalPrice = $g->;
    }

    public function actionChangeiteminorderstate(){
        if(\Yii::$app->request->isAjax){
            $item = SborkaItem::findOne(['id' => \Yii::$app->request->post("itemID"), 'orderID' => \Yii::$app->request->post("orderID")]);
            $return = '';

            switch(\Yii::$app->request->post("param")){
                case 'inorder':
                    $item->nalichie = $item->nalichie == 1 ? 0 : 1;
                    $return = $item->nalichie;
                    break;
                case 'deleted':
                    $item->nezakaz = $item->nezakaz == 1 ? 0 : 1;
                    $return = $item->nezakaz;
                    break;
                default:
                    return false;
            }

            $item->save();

            return $return;
        }
    }

    public function actionDeleteorder(){
        if(\Yii::$app->request->isAjax){
            $orderID = \Yii::$app->request->post('OrderID');
            $order = History::findOne(['id' => $orderID]);

            if($order){
                $orderItems = SborkaItem::findAll(['orderID' => $orderID]);
                $orderGoodsIDs = $orderItemsArray = [];

                foreach($orderItems as $item){
                    $orderGoodsIDs[] = $item->itemID;
                    $orderItemsArray[$item->itemID] = $item;
                }

                $orderGoods = Good::find()->where(['in', 'ID', $orderGoodsIDs])->all();

                foreach($orderGoods as $good){
                    $good->count += $orderItemsArray[$good->ID]->count;
                    $good->save(false);
                }

                $order->deleted = 1;
                $order->save(false);
            }
        }
    }

    public function actionUpdateorderprices(){
        if(\Yii::$app->request->isAjax){
            $d = \Yii::$app->request->post();
            if($d['type'] == 'opt' || $d['type'] == 'rozn'){
                $order = History::findOne(['id' => $d['OrderID']]);
                $order->recalculatePrices($d['type']);
            }
        }
    }
}
