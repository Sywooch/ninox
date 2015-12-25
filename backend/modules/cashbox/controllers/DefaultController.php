<?php

namespace backend\modules\cashbox\controllers;

use backend\models\CashboxItem;
use backend\models\CashboxOrder;
use backend\models\Good;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;

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

    public function actionChangecashboxtype(){
        if(!\Yii::$app->request->isAjax){
            throw new BadRequestHttpException("Этот метод доступен только через ajax!");
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
            }
        }

        //Тут также можно будет добавить логику изменения цены в заказе

        return $priceType;
    }

    public function actionRemoveitem(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        $itemID = \Yii::$app->request->post("itemID");

        $orderItem = CashboxItem::findOne(['orderID' =>  \Yii::$app->request->cookies->getValue("cashboxOrderID"), 'itemID' => $itemID]);

        if(!$orderItem){
            throw new NotFoundHttpException("Такой товар не найден!");
        }

        return $orderItem->delete();
    }

    public function actionIndex(){
        if(\Yii::$app->request->post("")){

        }

        $orderItems = [];

        foreach(CashboxItem::find()->select("itemID")->where(['orderID' =>\Yii::$app->request->cookies->getValue('cashboxOrderID')])->asArray()->all() as $item){
            $orderItems[] = $item['itemID'];
        }

        return $this->render('index', [
            'orderItems'    =>  new ActiveDataProvider([
                'query'     =>  Good::find()->where(['in', 'ID', $orderItems]),
                'pagination'    =>  [
                    'pageSize'  =>  0
                ]
            ])
        ]);
    }

    public function actionChecks(){
        return $this->render('checks', [
            'checksItems'   =>  new ActiveDataProvider([
                'query'     =>  CashboxOrder::find()->where('doneTime != NULL')
            ])
        ]);
    }

    public function actionSales(){
        return $this->render('sales', [
            'salesProvider' =>  new ActiveDataProvider([
                'query'     =>  CashboxOrder::find()
            ])
        ]);
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
            //$cashboxOrder->customerID =
            //$cashboxOrder->responsibleUser =
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
            $orderItem->name = $good->Name;
            $orderItem->originalPrice = $cashboxOrder->priceType == 1 ? $good->PriceOut1 : $good->PriceOut2;
        }else{
            $orderItem->count += 1;
        }

        $return = [
            'type'  =>  $orderItem->isNewRecord ? 'add' : 'update',
            'data'  =>  $this->renderAjax('_orderItem', [
                'model' =>  $good
            ])
        ];

        if($orderItem->save(false)){
            return $return;
        }
    }
}
