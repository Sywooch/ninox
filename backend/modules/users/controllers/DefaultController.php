<?php

namespace backend\modules\users\controllers;

use common\models\Siteuser;
use backend\models\User;
use yii\data\ActiveDataProvider;
use backend\controllers\SiteController as Controller;

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
            return '';
        }

        return $this->render('user', [
            'user'  =>  $user
        ]);
    }
}
