<?php

namespace backend\modules\orders\controllers;

use backend\models\HistorySearch;
use common\models\Customer;
use common\models\CustomerAddresses;
use common\models\CustomerContacts;
use common\models\Good;
use common\models\History;
use common\models\NovaPoshtaOrder;
use common\models\Pricerule;
use common\models\SborkaItem;
use common\models\Siteuser;
use yii\data\ActiveDataProvider;
use backend\controllers\SiteController as Controller;

class DefaultController extends Controller
{

    public function actionIndex(){
        //Нихуясебе что я тут за хуйню написал
        //Нужно переместить в HistorySearch почти всё что здесь


        /*
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

        if(!\Yii::$app->request->get("showDeleted") && \Yii::$app->request->get("ordersSource") != 'deleted'){
            $queryParts[] = 'deleted = 0';
        }

        if(\Yii::$app->request->get("responsibleUser")){
            $queryParts[] = 'responsibleUserID = '.\Yii::$app->request->get("responsibleUser");
        }

         */
        $date = time() - (date('H') * 3600 + date('i') * 60 + date('s'));

        $timeFrom = $timeTo = null;

        switch(\Yii::$app->request->get("showDates")){
            case 'yesterday':
                $timeFrom = $date;
                $timeTo = $date - 86400;
                break;
            case 'thisweek':
                $timeTo = $date - (date("N") - 1) * 86400;
                break;
            case 'thismonth':
                $timeTo = $date - (date("j") - 1) * 86400;
                break;
            case 'alltime':
                break;
        }

        $this->view->params['showDateButtons'] = true;

        $historySearch = new HistorySearch();

        $orders = $historySearch->search(\Yii::$app->request->get(), true);

        $ordersStats = [
            'totalOrders'       =>  0,
            'completedOrders'   =>  0,
            'notCalled'         =>  0,
            'ordersFaktSumm'    =>  0,
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
            'searchModel'       =>  $historySearch,
            'orders'            =>  $historySearch->search(\Yii::$app->request->get())
        ]);
    }

    public function actionSaveorderpreview(){
        if(!\Yii::$app->request->isAjax){
            return $this->run('site/error');
        }
        \Yii::$app->response->format = 'json';

        $order = History::findOne(['id' => \Yii::$app->request->post('History')['id']]);

        $order->attributes = \Yii::$app->request->post('History');
        $order->save();

        if(isset($order->responsibleUserID) && $order->responsibleUserID != 0){
            $order->responsibleUserID = Siteuser::getUser($order->responsibleUserID)->name;
        }

        return $order;
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

    public function actionOrderchanges(){
        if(!\Yii::$app->request->isAjax){
            return $this->run('site/error');
        }

        $order = \Yii::$app->request->post("orderID");

        return $this->renderAjax('_changes_modal', [
            'order' =>  $order
        ]);
    }

    public function actionCreateinvoice($param){
        $order = $param;
        if(!is_object($order)){
            $order = History::findOne(['id' => $order]);
        }

        $customer = Customer::findOne(['id' => $order->customerID]);

        $invoice = new NovaPoshtaOrder([
            'orderData'         =>  $order,
            'ServiceType'       =>  '',
            'recipientData'     =>  $customer,
            'recipientContacts' =>  CustomerContacts::find()->where(['partnerID' => $customer->ID, 'type' => '2'])->orderBy('ID DESC')->one(),
            'recipientDelivery' =>  CustomerAddresses::find()->where(['partnerID' => $customer->ID])->orderBy('ID DESC')->one(),
        ]);

        if(\Yii::$app->request->post()){
            $invoice->attributes = \Yii::$app->request->post("NovaPoshtaOrder");

            $invoice->save();
        }

        return $this->render('invoice', [
            'invoice'   =>  $invoice
        ]);
    }
}
