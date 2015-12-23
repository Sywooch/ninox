<?php

namespace backend\modules\store\controllers;

use common\models\Shop;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

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
}
