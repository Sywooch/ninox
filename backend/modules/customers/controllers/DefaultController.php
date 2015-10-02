<?php

namespace app\modules\customers\controllers;

use app\models\Customer;
use app\models\CustomerSearch;
use app\models\History;
use sammaye\audittrail\AuditTrail;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class DefaultController extends Controller
{

    public function actionIndex($p1 = "", $p2 = "")
    {
        if($p1 != '' || $p2 != ''){
            if($p2 != ""){
                return $this->runAction($p1, [
                    'param' =>  $p2
                ]);
            }else{
                return $this->runAction($p1);
            }
        }else{
            if(\Yii::$app->request->isAjax && !empty(\Yii::$app->request->post("hasEditable"))){
                $m = Customer::findOne(['ID' => \Yii::$app->request->post("editableKey")]);

                if($m){
                    foreach(current(\Yii::$app->request->post("Customer")) as $k => $c){
                        $m->$k = $c;
                    }
                    return $m->save(false);
                }
                return '1';
            }

            $dp = Customer::find();
            $dp->orderBy('ID DESC');

            $customerSearch = new CustomerSearch();

            return $this->render('index', [
                'dataProvider'  =>  $customerSearch->search(\Yii::$app->request->get()),
                'searchModel'   =>  $customerSearch
            ]);
        }
    }

    public function actionShowcustomer($param){
        $customer = Customer::findOne(['ID' => $param]);
        $edit = \Yii::$app->request->get("act") == 'edit';

        if(!$customer){
            return $this->render('/../../admin/views/default/error.php', [
                'name'  =>  'Клиента не существует',
                'message'   => 'Такого клиента нет на сайте! Вы можете <a onclick="window.history.back();">вернуться обратно</a>, или попробовать ещё раз'
            ]);
        }

        if($edit && !empty(\Yii::$app->request->post("Customer"))){
            $customer->attributes = \Yii::$app->request->post("Customer");
            $customer->save();
        }

        return $this->render('_showcustomer_template', [
            'customer'      =>  $customer,
            'editMode'      =>  $edit,
            'ordersStats'   =>  $customer->getOrdersStats(),
            'orders'        =>  new ActiveDataProvider([
                'query' =>  History::find()->where(['customerID' => $customer->ID]),
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]),
            'lastOrder'     =>  History::find()->where(['customerID' => $customer->ID, 'confirmed' => 1])->orderby('ID desc')->one()
        ]);
    }
}
