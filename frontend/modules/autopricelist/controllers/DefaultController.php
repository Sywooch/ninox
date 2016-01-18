<?php

namespace frontend\modules\autopricelist\controllers;

use common\models\PriceListFeed;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `autopricelist` module
 */
class DefaultController extends Controller
{

    public function actionIndex($id)
    {
        $priceList = PriceListFeed::findOne($id);

        if(!$priceList){
            throw new NotFoundHttpException("Страница не найдена!");
        }

        return $this->render('index');
    }
}
