<?php

namespace backend\modules\orders\controllers;

use backend\models\HistorySearch;
use common\helpers\PriceRuleHelper;
use backend\models\Customer;
use backend\models\CustomerAddresses;
use backend\models\CustomerContacts;
use backend\models\Good;
use backend\models\History;
use common\models\NovaPoshtaOrder;
use common\models\Pricerule;
use backend\models\SborkaItem;
use common\models\Siteuser;
use sammaye\audittrail\AuditTrail;
use yii\data\ActiveDataProvider;
use backend\controllers\SiteController as Controller;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnsupportedMediaTypeHttpException;

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

	    //TODO: очень много памяти и процессорного времени жрет этот форич, особенно когда записей много. Надо как-то по другому придумать как собрать данную статистику.
        //TODO: наче получилось уменьшить объем памяти и сократить время выполнения скрипта примерно в 5 раз. Теперь рашбери не должен лагать.
	    //Подсмотренно вот тут https://github.com/yiisoft/yii2/blob/master/docs/guide/tutorial-performance-tuning.md
	    foreach($orders->asArray()->each() as $order){
            $ordersStats['totalOrders']++;
            $ordersStats['completedOrders'] += $order['done'];
            $ordersStats['notCalled']   += $order['callback'] != 1;
            $ordersStats['ordersFaktSumm']   += $order['actualAmount'];
            $ordersStats['ordersSumm']   += $order['originalSum'];
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
            throw new UnsupportedMediaTypeHttpException("Этот запрос возможен только через ajax!");
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
        if(!\Yii::$app->request->isAjax){
            throw new UnsupportedMediaTypeHttpException("Этот запрос возможен только через ajax!");
        }

        $order = History::findOne(['id' => \Yii::$app->request->post("OrderID")]);
        //TODO: сделать проверку на колл-во звонков (поле callsCount)

        $order->callback = \Yii::$app->request->post("confirm") == "true" ? 1 : 2;

        $order->save(false);
        return $order->confirmed;
    }

    public function actionDoneorder(){
        if(\Yii::$app->request->isAjax){
            throw new UnsupportedMediaTypeHttpException("Этот запрос возможен только через ajax!");
        }

        $o = History::findOne(['id' => \Yii::$app->request->post("OrderID")]);

        if($o){
            $o->done = $o->done == 1 ? 0 : 1;
            $o->doneDate = $o->done == 1 ? date('Y-m-d H:i:s') : '0000-00-00 00:00:00';

            $o->save(false);
            return $o->done;
        }

        return 0;
    }

    public function actionShoworder($param = ''){
        $order = History::findOne(['id' => $param]);

        if(!$order){
            throw new NotFoundHttpException("Такого заказа не существует!");
        }

        if(\Yii::$app->request->post("SborkaItem")){
            $p = \Yii::$app->request->post("SborkaItem");
            $item = SborkaItem::findOne(['orderID' => $p['orderID'], 'itemID' => $p['itemID']]);

            if(!$item){
                throw new NotFoundHttpException("Такой товар не найден!");
            }

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
            ;
            $order->save();

            $item->save();
        }

        if(\Yii::$app->request->post("History")){
            $order->attributes = \Yii::$app->request->post("History");
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

        $itemsDataProvider = new ActiveDataProvider([
            'query' =>  $order->getItems(false),
        ]);

        $itemsDataProvider->setSort([
            'defaultOrder' => [
                'added'	=>	SORT_ASC
            ],
            'attributes' => [
                'added' => [
                    'default' => SORT_ASC
                ],
            ]
        ]);

        return $this->render('order', [
            'order'     =>  $order,
            'items'     =>  $sborkaItems,
            'itemsDataProvider'  =>  $itemsDataProvider,
            'priceRules'=>  Pricerule::find()->orderBy('priority')->all(),
            'goodsAdditionalInfo'   =>  $goodsAdditionalInfo,
            'customer'  =>  Customer::findOne(['id' => $order->customerID])
        ]);
    }

    public function actionGetorderpreview(){
        if(!\Yii::$app->request->isAjax){
            throw new UnsupportedMediaTypeHttpException("Этот запрос возможен только через ajax!");
        }

        $order = History::findOne(['id' => \Yii::$app->request->post("expandRowKey")]);

        if(!$order){
            throw new NotFoundHttpException("Заказ ".\Yii::$app->request->post("expandRowKey")." не найден!");
        }

        return $this->renderAjax('_orderPreview', [
            'model' =>  $order
        ]);
    }

    public function actionRestoreitemdata(){
        if(!\Yii::$app->request->isAjax){
            throw new UnsupportedMediaTypeHttpException("Этот запрос возможен только через ajax!");
        }

        \Yii::$app->response->format = 'json';

        $item = SborkaItem::findOne(['itemID' => \Yii::$app->request->post("itemID"), 'orderID' => \Yii::$app->request->post("orderID")]);

        if(!$item){
            throw new NotFoundHttpException("Товар ".\Yii::$app->request->post("itemID")." в заказе ".\Yii::$app->request->post("orderID")." не найден!");
        }

        $order = History::findOne(['id' => $item->orderID]);

        if(!$order){
            throw new NotFoundHttpException("Заказ ".$item->orderID." не найден!");
        }

        $good = Good::findOne(['id' => $item->itemID]);

        if(!$good){
            throw new NotFoundHttpException("Товар ".$item->itemID." не найден!");
        }

        $item->name = $good->Name;
        //$item->count = $item->originalCount;
        $item->originalPrice = $order->isOpt() ? $good->PriceOut1 : $good->PriceOut2;

        if($item->save(false)){
            //$good->count = $good->count - $item->addedCount;

            return $good->save(false);
        }

        return false;
    }

    public function actionChangeiteminorderstate(){
        if(!\Yii::$app->request->isAjax){
            throw new UnsupportedMediaTypeHttpException("Этот запрос возможен только через ajax!");
        }

        $item = SborkaItem::findOne(['itemID' => \Yii::$app->request->post("itemID"), 'orderID' => \Yii::$app->request->post("orderID")]);

        if(!$item){
            throw new NotFoundHttpException("Товар ".\Yii::$app->request->post("itemID")." в заказе ".\Yii::$app->request->post("orderID")." не найден!");
        }

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

    public function actionGetboxes(){
        return $this->render('_boxes', [
            'boxes' =>  Box::findAll()
        ]);
    }


    public function actionDeleteorder(){
        if(!\Yii::$app->request->isAjax){
            throw new UnsupportedMediaTypeHttpException("Этот запрос возможен только через ajax!");
        }

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

    public function actionUpdateorderprices(){
        if(!\Yii::$app->request->isAjax){
            throw new UnsupportedMediaTypeHttpException("Этот запрос возможен только через ajax!");
        }

        $d = \Yii::$app->request->post();
        if($d['type'] == 'opt' || $d['type'] == 'rozn'){
            $order = History::findOne(['id' => $d['OrderID']]);
            $order->recalculatePrices($d['type']);
        }
    }

    public function actionOrderchanges(){
        if(!\Yii::$app->request->isAjax){
            throw new UnsupportedMediaTypeHttpException("Этот запрос возможен только через ajax!");
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
            throw new UnsupportedMediaTypeHttpException("Этот запрос возможен только через ajax!");
        }

        \Yii::$app->response->format = 'json';

        $request = \Yii::$app->request->post();

        $request['selectedItems'] = Json::decode($request['selectedItems']);

        $items = SborkaItem::find()->where(['orderID' => $request['orderID']]);

        if($request['discountRewriteType'] == 1){
            if(empty($request['selectedItems'])){
                throw new BadRequestHttpException("Переданы не все параметры!");
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

    public function actionControl($param = null){
        if($param == null){
            return $this->render('control_index');
        }

        $order = History::findOne(['id' => $param]);

        if(!$order){
            throw new NotFoundHttpException("Такого заказа не существует!");
        }

        $items = SborkaItem::findAll(['orderID' => $order->id]);

        return $this->render('control', [
            'order' =>  $order,
            'items' =>  $items
        ]);
    }

    public function actionPrintinvoice($param){
        $order = History::findOne(['id' => $param]);

        if(!$order){
            throw new NotFoundHttpException("Такого заказа не существует!");
        }

        return $this->renderPartial('print/invoice', [
            'order'         =>  $order,
            'orderItems'    =>  SborkaItem::findAll(['orderID' => $order->id]),
            'act'           =>  'printOrder'
        ]);
    }

    public function actionGetlastid(){
        if(!\Yii::$app->request->isAjax){
            throw new UnsupportedMediaTypeHttpException("Этот запрос возможен только через ajax!");
        }

        //\Yii::$app->response->format = 'json';

        return History::find()->select("id")->orderBy("id desc")->limit(1)->scalar();
    }

    public function actionUsepricerule(){
        if(!\Yii::$app->request->isAjax){
            throw new UnsupportedMediaTypeHttpException("Этот запрос возможен только через ajax!");
        }

        \Yii::$app->response->format = 'json';

        $request = \Yii::$app->request->post();

        $priceRule = Pricerule::findOne(['id' => $request['priceRule']]);

        $order = History::findOne(['id' => $request['orderID']]);

        if(!$order){
            throw new NotFoundHttpException("Такого заказа не существует!");
        }elseif(!$priceRule){
            throw new NotFoundHttpException("Такого ценового правила не существует!");
        }

        $items = SborkaItem::findAll(['orderID' => $order->id]);

        $priceRuleHelper = new PriceRuleHelper();
        $priceRuleHelper->cartSumm = $order->originalSum;

        foreach($items as $item){
            $priceRuleHelper->recalcSborkaItem($item, $priceRule);
            $item->save();
        }

        //тут должна быть функция пересчёта

        return true;
    }
}
