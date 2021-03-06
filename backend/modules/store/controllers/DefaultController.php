<?php

namespace backend\modules\store\controllers;

use backend\models\CashboxForm;
use common\models\Cashbox;
use common\models\Shop;
use yii\data\ActiveDataProvider;
use backend\controllers\SiteController as Controller;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index', [
            'shops' =>  new ActiveDataProvider([
                'query' =>  Shop::find()
            ])
        ]);
    }

    public function actionShow($param){
        $shop = Shop::findOne($param);

        if(!$shop){
            throw new NotFoundHttpException("Склад или магазин с таким ID не найден!");
        }

        if(\Yii::$app->request->post("CashboxForm")){
            $cashboxForm = new CashboxForm();
            $cashboxForm->load(\Yii::$app->request->post());
            $cashboxForm->store = $shop->id;

            $cashboxForm->save();
        }

        $params = [];

        if($shop->type != $shop::TYPE_WAREHOUSE){
            $params['cashboxesDataProvider'] = new ActiveDataProvider([
                'query' =>  Cashbox::find()->where(['store' => $shop->id])
            ]);
        }

        $params['model'] = $shop;

        return $this->render('shop', $params);
    }
}
