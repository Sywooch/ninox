<?php

namespace backend\modules\cashbox\controllers;

use backend\models\CashboxCustomerForm;
use backend\models\CashboxItem;
use backend\models\CashboxOrder;
use backend\models\Customer;
use backend\models\Good;
use backend\models\History;
use backend\models\SborkaItem;
use common\models\Category;
use common\models\Siteuser;
use yii\data\ActiveDataProvider;
use yii\web\Cookie;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use backend\controllers\SiteController as Controller;

class DefaultController extends Controller
{
    public function beforeAction($action){
        if(!\Yii::$app->request->cookies->has("cashboxPriceType")){
            \Yii::$app->response->cookies->add(new Cookie([
                'name'      =>  'cashboxPriceType',
                'value'     =>  '0'
            ]));
        }

        return parent::beforeAction($action);
    }

    public function actionCompletesell(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Этот метод доступен только через ajax!");
        }

        $cashboxOrder = CashboxOrder::findOne(['id' => \Yii::$app->request->cookies->getValue("cashboxOrderID")]);

        if(!$cashboxOrder){
            throw new NotFoundHttpException("Такой заказ не найден!");
        }

        $order = new History([
            'responsibleUserID' =>  $cashboxOrder->responsibleUser,
            'customerID'        =>  $cashboxOrder->customerID,
            'originalSum'       =>  $cashboxOrder->sum
        ]);

        if($cashboxOrder->customerID != 0){
            $customer = Customer::findOne(['ID' => $cashboxOrder->customerID]);

            $order->customerEmail = $customer->email;

            $nameParts = explode(' ', $customer->Company);

            $order->customerName = $nameParts[0];
            $order->customerSurname = $nameParts[1];
        }

        $order->actualAmount = \Yii::$app->request->post("actualAmount");

