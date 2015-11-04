<?php

namespace backend\modules\orders\controllers;

use backend\models\HistorySearch;
use common\models\Customer;
use common\models\CustomerAddresses;
use common\models\CustomerContacts;
use common\models\Good;
use backend\models\History;
use common\models\NovaPoshtaOrder;
use common\models\Pricerule;
use backend\models\SborkaItem;
use common\models\Siteuser;
use sammaye\audittrail\AuditTrail;
use yii\data\ActiveDataProvider;
use backend\controllers\SiteController as Controller;
use yii\helpers\Json;

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

        $order->hasChanges = 1;

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

            $order->confirmed = \Yii::$app->request->post("confirm") == "true" ? 1 : 2;

            $order->hasChanges = 1;
            $order->save(false);
            return $order->confirmed;
        }
    }

    public function actionDoneorder(){
        if(\Yii::$app->request->isAjax){
            $o = History::findOne(['id' => \Yii::$app->request->post("OrderID")]);

            if($o){
                $o->done = $o->done == 1 ? 0 : 1;
                $o->doneDate = $o->done == 1 ? date('Y-m-d H:i:s') : '0000-00-00 00:00:00';

                $o->hasChanges = 1;

                $o->save(false);
                return $o->done;
            }

            return 0;
        }
    }

    public function actionShoworder($param = ''){
        $order = History::findOne(['id' => $param]);

        if(!$order){
            return $this->run('site/error');
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
            $order->hasChanges = 1;
            $order->save();

            $item->save();
        }

        if(\Yii::$app->request->post("History")){
            $order->attributes = \Yii::$app->request->post("History");
            $order->hasChanges = 1;
            $order->save();
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

        return $this->run('error');
    }

    public function actionGetboxes(){
        return $this->render('_boxes', [
            'boxes' =>  Box::findAll()
        ]);
    }


    public function actionDeleteorder(){
        if(\Yii::$app->request->isAjax){
            $orderID = \Yii::$app->request->post('OrderID');
            $order = History::findOne(['id' => $orderID]);

            if($order){
                $order->hasChanges = 1;
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

        $order = \Yii::$app->request->post("OrderID");

        return $this->renderAjax('_changes_modal', [
            'order'         =>  $order,
            'dataProvider'  =>  new ActiveDataProvider([
                'query'     =>  AuditTrail::find()
                                    ->where(['model'  =>  History::className(), 'model_id'    =>  $order])
                                    ->orderBy('id desc')
                                    ->orWhere(['and', ['model' => SborkaItem::className()], ['in', 'model_id', SborkaItem::find()->select('id')->where(['orderID' => $order])]]),
                'pagination'    =>  [
                    'pageSize'  =>  '20'
                ]
            ])
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

        return $this->renderAjax('invoice', [
            'invoice'   =>  $invoice
        ]);
    }

    public function actionSetitemsdiscount(){
        if(!\Yii::$app->request->isAjax){
            return $this->run('site/error');
        }

        \Yii::$app->response->format = 'json';

        $request = \Yii::$app->request->post();

        $request['selectedItems'] = Json::decode($request['selectedItems']);

        $items = SborkaItem::find()->where(['orderID' => $request['orderID']]);

        if($request['discountRewriteType'] == 1){
            if(empty($request['selectedItems'])){
                return $this->run('site/error');
            }

            $items->andWhere(['in', 'id', $request['selectedItems']]);
        }

        foreach($items->each() as $item){
            $item->discountSize = $request['discountSize'];
            $item->discountType = $request['discountType'];

            $item->save();
        }

        return $request;
    }

    public function actionUsepricerule(){
        if(!\Yii::$app->request->isAjax){
            return $this->run('site/error');
        }

        \Yii::$app->response->format = 'json';

        $request = \Yii::$app->request->post();

        //тут должна быть функция пересчёта

        return true;
    }
}
