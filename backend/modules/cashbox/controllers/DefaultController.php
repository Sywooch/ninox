<?php

namespace backend\modules\cashbox\controllers;

use backend\models\CashboxCustomerForm;
use backend\models\CashboxItem;
use backend\models\CashboxOrder;
use backend\models\Customer;
use backend\models\Good;
use backend\models\History;
use backend\models\SborkaItem;
use common\models\Siteuser;
use yii\base\ErrorException;
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

        return \Yii::$app->cashbox->sell(\Yii::$app->request->post("actualAmount"));
    }

    public function actionChangecashboxtype(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Этот метод доступен только через ajax!");
        }

        \Yii::$app->response->format = 'json';

        \Yii::$app->cashbox->changePriceType();

        if(!empty(\Yii::$app->cashbox->order)){
            \Yii::$app->cashbox->recalculate();

            return [
                'priceType' =>  \Yii::$app->cashbox->order->priceType,
                'orderSum'  =>  \Yii::$app->cashbox->sum,
                'orderToPay'=>  \Yii::$app->cashbox->toPay
            ];
        }

        return [
            'priceType' =>  \Yii::$app->cashbox->priceType
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

        if(\Yii::$app->cashbox->changeCount(\Yii::$app->request->post("itemID"), \Yii::$app->request->post("count"))){
            return [
                'itemsCount'    =>  \Yii::$app->cashbox->itemsCount,
                'sum'           =>  \Yii::$app->cashbox->sum,
                'toPay'         =>  \Yii::$app->cashbox->toPay,
                'wholesaleSum'  =>  \Yii::$app->cashbox->wholesaleSum,
                'priceType'     =>  \Yii::$app->cashbox->priceType,
            ];
        }

        return false;
    }

    public function actionIndex(){
        if(!empty(\Yii::$app->cashbox->order)){
            $order = \Yii::$app->cashbox->order;
        }else{
            $order = new CashboxOrder();
        }

        $customer = \Yii::$app->cashbox->customer;

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
            'query'     =>  \Yii::$app->cashbox->itemsQuery(),
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
            'manager'           =>  Siteuser::getActiveUsers()[\Yii::$app->cashbox->responsibleUser]
        ]);
    }

    public function actionChangecustomer(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        \Yii::$app->response->format = 'json';

        \Yii::$app->cashbox->changeCustomer(\Yii::$app->request->post("customerID"));

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
        $dataProvider = new ActiveDataProvider([
            'query'     =>  CashboxOrder::find()->where(['postpone' => 1]),
            'sort'      =>  [
                'defaultOrder'  =>  ['createdTime' =>  SORT_DESC]
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

        return $this->render('checks', [
            'checksItems'   =>  $dataProvider,
            'customers'     =>  $customers
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
            'query'     =>  $orders->andWhere(['return' => 0]),
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

        \Yii::$app->cashbox->refund();

        return \Yii::$app->cashbox->order->id;
    }

    public function actionPostponecheck(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        if(!\Yii::$app->cashbox->postpone()){
            throw new ErrorException("Произошла ошибка при выполнении метода actionPostponeCheck");
        }

        return \Yii::$app->cashbox->order->id;
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

        \Yii::$app->cashbox->put($good->ID);

        return [
            'toPay'         =>  \Yii::$app->cashbox->toPay,
            'sum'           =>  \Yii::$app->cashbox->sum,
            'itemsCount'    =>  \Yii::$app->cashbox->itemsCount,
            'wholesaleSum'  =>  \Yii::$app->cashbox->wholesaleSum,
            'priceType'     =>  \Yii::$app->cashbox->priceType
        ];
    }

    public function actionRemoveitem(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        \Yii::$app->response->format = 'json';

        $itemID = \Yii::$app->request->post("itemID");

        if($itemID != 'all' && !isset(\Yii::$app->cashbox->items[$itemID])){
            throw new NotFoundHttpException("Такой товар не найден!");
        }

        if(\Yii::$app->cashbox->itemsCount > 0){
            if($itemID == 'all'){
                foreach(\Yii::$app->cashbox->items as $item){
                    \Yii::$app->cashbox->remove($item->itemID);
                }
            }else{
                \Yii::$app->cashbox->remove($itemID);

                return [
                    'itemsCount'    =>  \Yii::$app->cashbox->itemsCount,
                    'sum'           =>  \Yii::$app->cashbox->sum,
                    'toPay'         =>  \Yii::$app->cashbox->toPay,
                    'wholesaleSum'  =>  \Yii::$app->cashbox->wholesaleSum,
                    'priceType'     =>  \Yii::$app->cashbox->priceType,
                ];
            }
        }

        return true;
    }
}
