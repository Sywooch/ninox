<?php

namespace frontend\modules\autopricelist\controllers;

use common\models\Category;
use common\models\Good;
use common\models\PriceListFeed;
use yii\base\Object;
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

        \Yii::$app->response->format = 'raw';
        $this->layout = 'yml';

        $categories = [];

        foreach(Category::find()->where(['in', 'ID', $priceList->categories])->each() as $category){
            $categories[$category->ID] = $category;
        }

        return $this->render('index', [
            'categories'    =>  $categories,
            'items'         =>  Good::find()->where(['in', 'GroupID', $priceList->categories])->all(),
            'shop'          =>  new Object()
        ]);
    }
}
