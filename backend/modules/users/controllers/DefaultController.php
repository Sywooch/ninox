<?php

namespace backend\modules\users\controllers;

use common\models\ControllerAction;
use common\models\Siteuser;
use backend\models\User;
use yii\data\ActiveDataProvider;
use backend\controllers\SiteController as Controller;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "siteusers".
 *
 * @property integer $id
 * @property string $username
 * @property string $name
 * @property string $password
 * @property integer $active
 * @property integer $showInStat
 * @property string $lastLoginIP
 * @property string $lastActivity
 * @property string $auth_key
 */

class DefaultController extends Controller
{
    public function beforeAction($action){

        if(\Yii::$app->user->identity->superAdmin == 1){
            $controller = \common\models\Controller::findOne(['controller'  =>  \Yii::$app->controller->className()]);

            if(empty($controller)){
                $controller = new \common\models\Controller;
            }

            \Yii::$app->params['moduleConfiguration'] = $this->renderPartial('_moduleConfiguration', [
                'dataProvider'  =>  new ActiveDataProvider([
                    'query' =>  ControllerAction::find()->where(['controllerID'  =>  $controller->id])
                ]),
                'controller'    =>  $controller,
                'action'        =>  new ControllerAction()
            ]);
        }

        return parent::beforeAction($action);
    }

    public function actionIndex(){
        if(\Yii::$app->request->post()){
            $p = \Yii::$app->request->post();

            if($p['Siteuser']['id'] != ''){
                $m = Siteuser::findOne($p['Siteuser']['id']);
            }else{
                $m = new Siteuser();
            }

            $m->load($p);
            $m->save();
        }

        return $this->render('index', [
            'dataProvider'  =>  new ActiveDataProvider([
                'query' =>  Siteuser::find()->where('id > 0')->orderBy('id'),
                'pagination'    =>  [
                    'pageSize'  =>  50
                ]
            ])
        ]);
    }

    public function actionShowuser($param){
        $user = User::findOne($param);

        if(!$user){
            throw new NotFoundHttpException("Пользователь не найден!");
        }

        return $this->render('user', [
            'user'  =>  $user
        ]);
    }

    public function actionPrivacy($param){

    }
}
