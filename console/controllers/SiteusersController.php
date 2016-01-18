<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 29.12.15
 * Time: 13:51
 */

namespace console\controllers;


use backend\models\User;
use yii\console\Controller;

class SiteusersController extends Controller{

    public function actionUpdatepasswords(){
        $users = User::findAll(['changePassword' => 1]);

        echo 'Now date is '.date('d.m H:i:s')."\n";

        foreach($users as $user){
            $password = \Yii::$app->security->generateRandomString(6);

            echo $user->name.' (id = '.$user->id.') have password: '.$password."\n";

            $user->setPassword($password);
            $user->save();
        }
    }

}