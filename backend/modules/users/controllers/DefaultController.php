<?php

namespace app\modules\users\controllers;

use app\models\Siteuser;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex($p1 = '', $p2 = '')
    {
        if($p1 == "" && $p2 == ""){
            return $this->runAction('userslist');
        }else{
            if($p2 != ""){
                return $this->runAction($p1, [
                    'param' =>  $p2
                ]);
            }else{
                return $this->runAction($p1);
            }
        }
    }

    public function actionUserslist(){
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
            return '';
        }

        return $this->render('user', [
            'user'  =>  $user
        ]);
    }
}
