<?php

namespace backend\modules\charts\controllers;

use backend\controllers\SiteController as Controller;
use backend\models\Good;
use backend\models\History;
use backend\models\Shop;
use backend\modules\charts\models\CashboxMonthReport;
use backend\modules\charts\models\HistorySearch;
use backend\modules\charts\models\MonthReport;
use common\models\CashboxMoney;
use yii\data\ActiveDataProvider;

class DefaultController extends Controller
{
    
    public function beforeAction($action)
    {
        if($action->id != 'index'){
            $this->getView()->params['breadcrumbs'][] = [
                'url'   =>  '/charts',
                'label' =>  'Отчёты'
            ];
        }
        
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    public function actionCashbox(){

        return $this->render('cashbox', [
            'report'    =>  new CashboxMonthReport()
        ]);
    }

    public function actionIndex()
    {
        if(\Yii::$app->request->post()){
            \Yii::$app->response->format = 'json';
            return \Yii::$app->request->post();
        }

        /*$p = null;
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
        $a = History::getPaymentStats($p);*/

        return $this->render('stats', [
            'possibleReports'   =>  [
                [
                    'url'   =>  '/charts/shop',
                    'label' =>  'Продажи магазина'
                ],
            ]
        ]);
    }

    public function actionShop(){
        $shop = \Yii::$app->params['configuration'];

        $shop->month = empty(\Yii::$app->request->get('month')) ? date('m') : \Yii::$app->request->get('month');
        $shop->year = empty(\Yii::$app->request->get('year')) ? date('Y') : \Yii::$app->request->get('year');

        $report = new MonthReport();

        $report->shop = $shop;

        $orders = new HistorySearch();

        return $this->render('shop',
            [
                'shop'          =>  $shop,
                'report'        =>  $report,
                'orders'        =>  $orders->search(\Yii::$app->request->get()),
                'historySearch' =>  $orders
            ]
        );
    }

    public function actionGoods(){
        $mod = \Yii::$app->request->get("mod");
        $mod = empty($mod) ? '' : $mod;

        $lang = \Yii::$app->language;

        return $this->render('goods', [
            'goodsDataProvider' =>  new ActiveDataProvider([
                'query' =>  Good::find()
                    ->joinWith('translations')
                    ->andWhere("`item_translations`.`enabled` = '0'")
                    ->andWhere("`item_translations`.`language` = '{$lang}'")
                    ->andWhere(['deleted' => '0', 'disableConfirmed' => '0']),
                'sort' =>  [
                    'defaultOrder'   =>  [
                        'otkl_time' =>  SORT_DESC
                    ]
                ],
                'pagination'    =>  [
                    'pageSize'  =>  25
                ]

            ])
        ]);
    }
}
