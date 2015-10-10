<?php

namespace backend\modules\admin\controllers;


use common\models\Customer;
use common\models\Good;
use common\models\LoginForm;
use common\models\Service;
use sammaye\audittrail\AuditTrail;
use backend\controllers\SiteController as Controller;
use yii\web\User;

class DefaultController extends Controller
{

    public function beforeAction($action){
        if(\Yii::$app->user->isGuest){
            \Yii::$app->setModule('app\\modules\\login\\Module', 'login');
            try{
                $m = \Yii::$app->getModule('login');
                return $m->runAction('default/login');
            }catch(\ReflectionException $r){
                if(\Yii::$app->request->isAjax){
                    return false;
                }else{
                    return $this->run('site/error');
                }
            }
        }

        \Yii::$app->user->identity->lastActivity = date('Y-m-d H:i:s');
        \Yii::$app->user->identity->save();

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        return $this->actionRoute('orders');
    }

    public function actionUpdatecurrency(){
        if(!\Yii::$app->request->isAjax){
            return;
        }

        \Yii::$app->response->format = 'json';

        $post = \Yii::$app->request->post("Service");

        $m = Service::findOne(['key' => $post['key']]);

        $m->load(\Yii::$app->request->post("Service"));

        $m->value = \Yii::$app->request->post("Service[value]");

        $m->save();

        return \Yii::$app->request->post();
    }

    public function actionRevertchanges(){
        if(\Yii::$app->request->isAjax){
            $m = AuditTrail::findOne(['id' => \Yii::$app->request->post("itemid")]);

            if(!$m){
                return $this->runAction('error');
            }

            switch($m->model){
                case 'common\models\Good':
                    $model = Good::findOne(['id' => $m->model_id]);
                    break;
                case 'common\models\Customer':
                    $model = Customer::findOne(['id' => $m->model_id]);
                    break;
            }

            $field = $m->field;

            $model->$field = $m->old_value;
            $model->save(false);
        }else{
            return $this->runAction('error');
        }
    }

    public function actionLogout()
    {
        \Yii::$app->user->logout();

        return $this->redirect('/admin');
    }

    public function actionRoute($action, $param1 = "", $param2 = ""){
        $a = 'backend\modules\\'.$action.'\Module';
        $params = ['p1' => $param1, 'p2' => $param2];

        \Yii::$app->setModule($action, $a);
        try{
            $m = \Yii::$app->getModule($action);
            $m->setLayoutPath(\Yii::$app->getModule('admin')->getLayoutPath());
            return $m->runAction($m->defaultRoute, $params);
        }catch(\ReflectionException $r){

        }
        return $this->runAction($this->defaultAction);
    }

    public function actionError($p1 = "404", $p2 = "Страница не найдена!"){
        $this->render("error", [
            'title'     =>  $p1,
            'message'   =>  $p2
        ]);
    }
}
