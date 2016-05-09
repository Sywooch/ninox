<?php

namespace backend\modules\orders\controllers;

use backend\models\HistorySearch;
use backend\models\OrdersStats;
use backend\modules\orders\models\OrderPreviewForm;
use common\helpers\PriceRuleHelper;
use backend\models\Customer;
use backend\models\CustomerAddresses;
use backend\models\CustomerContacts;
use backend\models\Good;
use backend\models\History;
use backend\models\NovaPoshtaOrder;
use common\models\DeliveryParam;
use common\models\DeliveryType;
use common\models\PaymentType;
use common\models\Pricerule;
use backend\models\SborkaItem;
use common\models\Siteuser;
use sammaye\audittrail\AuditTrail;
use yii\base\ErrorException;
use yii\data\ActiveDataProvider;
use backend\controllers\SiteController as Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnsupportedMediaTypeHttpException;
use yii\widgets\ActiveForm;

class DefaultController extends Controller
{

    public function actionIndex(){
        $date = time() - (date('H') * 3600 + date('i') * 60 + date('s'));

        $showDates = \Yii::$app->request->get("showDates");

        $timeFrom = $timeTo = null;

        if(empty(\Yii::$app->request->get("ordersStatus"))){
            $showDates = 'alltime';
        }

        switch($showDates){
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

        $this->getView()->params['showDateButtons'] = true;

        $historySearch = new HistorySearch();

        $orders = $historySearch->search(\Yii::$app->request->get(), true);

        $stats = new OrdersStats();

        $ordersStats = [
            'totalOrders'       =>  $stats->orders,
            'completedOrders'   =>  0,
            'notCalled'         =>  0,
            'ordersFaktSumm'    =>  $stats->ordersAmount,
            'ordersSumm'        =>  $stats->ordersActualAmount
        ];

	    /*foreach($orders->asArray()->each() as $order){
            $ordersStats['totalOrders']++;
            $ordersStats['completedOrders'] += $order['done'];
            $ordersStats['notCalled']   += $order['callback'] != 1;
            $ordersStats['ordersFaktSumm']   += $order['actualAmount'];
            $ordersStats['ordersSumm']   += $order['originalSum'];
        }*/

        return $this->render('index', [
            'collectors'        =>  Siteuser::getCollectorsWithData($timeTo, $timeFrom),
            'showUnfinished'    =>  !\Yii::$app->request->get("showDates") || \Yii::$app->request->get("showDates") == 'today',
            'ordersStats'       =>  $ordersStats,
            'ordersStatsModel'  =>  $stats,
            'searchModel'       =>  $historySearch,
            'orders'            =>  $historySearch->search(\Yii::$app->request->get())
        ]);
    }

    public function actionUpdate(){
        $order = \Yii::$app->request->post("orderID");

    }

    public function actionGetDeliveries($type = ''){
        if(empty($type)){
            if(!\Yii::$app->request->isAjax ){
                throw new BadRequestHttpException("Данный запрос возможен только через ajax!");
            }

            \Yii::$app->response->format = 'json';

            $type = \Yii::$app->request->post("type");

            if(empty($type) && !empty(\Yii::$app->request->post("depdrop_all_params")['deliveryTypeInput'])){
                $type = 'deliveryParam';
            }
        }

        switch($type){
            case 'deliveryType':
                $query = DeliveryType::find()->where('enabled = 1');

                if(!\Yii::$app->request->isAjax){
                    return ArrayHelper::map($query->asArray()->all(), 'id', 'description');
                }

                $results = [];

                foreach($query->each() as $result){
                    $results[] = ['id' => $result->id, 'name' => $result->description];
                }

                return $results;
                break;
            case 'deliveryParam':
                $deliveryType = empty(\Yii::$app->request->post("depdrop_all_params")['deliveryTypeInput']) ? \Yii::$app->request->post('deliveryType') : \Yii::$app->request->post("depdrop_all_params")['deliveryTypeInput'];

                $deliveryType = DeliveryType::find()->where(['id' => $deliveryType])->one();

                if(!$deliveryType){
                    throw new NotFoundHttpException("Не найден переданый тип доставки!");
                }

                $params = $deliveryType->params;

                if(!is_array($params)){
                    $params = [$params];
                }

                $result = [];

                foreach($params as $param){
                    $result[] = ['id' => $param->id, 'name' => $param->description];
                }

                return ['output' => $result];
                break;
        }
    }

    public function actionGetPayments($type = ''){
        if(empty($type)){
            if(!\Yii::$app->request->isAjax ){
                throw new BadRequestHttpException("Данный запрос возможен только через ajax!");
            }

            \Yii::$app->response->format = 'json';

            $type = \Yii::$app->request->post("type");

            if(empty($type) && !empty(\Yii::$app->request->post("depdrop_all_params")['paymentTypeInput'])){
                $type = 'paymentParam';
            }
        }

        switch($type){
            case 'paymentType':
                $query = PaymentType::find()->where('enabled = 1');

                if(!\Yii::$app->request->isAjax){
                    return ArrayHelper::map($query->asArray()->all(), 'id', 'description');
                }

                $results = [];

                foreach($query->each() as $result){
                    $results[] = ['id' => $result->id, 'name' => $result->description];
                }

                return $results;
                break;
            case 'paymentParam':
                $paymentType = empty(\Yii::$app->request->post("depdrop_all_params")['paymentTypeInput']) ? \Yii::$app->request->post('paymentType') : \Yii::$app->request->post("depdrop_all_params")['paymentTypeInput'];

                $paymentType = PaymentType::find()->where(['id' => $paymentType])->one();

                if(!$paymentType){
                    throw new NotFoundHttpException("Не найден переданый тип оплаты!");
                }

                $result = [];

                $params = $paymentType->params;

                if(!empty($params)){
                    if(!is_array($params)){
                        $params = [$params];
                    }

                    foreach($params as $param){
                        $result[] = ['id' => $param->id, 'name' => $param->description];
                    }
                }


                return ['output' => $result];
                break;
        }
    }

    /**
     * Возвращает и сохраняет превью блока заказа
     *
     * @return array|string
     * @throws NotFoundHttpException
     * @throws UnsupportedMediaTypeHttpException
     */
    public function actionOrderPreview(){
        if(!\Yii::$app->request->isAjax){
            throw new UnsupportedMediaTypeHttpException("Этот запрос возможен только через ajax!");
        }

        $orderForm = new OrderPreviewForm();

        $orderForm->load(\Yii::$app->request->post());

        if(empty($orderForm->id)){
            $orderForm->id = \Yii::$app->request->post("expandRowKey");
        }

        $order = History::findOne(['id' => $orderForm->id]);

        if(!$order){
            throw new NotFoundHttpException("Заказ {$orderForm->id} не найден!");
        }

        if(!empty(\Yii::$app->request->post("ajax"))){
            \Yii::$app->response->format = 'json';

            return ActiveForm::validate($orderForm);
        }

        $orderForm->loadOrder($order);

        if(\Yii::$app->request->post("OrderPreviewForm") && $orderForm->load(\Yii::$app->request->post()) && \Yii::$app->request->post("action") == "save"){
            $orderForm->save();
        }

        return $this->renderAjax('_orderPreview', [
            'model' =>  $orderForm
        ]);
    }

    public function actionConfirmordercall(){
        if(!\Yii::$app->request->isAjax){
            throw new UnsupportedMediaTypeHttpException("Этот запрос возможен только через ajax!");
        }

        $orderID = \Yii::$app->request->post("OrderID");

        $order = History::findOne($orderID);

        if(empty($order)){
            throw new NotFoundHttpException("Заказ с идентификатором {$orderID} не найден!");
        }

        $order->callback = \Yii::$app->request->post("confirm") == "true" ? 1 : 2;

        $order->save(false);

        \Yii::$app->response->format = 'json';

        return [
            'callback'  =>  $order->callback,
            'status'    =>  [
                'id'            =>  $order->status,
                'description'   =>  $order->statusDescription
            ]
        ];
    }

    public function actionDoneorder(){
        if(!\Yii::$app->request->isAjax){
            throw new UnsupportedMediaTypeHttpException("Этот запрос возможен только через ajax!");
        }

        $orderID = \Yii::$app->request->post("OrderID");

        $order = History::findOne($orderID);

        if(!$order){
            throw new NotFoundHttpException("Заказ с идентификатором {$orderID} не найден!");
        }

        $order->done = $order->done == 1 ? 0 : 1;

        $order->save(false);

        \Yii::$app->response->format = 'json';

        return [
            'done'      =>  $order->done,
            'status'    =>  [
                'id'            =>  $order->status,
                'description'   =>  $order->statusDescription
            ]
        ];
    }

    /**
     * Страница просмотра и редактирования заказа
     * @param string $param ID заказа
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionShoworder($param = ''){
        $order = History::findOne($param);

        if(!$order){
            throw new NotFoundHttpException("Такого заказа не существует!");
        }

        if(\Yii::$app->request->isAjax && !\Yii::$app->request->get("_pjax")){
            switch(\Yii::$app->request->post("action")){
                case 'getEditItemForm':
                    $item = $order->findItem(\Yii::$app->request->post("itemID"));

                    if(empty($item)){
                        throw new NotFoundHttpException("Товар не найден в заказе!");
                    }

                    return $this->renderAjax('_order_itemEdit', ['model' => $item]);
                    break;
                case 'saveEditItemForm':
                    break;
            }
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
            };

            $order->save();

            $item->save();
        }

        if(\Yii::$app->request->post("History")){
            $order->load(\Yii::$app->request->post());
            $order->save(false);
        }

        $st = [];
        $sborkaItems = SborkaItem::findAll(['orderID' => $order->id]);

        foreach($sborkaItems as $sItem){
            $st[] = $sItem->itemID;
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
            'order'                 =>  $order,
            'items'                 =>  $order->items,
            'itemsDataProvider'     =>  $itemsDataProvider,
            'priceRules'            =>  Pricerule::find()->orderBy('priority')->all(),
            'customer'              =>  $order->customer
        ]);
    }

    public function actionShowlist($context = false, $ordersSource = false){
        if(!\Yii::$app->request->isAjax && !$context){
            throw new BadRequestHttpException("Этот метод доступен только через ajax!");
        }

        if(!$context){
            $context = !empty(\Yii::$app->request->get("context")) ? true : false;
        }

        $historySearch = new HistorySearch();

        $return = $this->renderPartial('_ordersList', [
            'searchModel'       =>  $historySearch,
            'orderSource'       =>  $ordersSource,
            'orders'            =>  $historySearch->search(
                $ordersSource == 'search' ? [] :
                    $ordersSource != false ? array_merge(['ordersSource' => $ordersSource], \Yii::$app->request->get()) : \Yii::$app->request->get())
        ]);

        if(!$context){
            \Yii::$app->response->format = 'json';
        }

        return $return;
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
        $item->originalPrice = $order->isWholesale() ? $good->PriceOut1 : $good->PriceOut2;

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

        $type = \Yii::$app->request->post("type");
        $orderID = \Yii::$app->request->post("OrderID");

        $order = History::findOne(['id' => $orderID]);

        if(!$order){
            throw new NotFoundHttpException("Заказ с идентификатором {$orderID} не найден!");
        }

        if(in_array($type, ['opt', 'rozn'])){
            $order->recalculatePrices($type);
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
            $order = History::findOne($order);
        }

        $invoice = new NovaPoshtaOrder([
            'orderData'         =>  $order,
        ]);

        if(!empty(\Yii::$app->request->post("NovaPoshtaOrder")) && $invoice->load(\Yii::$app->request->post())){
            $invoice->save();
        }

        if(!empty($invoice->deliveryReference)){
            return $this->renderAjax('print/novaPoshta_invoice', [
                'invoice'   =>  $invoice
            ]);
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
        if(\Yii::$app->request->isAjax && !\Yii::$app->request->get("_pjax")){
            $param = \Yii::$app->request->post("orderID");
        }

        if($param == null){
            return $this->render('control_index');
        }

        $order = History::find()->where(['or', ['number' => $param], ['ID' => $param]])->one();

        if(!$order){
            throw new NotFoundHttpException("Такого заказа не существует!");
        }

        if(\Yii::$app->request->isAjax && !\Yii::$app->request->get("_pjax")){
            switch(\Yii::$app->request->post("action")){
                case 'clear':
                    $order->clearControl();
                    break;
                case 'add':
                    $requestedItemID = \Yii::$app->request->post("itemID");

                    $good = Good::find()->where(['or', ['ID' => $requestedItemID], ['Code' => $requestedItemID], ['BarCode1' => $requestedItemID]])->one();

                    if(!$good){
                        throw new NotFoundHttpException("Товар с идентификатором {$requestedItemID} не найден!");
                    }

                    $order->controlItem($good->ID);
                    break;

            }

            \Yii::$app->response->format = 'json';

            return [
                'items' =>  $order->notControlledItemsCount,
                'goods' =>  $order->notControlledGoodsCount
            ];
        }

        return $this->render('control', [
            'order' =>  $order,
        ]);
    }

    public function actionPrintinvoice($param){
        $order = History::findOne(['id' => $param]);

        if(!$order){
            throw new NotFoundHttpException("Такого заказа не существует!");
        }

        $sborkaItems = new ActiveDataProvider([
            'query'         =>  SborkaItem::find()->where(['orderID' => $order->id]),
            'pagination'    =>  [
                'pageSize'  =>  0
            ]

        ]);

        $itemIDs = $goods = [];

        foreach($sborkaItems->getModels() as $item){
            $itemIDs[] = $item->itemID;
        }

        foreach(Good::find()->where(['in', 'ID', $itemIDs])->each() as $good){
            $goods[$good->ID] = $good;
        }

        $customer = Customer::findOne($order->customerID);

        if(!$customer){
            $customer = new Customer();
        }

        return $this->renderAjax('print/invoice', [
            'order'         =>  $order,
            'goods'         =>  $goods,
            'orderItems'    =>  $sborkaItems,
            'customer'      =>  $customer,
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

        //\Yii::$app->response->format = 'json';

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
            if(!$item->save()){
                throw new ErrorException("Не удалось сохранить итем при пересчёте. ID: ".$item->id);
            }
        }

        //тут должна быть функция пересчёта

        return true;
    }

    public function actionSborka($param){
        $order = History::findOne($param);

        if(!$order){
            throw new NotFoundHttpException("Заказ с ID {$param} не найден");
        }

        if(\Yii::$app->request->isAjax && !\Yii::$app->request->get("_pjax")){
            \Yii::$app->response->format = 'json';

            switch(\Yii::$app->request->post("action")){
                case 'changeInOrder':
                    if(!$order->findItem(\Yii::$app->request->post("itemID"))){
                        throw new NotFoundHttpException("Товар не найден в заказе!");
                    }

                    $item = $order->findItem(\Yii::$app->request->post("itemID"));
                    $item->inOrder = $item->inOrder == 1 ? 0 : 1;
                    $item->save(false);

                    return $item->inOrder == 1 ? 1 : 0;
                    break;
                case 'changeNotFound':
                    if(!$order->findItem(\Yii::$app->request->post("itemID"))){
                        throw new NotFoundHttpException("Товар не найден в заказе!");
                    }

                    $item = $order->findItem(\Yii::$app->request->post("itemID"));
                    $item->notFounded = $item->notFounded == 1 ? 0 : 1;
                    $item->save(false);

                    return $item->notFounded == 1 ? 1 : 0;
                    break;
                case 'saveItemsCount';
                    foreach(\Yii::$app->request->post("fields") as $item){
                        if(!$order->findItem($item['itemID'])){
                            throw new NotFoundHttpException("Товар не найден в заказе!");
                        }

                        $itemModel = $order->findItem($item['itemID']);

                        $itemModel->setCount($item['count']);
                        $itemModel->save(false);
                    }
                    return true;
                    break;
            }
        }

        $this->layout = 'sborka';

        return $this->render('sborka', [
            'order' =>  $order
        ]);
    }

    public function actionSms(){
        if(!\Yii::$app->request->isAjax){
            throw new BadRequestHttpException("Этот запрос возможен только через ajax!");
        }

        $orderID = \Yii::$app->request->post("orderID");

        $order = History::findOne($orderID);

        if(!$order){
            throw new NotFoundHttpException("Заказ с идентификатором {$orderID} не найден!");
        }

        return $order->sendMessage(\Yii::$app->request->post("type"));
    }

}
