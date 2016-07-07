<?php

namespace backend\modules\charts\controllers;

use backend\controllers\SiteController as Controller;
use backend\models\Good;
use backend\models\History;
use backend\models\Shop;
use backend\modules\charts\models\CashboxMonthReport;
use backend\modules\charts\models\CashboxStat;
use backend\modules\charts\models\HistorySearch;
use backend\modules\charts\models\MonthReport;
use common\models\Cashbox;
use common\models\CashboxMoney;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

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
        $report = new CashboxMonthReport();

        if(\Yii::$app->request->isAjax){
            switch(\Yii::$app->request->post('action')){
                case 'addMoney':
                    $operation = new CashboxMoney([
                        'cashbox'   =>  \Yii::$app->params['configuration']->defaultCashboxID,
                        'operation' =>   CashboxMoney::OPERATION_PUT,
                        'amount'    =>  \Yii::$app->request->post('value')
                    ]);

                    $operation->save(false);
                    break;
                case 'tookMoney':
                    $operation = new CashboxMoney([
                        'cashbox'   =>  \Yii::$app->params['configuration']->defaultCashboxID,
                        'operation' =>   CashboxMoney::OPERATION_TAKE,
                        'amount'    =>  \Yii::$app->request->post('value'),
                    ]);

                    $operation->save(false);
                    break;
                case 'getDetailed':
                    $day = \Yii::$app->request->post('value');

                    return $this->renderAjax('cashbox/detailView', [
                        'dataProvider'  =>  new ActiveDataProvider([
                            'query' =>  CashboxMoney::find()->where(['like', 'date', $day.'%', false])->andWhere(['in', 'cashbox', ArrayHelper::getColumn(\Yii::$app->params['configuration']->possibleCashboxes, 'ID')]),
                            'sort'      =>  [
                                'attributes' =>  ['date', 'responsibleUser'],
                                'defaultOrder'  =>  [
                                    'date'  =>  SORT_DESC
                                ]
                            ],
                            'pagination'    =>  [
                                'pageSize'  =>  0
                            ]
                        ]),
                        'day'           =>  $day
                    ]);
                    break;
            }

        }

        $lastMoneyTake = CashboxMoney::find()->where(['operation' => CashboxMoney::OPERATION_TAKE])->andWhere(['in', 'cashbox', ArrayHelper::getColumn(\Yii::$app->params['configuration']->possibleCashboxes, 'ID')])->orderBy('date DESC')->one();

        if(empty($lastMoneyTake)){
            $lastMoneyTake = new CashboxMoney([
                'operation' =>  CashboxMoney::OPERATION_TAKE
            ]);
        }

        return $this->render('cashbox', [
            'report'        =>  $report,
            'lastMoneyTake' =>  $lastMoneyTake,
            'stats'         =>  new CashboxStat(['date' => $lastMoneyTake->date])
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
