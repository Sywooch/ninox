<?php

namespace backend\modules\payments\controllers;

use backend\models\History;
use backend\models\SendedPayment;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `payments` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
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

            $id = \Yii::$app->request->post("id");

            $model = SendedPayment::findOne(['id' => $id]);

            if(empty($model)){
                throw new NotFoundHttpException("Заказ с идентификатором {$id} не найден!");
            }

            switch(\Yii::$app->request->post("action")){
                case "delete":
                    $model->delete();
                    return true;
                    break;
                case "confirm":
                    if(!empty($model->order)){
                        $model->order->moneyConfirmed = 1;

                        if($model->order->save(false)){
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

    public function actionControl(){

    }
}