        if($order->save(false)){
            foreach($cashboxOrder->items as $item){


                $sborkaItem = new SborkaItem([
                    'orderID'       =>  $order->id,
                    'itemID'        =>  $item->itemID,
                    'name'          =>  $item->name,
                    'count'         =>  $item->count,
                    'originalCount' =>  $item->count,
                    'originalPrice' =>  $item->originalPrice,
                    'discountSize'  =>  $item->discountSize,
                    'discountType'  =>  $item->discountType,
                    'priceRuleID'   =>  $item->priceRuleID,
                    'category'      =>  $item->category,
                    'customerRule'  =>  $item->customerRule
                ]);

                if($sborkaItem->save()){
                    $item->delete();
                }

                $cashboxOrder->createdOrder = $order->id;
            }

            $cashboxOrder->doneTime = date('Y-m-d H:i:s');
            $cashboxOrder->save(false);

            \Yii::$app->response->cookies->remove('cashboxOrderID');
            \Yii::$app->response->cookies->remove('cashboxCurrentCustomer');

            return $cashboxOrder->createdOrder;
        }
    }

    public function actionChangecashboxtype(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Этот метод доступен только через ajax!");
        }

        \Yii::$app->response->format = 'json';

        $priceType = \Yii::$app->request->cookies->getValue("cashboxPriceType", 0);

        $priceType = $priceType == 1 ? '0' : '1';

        \Yii::$app->response->cookies->remove('cashboxPriceType');

        \Yii::$app->response->cookies->add(new Cookie([
            'name'      =>  'cashboxPriceType',
            'value'     =>  $priceType
        ]));

        if(\Yii::$app->request->cookies->has("cashboxOrderID")){
            $cashboxOrder = CashboxOrder::findOne(['id' => \Yii::$app->request->cookies->getValue("cashboxOrderID")]);

            if($cashboxOrder){
                $cashboxOrder->priceType = $priceType;

                $cashboxOrder->save();

                return [
                    'priceType' =>  $cashboxOrder->priceType,
                    'orderSum'  =>  $cashboxOrder->sum,
                    'orderToPay'=>  $cashboxOrder->toPay
                ];
            }
        }

        //Тут также можно будет добавить логику изменения цены в заказе

        return [
            'priceType' =>  $priceType
        ];
    }

    public function actionChangemanager(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        if(\Yii::$app->request->post("action") == 'showList'){
            return $this->renderAjax('_changeManager', [
                'managers'  =>  Siteuser::getActiveUsers()
            ]);
        }

        \Yii::$app->response->cookies->add(new Cookie([
            'name'      =>  'cashboxManager',
            'value'     =>  \Yii::$app->request->post("manager")
        ]));

        $cashboxOrder = CashboxOrder::findOne(['id' => \Yii::$app->request->cookies->getValue("cashboxOrderID")]);

        if($cashboxOrder){
            $cashboxOrder->responsibleUser = \Yii::$app->request->post("manager");

            $cashboxOrder->save(false);
        }

        return \Yii::$app->request->post("manager");
    }

    public function actionChangeitemcount(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        \Yii::$app->response->format = 'json';

        $item = CashboxItem::findOne(['orderID' => \Yii::$app->request->cookies->getValue('cashboxOrderID'), 'itemID' => \Yii::$app->request->post("itemID")]);

        $item->count = \Yii::$app->request->post("count");

        if($item->save(false)){
            $cashboxOrder = CashboxOrder::findOne(['id' => \Yii::$app->request->cookies->getValue("cashboxOrderID")]);

            if($cashboxOrder){
                return [
                    'itemsCount'    =>  count($cashboxOrder->items),
                    'sum'           =>  $cashboxOrder->sum,
                    'toPay'         =>  $cashboxOrder->toPay,
                ];
            }

            return false;
        }

        return false;
    }

    public function actionRemoveitem(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        \Yii::$app->response->format = 'json';

        $itemID = \Yii::$app->request->post("itemID");

        $orderItem = CashboxItem::find()->where(['orderID' =>  \Yii::$app->request->cookies->getValue("cashboxOrderID")]);

        if($itemID != 'all'){
            $orderItem->andWhere(['itemID' => $itemID]);
        }

        if($orderItem->count() < 0){
            throw new NotFoundHttpException("Такой товар не найден!");
        }

        foreach($orderItem->each() as $item){
            if(!$item->delete()){
                return false;
            }
        }

        if($itemID != 'all'){
            $cashboxOrder = CashboxOrder::findOne(['id' => \Yii::$app->request->cookies->getValue("cashboxOrderID")]);

            if($cashboxOrder){
                return [
                    'itemsCount'    =>  count($cashboxOrder->items),
                    'sum'           =>  $cashboxOrder->sum,
                    'toPay'         =>  $cashboxOrder->toPay,
                ];
            }
        }

        return true;
    }

    public function actionIndex(){
        if(\Yii::$app->request->cookies->has('cashboxOrderID')){
            $order = CashboxOrder::findOne(['id'    =>  \Yii::$app->request->cookies->getValue('cashboxOrderID')]);
        }else{
            $order = new CashboxOrder();
        }

        $customer = false;

        if(\Yii::$app->request->cookies->has('cashboxCurrentCustomer')){
            $customer = \Yii::$app->request->cookies->getValue('cashboxCurrentCustomer');
        }

        if(\Yii::$app->request->post("CashboxCustomerForm")){
            $cashboxCustomerForm = new CashboxCustomerForm();
            $cashboxCustomerForm->load(\Yii::$app->request->post());

            if($cashboxCustomerForm->save()){
                if(!$order->isNewRecord){
                    $order->customerID = $cashboxCustomerForm->id;

                    $order->save(false);
                }

                \Yii::$app->response->cookies->add(new Cookie([
                    'name'  =>  'cashboxCurrentCustomer',
                    'value' =>  $cashboxCustomerForm->id
                ]));

                $customer = $cashboxCustomerForm->id;
            }
        }

        if(!empty($order->customerID)){
            $customer = $order->customerID;
        }

        if($customer){
            $customer = Customer::findOne($customer);
        }

        $orderItems = new ActiveDataProvider([
            'query'     =>  CashboxItem::find()->where(['orderID' =>    \Yii::$app->request->cookies->getValue('cashboxOrderID')]),
            'pagination'    =>  [
                'pageSize'  =>  0
            ]
        ]);

        $orderToPay = $orderDiscountSize = $orderDiscountPercent = $orderSum = 0;

        $orderItemsIDs = [];

        foreach($orderItems->getModels() as $item){
            $orderItemsIDs[] = $item->itemID;
            $orderToPay += $item->price;
        }

        $goodsModels = [];

        foreach(Good::find()->where(['in', 'ID', $orderItemsIDs])->each() as $item){
            $goodsModels[$item->ID] = $item;
        }

        return $this->render('index', [
            'goodsModels'       =>  $goodsModels,
            'orderItems'        =>  $orderItems,
            'order'             =>  $order,
            'customer'          =>  $customer,
            'manager'           =>  Siteuser::getActiveUsers()[\Yii::$app->request->cookies->getValue("cashboxManager", 0)]
        ]);
    }

    public function actionChangecustomer(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        \Yii::$app->response->format = 'json';

        if(\Yii::$app->request->cookies->has("cashboxOrderID")){
            $order = CashboxOrder::findOne(\Yii::$app->request->cookies->getValue("cashboxOrderID"));

            $order->customerID = \Yii::$app->request->post("customerID");

            $order->save(false);
        }

        \Yii::$app->response->cookies->add(new Cookie([
            'name'  =>  'cashboxCurrentCustomer',
            'value' =>  \Yii::$app->request->post("customerID")
        ]));

        return true;
    }

    public function actionFindcustomer(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        \Yii::$app->response->format = 'json';

        $attribute  = \Yii::$app->request->get("attribute");
        $query      = \Yii::$app->request->get("query");

        $customer   = Customer::find()->select(['ID', 'Company', 'phone', 'cardNumber'])->where(['like', $attribute, $query]);

        return $customer->all();
    }

    public function actionChecks(){
        return $this->render('checks', [
            'checksItems'   =>  new ActiveDataProvider([
                'query'     =>  CashboxOrder::find()->where(['postpone' => 1])
            ])
        ]);
    }

    public function actionReturns(){
        $orders = CashboxOrder::find()->where(['return' => 1]);

        $date = time() - (date('H') * 3600 + date('i') * 60 + date('s'));

        switch(\Yii::$app->request->get('smartfilter')){
            case 'yesterday':
                $orders->andWhere('doneTime >= \''.\Yii::$app->formatter->asDatetime($date - 86400, 'php:Y-m-d H:i:s')."'");
                $orders->andWhere('doneTime < \''.\Yii::$app->formatter->asDatetime($date, 'php:Y-m-d H:i:s')."'");
                break;
            case 'week':
                $orders->andWhere('doneTime >= \''.\Yii::$app->formatter->asDatetime(($date - (date("N") - 1) * 86400), 'php:Y-m-d H:i:s')."'");
                break;
            case 'month':
                $orders->andWhere('doneTime >= \''.\Yii::$app->formatter->asDatetime(($date - (date("j") - 1) * 86400), 'php:Y-m-d H:i:s')."'");
                break;
            case 'today':
            default:
                $orders->andWhere('doneTime >= \''.\Yii::$app->formatter->asDatetime($date, 'php:Y-m-d H:i:s')."'");
                break;
        }

        return $this->render('returns', [
            'returns'   =>  new ActiveDataProvider([
                'query'     =>  $orders
            ])
        ]);
    }

    public function actionGetsaledetails(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        $cashboxOrder = CashboxOrder::findOne(['ID'    =>  \Yii::$app->request->post("orderID")]);

        if(!$cashboxOrder){
            throw new NotFoundHttpException("Такого заказа не существует!");
        }

        return $this->renderAjax('_orderPreview', [
            'goods' =>  new ActiveDataProvider([
                'query' =>  SborkaItem::find()->where(['orderID'   =>  $cashboxOrder->createdOrder]),
            ])
        ]);
    }

    public function actionSales(){
        $orders = CashboxOrder::find();

        $date = time() - (date('H') * 3600 + date('i') * 60 + date('s'));

        \Yii::trace($date);

        switch(\Yii::$app->request->get('smartfilter')){
            case 'yesterday':
                $orders->andWhere('doneTime >= \''.\Yii::$app->formatter->asDatetime($date - 86400, 'php:Y-m-d H:i:s')."'");
                $orders->andWhere('doneTime < \''.\Yii::$app->formatter->asDatetime($date, 'php:Y-m-d H:i:s')."'");
                break;
            case 'week':
                $orders->andWhere('doneTime >= \''.\Yii::$app->formatter->asDatetime(($date - (date("N") - 1) * 86400), 'php:Y-m-d H:i:s')."'");
                break;
            case 'month':
                $orders->andWhere('doneTime >= \''.\Yii::$app->formatter->asDatetime(($date - (date("j") - 1) * 86400), 'php:Y-m-d H:i:s')."'");
                break;
            case 'today':
            default:
                $orders->andWhere('doneTime >= \''.\Yii::$app->formatter->asDatetime($date, 'php:Y-m-d H:i:s')."'");
                break;
        }

        $dataProvider = new ActiveDataProvider([
            'query'     =>  $orders,
            'sort'      =>  [
                'defaultOrder'  =>  ['doneTime' =>  SORT_DESC]
            ]
        ]);

        $customersIDs = [];
        $customers = [];

        foreach($dataProvider->getModels() as $sell){
            $customersIDs[] = $sell->customerID;
        }

        foreach(Customer::find()->where(['in', 'id', $customersIDs])->each() as $customer){
            $customers[$customer->ID] = $customer;
        }

        return $this->render('sales', [
            'customers'     =>  $customers,
            'salesProvider' =>  $dataProvider
        ]);
    }

    public function actionReturnorder(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        $cashboxOrder = CashboxOrder::findOne(['id' => \Yii::$app->request->cookies->getValue("cashboxOrderID")]);

        if(!$cashboxOrder){
            throw new NotFoundHttpException("Такой заказ не найден!");
        }

        $items = CashboxItem::find()->where(['orderID' => $cashboxOrder->id]);

        if($items->count() < 0){
            throw new NotFoundHttpException("В заказе не найдено ни одного товара!");
        }

        foreach($items->each() as $item){
            $good = Good::findOne(['ID' => $item->itemID]);
            $good->count += $item->count;

            if($good->save(false)){
                $item->delete();
            }else{
                return false;
            }
        }

        $cashboxOrder->doneTime = date('Y-m-d H:i:s');
        $cashboxOrder->return = 1;
        $cashboxOrder->save(false);

        \Yii::$app->response->cookies->remove('cashboxOrderID');

        return $cashboxOrder->id;
    }

    public function actionPostponecheck(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        $cashboxOrder = CashboxOrder::findOne(['id' => \Yii::$app->request->cookies->getValue("cashboxOrderID")]);

        if(!$cashboxOrder){
            throw new NotFoundHttpException("Такой заказ не найден!");
        }

        $cashboxOrder->postpone = 1;

        if($cashboxOrder->save(false)){
            \Yii::$app->response->cookies->remove('cashboxOrderID');
        }

        return $cashboxOrder->id;
    }

    public function actionAdditem(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        $itemID = \Yii::$app->request->post("itemID");

        $good = Good::find()->where(['or', 'ID = '.$itemID, 'BarCode1 = '.$itemID, 'Code = '.$itemID])->one();

        if(!$good){
            throw new NotFoundHttpException("Такой товар не найден!");
        }

        \Yii::$app->response->format = 'json';

        if(\Yii::$app->request->cookies->has("cashboxOrderID")){
            $cashboxOrder = CashboxOrder::findOne(['id' => \Yii::$app->request->cookies->getValue("cashboxOrderID")]);
        }else{
            $cashboxOrder = new CashboxOrder();
        }

        if($cashboxOrder->isNewRecord){
            if(\Yii::$app->request->cookies->has("cashboxCurrentCustomer")){
                $cashboxOrder->customerID = \Yii::$app->request->cookies->getValue("cashboxCurrentCustomer");
            }
            $cashboxOrder->createdTime = date('Y-m-d H:i:s');
            $cashboxOrder->priceType = \Yii::$app->request->cookies->getValue('cashboxPriceType', 0);

            if($cashboxOrder->save(false)){
                \Yii::$app->response->cookies->add(new Cookie([
                    'name'      =>  'cashboxOrderID',
                    'value'     =>  $cashboxOrder->id
                ]));
            }
        }

        $orderItem = CashboxItem::findOne(['orderID' => $cashboxOrder->id, 'itemID' => $good->ID]);

        if(!$orderItem){
            $orderItem = new CashboxItem();

            $orderItem->orderID = $cashboxOrder->id;
            $orderItem->itemID = $good->ID;
            $orderItem->count = 1;
            $orderItem->category = Category::find()->select("Code")->where(['ID' => $good->GroupID])->scalar();
            $orderItem->name = $good->Name;
            $orderItem->originalPrice = $cashboxOrder->priceType == 1 ? $good->PriceOut1 : $good->PriceOut2;
        }else{
            $orderItem->count += 1;
        }

        $return = [
            'type'          =>  $orderItem->isNewRecord ? 'add' : 'update',
        ];

        if($orderItem->save(false)){
            $return = array_merge($return, [
                'toPay'     =>  $cashboxOrder->toPay,
                'sum'       =>  $cashboxOrder->sum,
                'itemsCount'=>  count($cashboxOrder->items)
            ]);

            return $return;
        }

        return $return;
    }
}
