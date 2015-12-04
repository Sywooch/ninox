<?php

namespace frontend\modules\account\controllers;

use yii\web\Controller;

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
}
