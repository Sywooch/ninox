<?php

namespace backend\modules\orders\controllers;

use backend\controllers\SiteController as Controller;
use backend\models\HistorySearch;
use backend\models\OrderCommentForm;
use backend\models\OrdersStats;
use backend\models\Customer;
use backend\models\Good;
use backend\models\History;
use backend\models\NovaPoshtaOrder;
use backend\models\SborkaItem;
use backend\modules\orders\models\CustomerCommentForm;
use backend\modules\orders\models\OrderCustomerForm;
use backend\modules\orders\models\OrderDeliveryForm;
use backend\modules\orders\models\OrderPreviewForm;
use common\helpers\PriceRuleHelper;
use common\models\DeliveryType;
use common\models\PaymentType;
use common\models\Pricerule;
use backend\models\Siteuser;
use sammaye\audittrail\AuditTrail;
use yii\base\ErrorException;
use yii\data\ActiveDataProvider;
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

        $showDates = \Yii::$app->request->get('showDates');

        $timeFrom = $timeTo = $date;

        if(empty(\Yii::$app->request->get('ordersStatus'))){
            $showDates = 'alltime';
        }

        $collectorsTimeTo = strtotime(date('Y-m-d')) + 86400;
        $collectorsTimeFrom = strtotime(date('Y-m-d'));

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

        if($showDates != 'alltime'){
            $timeTo = strtotime(date('Y-m-d')) + 86400;
            $timeFrom = strtotime(date('Y-m-d'));
        }

        $this->getView()->params['showDateButtons'] = true;

        $historySearch = new HistorySearch();

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
            'collectors'        =>  Siteuser::getActive(),
            'collectorsData'    =>  ['dateFrom' => $collectorsTimeFrom, 'dateTo' => $collectorsTimeTo],
            'showUnfinished'    =>  !\Yii::$app->request->get("showDates") || \Yii::$app->request->get("showDates") == 'today',
            'ordersStats'       =>  $ordersStats,
            'ordersStatsModel'  =>  $stats,
            'searchModel'       =>  $historySearch,
            'orders'            =>  $historySearch->search(\Yii::$app->request->get())
        ]);
    }

    /**
     * Хз че делает, скорее всего она депрекейтед
     * @deprecated
     */
    public function actionUpdate(){
        $order = \Yii::$app->request->post("orderID");

    }

    /**
     * Возвращает типы и параметры доставки для превью блока заказа
     * @param string $type
     * @return array
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionGetDeliveries($type = ''){
        if(empty($type)){
            if(!\Yii::$app->request->isAjax ){
                throw new BadRequestHttpException("Данный запрос возможен только через ajax!");
            }

            \Yii::$app->response->format = 'json';

            $type = \Yii::$app->request->post("type");

            if(empty($type) && !empty(\Yii::$app->request->post("depdrop_params"))){
                $order = History::findOne(['ID' => \Yii::$app->request->post("depdrop_params")]);
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
                $deliveryTypeID = \Yii::$app->request->post("depdrop_parents")[0];

                $deliveryType = DeliveryType::findOne(['id' => $deliveryTypeID]);

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

                return ['output' => $result, 'selected' =>
                    $order->deliveryType != $deliveryTypeID || empty($order->deliveryParam) ?(string)$params[0]['id'] : (string)$order->deliveryParam];
                break;
        }
    }

    /**
     * Восстанавливает заказ
     * @return bool
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionRestore(){
        if(!\Yii::$app->request->isAjax){
            throw new BadRequestHttpException('Данный метод доступен только через ajax!');
        }

        $orderID = \Yii::$app->request->post('orderID');
        $order = History::find()->where(['id' => $orderID])->with('items')->one();

        if(!$order){
            throw new NotFoundHttpException("Заказ с идентификатором {$orderID} не найден!");
        }

        if(!empty($order->items)){
            foreach($order->items as $item){
                $item->good->count -= $item->count;
                $item->good->save(false);
            }
        }

        $order->amountDeductedOrder = $order->realSum >= $order->customer->money ? $order->customer->money : $order->realSum;
        $order->customer->money -= $order->amountDeductedOrder;

        $order->deleted = 0;

        if($order->customer->save(false)){
            $order->save(false);
        }

        return true;
    }

    /**
     * Возвращает типы и параметры оплат для превью блока заказа
     * @param string $type
     * @return array
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionGetPayments($type = ''){
        if(empty($type)){
            if(!\Yii::$app->request->isAjax ){
                throw new BadRequestHttpException("Данный запрос возможен только через ajax!");
            }

            \Yii::$app->response->format = 'json';

            $type = \Yii::$app->request->post("type");

            if(empty($type) && !empty(\Yii::$app->request->post("depdrop_params"))){
                $order = History::findOne(['ID' => \Yii::$app->request->post("depdrop_params")]);
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
                $paymentTypeID = \Yii::$app->request->post("depdrop_parents")[0];

                $paymentType = PaymentType::findOne(['id' => $paymentTypeID]);

                if(!$paymentType){
                    throw new NotFoundHttpException("Не найден переданый тип оплаты!");
                }

                $result = [];
                $selected = '';

                $params = $paymentType->params;

                if(!empty($params)){
                    if(!is_array($params)){
                        $params = [$params];
                    }

                    foreach($params as $param){
                        $result[] = ['id' => $param->id, 'name' => $param->description];
                    }

                    $selected = $order->paymentType != $paymentTypeID || empty($order->paymentParam) ?
                        (string)$params[0]['id'] : (string)$order->paymentParam;
                }

                return ['output' => $result, 'selected' => $selected];
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
            $orderForm->save(false);
            \Yii::$app->response->format = 'json';
            return $orderForm;
        }

        return $this->renderAjax('_orderPreview', [
            'model' =>  $orderForm
        ]);
    }

    /**
     * Подтверждает или снимает статус дозвона до клиента
     * @return array
     * @throws NotFoundHttpException
     * @throws UnsupportedMediaTypeHttpException
     */
    public function actionConfirmordercall(){
        if(!\Yii::$app->request->isAjax){
            throw new UnsupportedMediaTypeHttpException("Этот запрос возможен только через ajax!");
        }

        $orderID = \Yii::$app->request->post("OrderID");

        $order = History::findOne($orderID);

        if(empty($order)){
            throw new NotFoundHttpException("Заказ с идентификатором {$orderID} не найден!");
        }

        \Yii::$app->request->post("confirm") == "true" ? $order->callback = 1 : ($order->callback ? $order->callback++ : $order->callback = 2);
        $order->callbackDate = date('Y-m-d H:i:s');
        $order->confirmed = $order->callback == 1 ? 1 : 0;

        $order->save(false);

        $comment = trim(\Yii::$app->request->post("comment"));
        if(!empty($comment)){
            $commentModel = new OrderCommentForm([
                'model'     =>  $order,
                'comment'   =>  $comment,
            ]);

            $commentModel->save();
        }

        \Yii::$app->response->format = 'json';

        return [
            'callback'  =>  $order->callback,
            'status'    =>  [
                'id'            =>  $order->status,
                'description'   =>  $order->statusDescription
            ]
        ];
    }

    /**
     * Ставит или снимает пометку о сборке заказа + отсылает смс получателю об этом
     * с условием, что это не заказы с детскими товарами
     * @return array
     * @throws NotFoundHttpException
     * @throws UnsupportedMediaTypeHttpException
     */
    public function actionDoneorder(){
        if(!\Yii::$app->request->isAjax){
            throw new UnsupportedMediaTypeHttpException("Этот запрос возможен только через ajax!");
        }

        $orderID = \Yii::$app->request->post("OrderID");

        $order = History::findOne($orderID);

        if(!$order){
            throw new NotFoundHttpException("Заказ с идентификатором {$orderID} не найден!");
        }

        $order->done = !$order->done;

        $order->save(false);

        \Yii::$app->response->format = 'json';

        return [
            'done'      =>  $order->done,
            'status'    =>  [
                'id'            =>  $order->status,
                'description'   =>  $order->statusDescription
            ],
            'sms'       =>  $order->responsibleUserID == 59 ? '' : $order->sendMessage('done')
        ];
    }

    /**
     * Страница просмотра и редактирования заказа
     * @param string $param ID заказа
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionShoworder($param = ''){
        $order = History::findWith()->where(['id' => $param])->one();

        if(!$order){
            throw new NotFoundHttpException("Такого заказа не существует!");
        }

        if(\Yii::$app->request->isAjax && !\Yii::$app->request->get("_pjax")){
            switch(\Yii::$app->request->post("action")){
                case 'merge':
                    $targetOrder = History::findOne(\Yii::$app->request->post("target"));

                    if(!$targetOrder){
                        throw new NotFoundHttpException("Целевой заказ не найден!");
                    }

                    return $order->mergeWith($targetOrder);
                    break;
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

        if(\Yii::$app->request->post("OrderCommentForm")){
            $commentModel = new OrderCommentForm([
                'model' =>  $order
            ]);

            $commentModel->load(\Yii::$app->request->post());

            $commentModel->save();
        }

        $orderCustomerForm = new OrderCustomerForm();
        $orderDeliveryForm = new OrderDeliveryForm();
        $customerComment = new CustomerCommentForm();

        $orderCustomerForm->loadOrder($order);

        if(\Yii::$app->request->post("OrderCustomerForm")){
            $orderCustomerForm->load(\Yii::$app->request->post());

            if($orderCustomerForm->save()){
                $order = $orderCustomerForm->order;
            }
        }

        $orderDeliveryForm->loadOrder($order);

        if(\Yii::$app->request->post("OrderDeliveryForm")){
            $orderDeliveryForm->load(\Yii::$app->request->post());

            if($orderDeliveryForm->save()){
                $order = $orderDeliveryForm->order;
            }
        }

        $customerComment->loadOrder($order);

        if(\Yii::$app->request->post("CustomerCommentForm")){
            $customerComment->load(\Yii::$app->request->post());
            $customerComment->save();
        }

        $customer = $order->customer;

        if(empty($customer)){
            $customer = new Customer();
        }

        return $this->render('order', [
            'customerForm'          =>  $orderCustomerForm,
            'deliveryForm'          =>  $orderDeliveryForm,
            'order'                 =>  $order,
            'items'                 =>  $order->items,
            'itemsDataProvider'     =>  new ActiveDataProvider([
                'query'     =>  $order->getItems(),
                'pagination'=>  [
                    'pageSize'  =>  100
                ],
                'sort'  =>  [
                    'defaultOrder' => [
                        'added'	=>	SORT_ASC
                    ],
                    'attributes' => [
                        'added' => [
                            'default' => SORT_ASC
                        ],
                    ]
                ]
            ]),
            'customerComment'       =>  $customerComment,
            'priceRules'            =>  Pricerule::find()->where(['enabled' => 1])->orderBy('priority')->all(),
            'customer'              =>  $customer
        ]);
    }

    public function actionEditableEdit(){
        if(!\Yii::$app->request->isAjax){
            throw new NotFoundHttpException("Данный метод возможен только через ajax!");
        }elseif(empty(\Yii::$app->request->post("hasEditable"))){
            throw new BadRequestHttpException("Данный метод предназначен для работы editable виджета!");
        }

        $orderID = \Yii::$app->request->post("editableKey");

        $order = History::findOne(['id' => $orderID]);

        if(!$order){
            throw new NotFoundHttpException("Заказ с идентификатором {$orderID} не найден!");
        }

        $attribute = \Yii::$app->request->post('editableAttribute');
        $data = \Yii::$app->request->post('History');
        $data = $data[\Yii::$app->request->post('editableIndex')];

        $order->$attribute = $data[$attribute];

        $order->save(false);

        return $order->$attribute;
    }

    public function actionShowlist($context = false, $ordersSource = false){
        if(!\Yii::$app->request->isAjax && !$context){
            throw new BadRequestHttpException('Этот метод доступен только через ajax!');
        }

        if(!$context){
            $context = !empty(\Yii::$app->request->get('context')) ? true : false;
        }

        $historySearch = new HistorySearch();

        $return = $this->renderPartial('_ordersList', [
            'searchModel'       =>  $historySearch,
            'orderSource'       =>  $ordersSource,
            'orders'            =>  $historySearch->search(
                $ordersSource == 'search' ? [
                    'ordersSource'  =>  'search'
                ] :
                    $ordersSource != false ? array_merge(['ordersSource' => $ordersSource], \Yii::$app->request->get()) : \Yii::$app->request->get())
        ]);

        if(!$context){
            \Yii::$app->response->format = 'json';
        }

        return $return;
    }

    public function actionRestoreitemdata(){
        if(!\Yii::$app->request->isAjax){
            throw new UnsupportedMediaTypeHttpException('Этот запрос возможен только через ajax!');
        }

        \Yii::$app->response->format = 'json';

        $itemID = \Yii::$app->request->post('ID');

        $item = SborkaItem::findOne(['ID' => $itemID]);

        if(!$item){
            throw new NotFoundHttpException("Товар {$itemID} не найден!");
        }

        $order = History::findOne(['id' => $item->orderID]);

        if(!$order){
            throw new NotFoundHttpException("Заказ {$item->orderID} не найден!");
        }

        $good = Good::findOne(['id' => $item->itemID]);

        if(!$good){
            throw new NotFoundHttpException("Товар {$item->itemID} не найден!");
        }

        $item->name = $good->Name;
        //$item->count = $item->originalCount;
        $item->originalPrice = $order->isWholesale ? $good->PriceOut1 : $good->PriceOut2;

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

        $itemID = \Yii::$app->request->post("ID");

        $item = SborkaItem::findOne(['ID' => $itemID]);

        if(!$item){
            throw new NotFoundHttpException("Товар {$itemID} не найден!");
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

        $item->save(false);

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

            foreach(SborkaItem::findAll(['orderID' => $orderID]) as $item){
                $item->returnToStore($order->orderSource);
            }

            if(!empty($order->customer)){
                $order->customer->money += $order->amountDeductedOrder;
                $order->customer->save(false);
            }

            $order->amountDeductedOrder = 0;

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
                                    ->where(['like', 'model', '%History', false])
                                    ->orWhere(['and', ['like', 'model', '%SborkaItem', false], ['in', 'model_id', SborkaItem::find()->select('ID')->where(['orderID' => $order])]]),
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

        if(!empty(\Yii::$app->request->post('NovaPoshtaOrder')) && $invoice->load(\Yii::$app->request->post())){
            $invoice->save();
        }

        if(!empty($invoice->deliveryReference)){
            return $this->renderPartial('print/novaPoshta_invoice', [
                'invoice'   =>  $invoice
            ]);
        }

        return $this->renderPartial('invoice', [
            'invoice'   =>  $invoice,
            'orderID'   =>  !empty($order) ? $order->ID : $param
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

        $order = History::findWith()->where(['or', ['number' => $param], ['ID' => $param]])->one();

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

                    $goods = Good::find()
                        ->where(['or',
                            ['BarCode2' => $requestedItemID],
                            ['ID' => $requestedItemID],
                            ['Code' => $requestedItemID],
                            ['BarCode1' => $requestedItemID]])
                        ->orderBy(['BarCode2' => SORT_DESC])
                        ->all();

                    if(empty($goods)){
                        throw new NotFoundHttpException("Товар с идентификатором {$requestedItemID} не найден!");
                    }

                    foreach($goods as $good){
                        try{
                            $order->controlItem($good->ID);
                            break;
                        }catch(NotFoundHttpException $e){

                        }
                    }

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
            throw new UnsupportedMediaTypeHttpException('Этот запрос возможен только через ajax!');
        }

        foreach(\Yii::$app->log->targets as $target){
            $target->enabled = false;
        }

        return History::find()->max('number');
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
        $priceRuleHelper->cartSumm = $order->sumWithoutDiscount;

        foreach($items as $item){
            $priceRuleHelper->recalcSborkaItem($item, $priceRule);
            if(!$item->save()){
                throw new ErrorException("Не удалось сохранить итем при пересчёте. ID: ".$item->id);
            }
        }

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
                    if(!$order->findItemByUniqID(\Yii::$app->request->post("itemID"))){
                        throw new NotFoundHttpException("Товар не найден в заказе!");
                    }

                    $item = $order->findItemByUniqID(\Yii::$app->request->post("itemID"));
                    $item->inOrder = $item->inOrder == 1 ? 0 : 1;
                    $item->save(false);

                    return $item->inOrder == 1 ? 1 : 0;
                    break;
                case 'changeNotFound':
                    if(!$order->findItemByUniqID(\Yii::$app->request->post("itemID"))){
                        throw new NotFoundHttpException("Товар не найден в заказе!");
                    }

                    $item = $order->findItemByUniqID(\Yii::$app->request->post("itemID"));
                    $item->nalichie = $item->nalichie == 1 ? 0 : 1;
                    $item->save(false);

                    return $item->nalichie == 1 ? 1 : 0;
                    break;
                case 'saveItemsCount';
                    foreach(\Yii::$app->request->post("fields") as $item){
                        if(!$order->findItemByUniqID($item['itemID'])){
                            throw new NotFoundHttpException("Товар не найден в заказе!");
                        }

                        $itemModel = $order->findItemByUniqID($item['itemID']);

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


    public function actionPaymentConfirmForm(){
        $model = new \frontend\models\PaymentConfirmForm();

        $model->load(\Yii::$app->request->post());

        if(!$model->save()){
            return false;
        }

        return true;
    }

}
