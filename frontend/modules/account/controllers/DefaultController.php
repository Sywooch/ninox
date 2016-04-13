<?php

namespace frontend\modules\account\controllers;

use frontend\models\History;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;

class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'roles' =>  ['?'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionBetterlistclosed(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Этот запрос возможен только через Ajax!");
        }

        \Yii::$app->user->identity->giveFeedbackClosed = date('Y-m-d H:i:s');
        \Yii::$app->user->identity->save(false);
    }

    public function actionOrders()
    {
        $ordersDataProvider = new ActiveDataProvider([
            'query' => History::find()->where(['customerID' => \Yii::$app->user->identity->ID]),
            'sort'  =>  [
                'defaultOrder'  =>  [
                    'id'    =>  SORT_DESC
                ]
            ]
        ]);

        return $this->render('orders', [
            'ordersDataProvider'    =>  $ordersDataProvider
        ]);
    }
}
