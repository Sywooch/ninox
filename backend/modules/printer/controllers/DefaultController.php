<?php

namespace backend\modules\printer\controllers;

use backend\controllers\SiteController as Controller;
use backend\models\Customer;
use backend\models\Good;
use backend\models\History;
use backend\models\SborkaItem;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `printer` module
 */
class DefaultController extends Controller
{

    public function beforeAction($action){
        if(\Yii::$app->request->get("secret") == "secretKeyForPrinter"){
            return true;
        }

        return parent::beforeAction($action);
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionInvoice($param){
        $order = History::findOne(['id' => $param]);

        if(!$order){
            throw new NotFoundHttpException("Такого заказа не существует!");
        }

        $sborkaItems = new ActiveDataProvider([
            'query'         =>  SborkaItem::find()->where(['orderID' => $order->id]),
            'pagination'    =>  [
                'pageSize'  =>  0
            ]

        ]);

        $itemIDs = $goods = [];

        foreach($sborkaItems->getModels() as $item){
            $itemIDs[] = $item->itemID;
        }

        foreach(Good::find()->where(['in', 'ID', $itemIDs])->each() as $good){
            $goods[$good->ID] = $good;
        }

        $customer = Customer::findOne($order->customerID);

        if(!$customer){
            $customer = new Customer();
        }

        return $this->renderAjax('invoice', [
            'order'         =>  $order,
            'goods'         =>  $goods,
            'orderItems'    =>  $sborkaItems,
            'customer'      =>  $customer,
            'act'           =>  'printOrder'
        ]);
    }

    public function actionOrder($param){

    }

    public function actionRefund($param){

    }
}
