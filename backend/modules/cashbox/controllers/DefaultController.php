<?php

namespace backend\modules\cashbox\controllers;

use backend\models\CashboxItem;
use backend\models\CashboxOrder;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Cookie;

class DefaultController extends Controller
{
    public function beforeAction($action){
        if(!\Yii::$app->request->cookies->has("cashboxPriceType")){
            \Yii::$app->response->cookies->add(new Cookie([
                'name'      =>  'cashboxPriceType',
                'value'     =>  '0'
            ]));
        }

        if(!\Yii::$app->request->cookies->has("cashboxOrderID")){
            $maxID = CashboxOrder::find()->max("id");

            \Yii::$app->response->cookies->add(new Cookie([
                'name'      =>  'cashboxOrderID',
                'value'     =>  $maxID > 0 ? $maxID : 1
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

        //Тут также можно будет добавить логику изменения цены в заказе

        return $priceType;
    }

    public function actionIndex(){
        if(\Yii::$app->request->post("")){

        }

        return $this->render('index', [
            'orderItems'    =>  new ActiveDataProvider([
                'query'     => CashboxItem::find()->where(['orderID' =>  \Yii::$app->request->cookies->getValue('cashboxOrderID')])
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
}
