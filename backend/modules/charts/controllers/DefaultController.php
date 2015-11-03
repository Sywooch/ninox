<?php

namespace backend\modules\charts\controllers;

use common\models\History;
use common\models\Users;
use backend\controllers\SiteController as Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        if(\Yii::$app->request->post()){
            \Yii::$app->response->format = 'json';
            return \Yii::$app->request->post();
        }

        $p = null;
        if(\Yii::$app->request->get('minPeriod') || \Yii::$app->request->get('maxPeriod')){
            $p = [];

            if(\Yii::$app->request->get('minPeriod')){
                $p['min'] = \Yii::$app->request->get('minPeriod');
            }

            if(\Yii::$app->request->get('maxPeriod')){
                $p['max'] = \Yii::$app->request->get('maxPeriod');
            }

        }

        $q = History::getShopSiteOrdersCount($p);
        $a = History::getPaymentStats($p);

        return $this->render('index', [
            'orders'    =>  [
                'fromSite'  =>  [
                    'all'   =>  $q['site']
                ],
                'fromShop'  =>  [
                    'all'   =>  $q['shop']
                ],
                'payments'  =>  [
                    'card'  =>  ((isset($a['2']) ? $a['2'] : 0) + (isset($a['3']) ? $a['3'] : 0) + (isset($a['4']) ? $a['4'] : 0) + (isset($a['7']) ? $a['7'] : 0)),
                    'COD'   =>  (isset($a['1']) ? $a['1'] : 0),
                    'shop'  =>  (isset($a['6']) ? $a['6'] : 0),
                    'pickup'=>  (isset($a['5']) ? $a['5'] : 0)
                ],
                'byCategories'  =>  History::getStatsByCategoriesWithCategoryName($p),
                'all'       =>  ($q['site'] + $q['shop'])
            ]
        ]);
    }

    public function actionGoods(){
        $mod = \Yii::$app->request->get("mod");
        $mod = empty($mod) ? '' : $mod;

        return $this->render('goods', [

        ]);
    }
}
