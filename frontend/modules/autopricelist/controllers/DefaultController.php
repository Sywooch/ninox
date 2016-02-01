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

        switch($priceList->format){
            case PriceListFeed::FORMAT_XML:
                //$this->layout = 'xml';
                $this->layout = 'yml';
                break;
            case PriceListFeed::FORMAT_YML:
            default:
                $this->layout = 'yml';
                break;
        }

        $categories = [];

        foreach(Category::find()->where(['in', 'ID', $priceList->categories])->each() as $category){
            $categories[$category->ID] = $category;
            $categoriesByCodes[$category->Code] = $category;
        }

        $items = Good::find()->where(['in', 'GroupID', $priceList->categories]);

        if(isset($priceList->options['unlimited']) && !$priceList->options['unlimited']){
            $items->andWhere(['isUnlimited' => 0]);
        }

        if(isset($priceList->options['deleted']) && !$priceList->options['deleted']){
            $items->andWhere(['Deleted' =>  0]);
        }

        if(isset($priceList->options['available']) && $priceList->options['available']){
            $items->andWhere(['show_img' =>  1])->andWhere('count > 0');
        }

        $items = $items->all();

        return $this->render('index', [
            'categories'    =>  $categories,
            'categoriesByCodes'=>  $categoriesByCodes,
            'items'         =>  $items,
            'shop'          =>  new Object()
        ]);
    }
}
