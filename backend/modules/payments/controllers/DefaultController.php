<?php

namespace backend\modules\payments\controllers;

use backend\models\History;
use backend\models\SendedPayment;
use common\models\MoneyExchange;
use yii\base\InvalidParamException;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use backend\controllers\SiteController as Controller;

/**
 * Default controller for the `payments` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     * @throws \yii\db\StaleObjectException
     * @throws \yii\base\InvalidParamException
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' =>  SendedPayment::find()->andWhere(['read_confirm' => 0]),
            'sort'  =>  [
                'defaultOrder'  =>  [
                    'id'  =>  SORT_DESC
                ]
            ]
        ]);

        if(\Yii::$app->request->isAjax){
            \Yii::$app->response->format = 'json';

            $id = \Yii::$app->request->post('id');

            $model = SendedPayment::findOne(['id' => $id]);

            if(empty($model)){
                throw new NotFoundHttpException("Заказ с идентификатором {$id} не найден!");
            }

            switch(\Yii::$app->request->post('action')){
                case 'delete':
                    $model->delete();
                    return true;
                    break;
                case 'confirm':
                    if(!empty($model->order)){
                        $order = $model->order;
                        $order->moneyConfirmed = 1;

                        if($order->save(false)){
                            $model->read_confirm = 1;
                            $model->save(false);

                            return true;
                        }

                        return false;
                    }

                    return false;
                    break;
            }
        }

        return $this->render('index', [
            'dataProvider'  =>  $dataProvider
        ]);
    }

    public function actionUpdateExchange(){
        if(!\Yii::$app->request->isAjax){
            throw new BadRequestHttpException('Данный метод доступен только через ajax!');
        }

        $exchange = MoneyExchange::findOne(['date' => \Yii::$app->request->get('date')]);

        if(!$exchange){
            $exchange = new MoneyExchange([
                'date'  =>  \Yii::$app->request->get('date')
            ]);
        }

        $exchange->load(\Yii::$app->request->post());

        \Yii::$app->response->format = 'json';


        if(!$exchange->save()){
            return $exchange->getErrors();
        }

        return $exchange;
    }

    public function actionConfirm(){
        if(!\Yii::$app->request->isAjax){
            throw new BadRequestHttpException('Этот метод доступен только через ajax!');
        }

        $order = History::findOne(['id' => \Yii::$app->request->post('id')]);

        \Yii::$app->response->format = 'json';

        if(!$order){
            throw new NotFoundHttpException('Заказ не найден!');
        }

        switch(\Yii::$app->request->post('action')){
            case 'confirm':
                $order->moneyConfirmed = '1';
                break;
        }

        return $order->save(false);
    }

    public function actionConfirmAll(){
        if(!\Yii::$app->request->isAjax){
            throw new BadRequestHttpException('Этот метод доступен только через ajax!');
        }

        $date = \Yii::$app->request->post('date');

        if(empty($date)){
            throw new InvalidParamException('Дата кривовата');
        }

        $orders = History::find()
            ->andWhere("FROM_UNIXTIME(`added`, '%Y-%m-%d') = '{$date}'")
            ->andWhere(['orderSource'   =>  \Yii::$app->params['configuration']->id])
            ->andWhere(['sourceType' => History::SOURCETYPE_SHOP]);

        $return = false;

        foreach($orders->each() as $order){
            $order->moneyConfirmed = 1;
            $return = $order->save(false);
        }

        return $return;
    }

    /**
     * @param mixed $param
     * @return string
     * @throws \yii\base\InvalidParamException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionControl($param = null){
        if(!empty($param)){
            $param = \Yii::$app->formatter->asDate($param, 'php:Y-m-d');

            $query = History::find()->with('moneyCollector');

            switch(\Yii::$app->request->get('type')){
                case 'shop':
                    $query->andWhere("FROM_UNIXTIME(`added`, '%Y-%m-%d') = '{$param}'")
                        ->andWhere(['orderSource'   =>  \Yii::$app->params['configuration']->id])
                        ->andWhere(['sourceType' => History::SOURCETYPE_SHOP]);
                    break;
                case 'selfDelivered':
                    $query->andWhere("STR_TO_DATE(`moneyConfirmedDate`, '%Y-%m-%d') = '{$param}'")
                        ->andWhere(['deliveryType' => '3', 'sourceType' => History::SOURCETYPE_INTERNET]);
                    break;
                default:
                    //$query->andWhere(['or', ])
                    break;
            }

            $dataProvider = new ActiveDataProvider([
                'query' =>  $query,
                'sort'  =>  [
                    'defaultOrder'  =>  [
                        'number'    =>  SORT_DESC
                    ]
                ]
            ]);

            $unconfirmed = 0;

            foreach($dataProvider->getModels() as $model){
                $model->moneyConfirmed != 1 ? $unconfirmed++ : null;
            }

            return $this->render('viewDay',
                [
                    'param'         =>  $param,
                    'unconfirmed'   =>  $unconfirmed,
                    'dataProvider'  =>  $dataProvider
                ]);
        }

        return $this->render('control');
    }
}
