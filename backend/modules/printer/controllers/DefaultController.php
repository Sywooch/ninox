<?php

namespace backend\modules\printer\controllers;

use backend\controllers\SiteController as Controller;
use backend\models\Customer;
use backend\models\Good;
use backend\models\History;
use backend\models\HistorySearch;
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

        $this->layout = 'print';

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
            'query'         =>  SborkaItem::find()->where(['AND', ['orderID' => $order->id, 'nezakaz' => 0]]),
            'pagination'    =>  [
                'pageSize'  =>  0
            ]
        ]);

        $sborkaItems->setSort([
            'defaultOrder'  =>  [
                'added' =>  SORT_ASC
            ]
        ]);

        $itemIDs = $goods = [];

        foreach($sborkaItems->getModels() as $item){
            $itemIDs[] = $item->itemID;
        }

        foreach(Good::find()->where(['in', 'ID', $itemIDs])->each() as $good){
            $goods[$good->ID] = $good;
        }

        return $this->renderAjax('invoice', [
            'order'         =>  $order,
            'goods'         =>  $goods,
            'orderItems'    =>  $sborkaItems,
            'customer'      =>  $order->customer,
            'act'           =>  'printOrder'
        ]);
    }

    public function actionDeliveryList(){
        $orders = (new HistorySearch())->search(\Yii::$app->request->get(), true);

        return $this->render('deliveryList', [
           'orders'         =>  new ActiveDataProvider([
               'query'  =>  $orders->andWhere("`responsibleUserID` != '59'")    //Убрал "Женю"
           ])
        ]);
    }

    /**
     * @param integer $param - ID товара
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionGoodBarcode($param){
        $good = Good::findOne($param);

        if(!$good){
            throw new NotFoundHttpException("Товар с идентификатором {$param} не найден!");
        }

        return $this->render('good/barcode', [
            'good'  =>  $good
        ]);
    }

    public function actionTransport_list($param){
        $order = History::findOne($param);

        if(!$order){
            throw new NotFoundHttpException("Заказ с идентификатором {$param} не найден!");
        }

        return $this->render('_last_page', [
            'order' =>  $order
        ]);
    }

    public function actionOrder($param){
        $order = History::findOne($param);

        if(!$order){
            throw new NotFoundHttpException("Заказ с идентификатором {$param} не найден!");
        }

        return $this->render('order', [
            'order' =>  $order
        ]);
    }

    public function actionRefund($param){

    }
}
