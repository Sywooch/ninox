<?php

namespace frontend\modules\account\controllers;

use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;

class DefaultController extends Controller
{
    public function beforeAction($action){
        if(\Yii::$app->user->isGuest){
            \Yii::$app->response->redirect('/login');
        }

        return parent::beforeAction($action);
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
        return $this->render('orders');
    }
}
