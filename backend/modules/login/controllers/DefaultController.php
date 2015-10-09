<?php

namespace backend\modules\login\controllers;

use common\models\LoginForm;
use backend\controllers\SiteController as Controller;

class DefaultController extends Controller
{
    public $defaultAction = 'login';

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin(){
        if(\Yii::$app->request->isAjax){
            return \Yii::$app->user->isGuest ? '1' : '0';
        }

        if(!\Yii::$app->user->isGuest){
            return $this->redirect('/admin'.\Yii::$app->user->identity->default_route);
        }

        $model = new LoginForm();

        if ($model->load(\Yii::$app->request->post()) && $model->login()) {
            if(\Yii::$app->request->url == '/admin'){
                return $this->redirect('/admin'.\Yii::$app->user->identity->default_route);
            }else{
                return $this->redirect(\Yii::$app->request->url);
            }
        }else{
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }
}
