<?php

namespace backend\modules\carts\controllers;

use common\models\Cart;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use backend\controllers\SiteController as Controller;

/**
 * Default controller for the `carts` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'dataProvider'  =>  new ActiveDataProvider([
                'query'     =>  Cart::find()->distinct("cartCode")->groupBy('cartCode'),
                'sort'      =>  [
                    'defaultOrder'  =>  [
                        'date'  =>  SORT_DESC
                    ]
                ]
            ])
        ]);
    }

    public function actionView($param){
        if(empty($param)){
            throw new NotFoundHttpException("При пустом идентификаторе корзины нельзя найти корзину!");
        }

        $cart = Cart::findOne(['cartCode' => $param]);

        if(!$cart){
            throw new NotFoundHttpException("Корзина с идентификатором {$param} не найдена!");
        }

        return $this->render('cart', [
            'cart'          =>  $cart,
            'dataProvider'  =>  new ActiveDataProvider([
                'query' =>  Cart::find()->where(['cartCode' => $param])->with('good'),
                'sort'  =>  [
                    'defaultOrder' =>   [
                        'date'  =>  SORT_DESC
                    ]
                ]
            ])
        ]);
    }
}
