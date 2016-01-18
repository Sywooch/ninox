<?php

namespace backend\modules\store\controllers;

use common\models\Cashbox;
use common\models\Shop;
use yii\data\ActiveDataProvider;
use backend\controllers\SiteController as Controller;
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

        $params = [];

        if($shop->type != $shop::TYPE_WAREHOUSE){
            $params['cashboxesDataProvider'] = new ActiveDataProvider([
                'query' =>  Cashbox::find()->where(['store' => $shop->id])
            ]);
        }

        return $this->render('shop', array_merge([
            'model' =>  $shop,
        ], $params));
    }
}
