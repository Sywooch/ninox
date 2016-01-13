<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 12.01.16
 * Time: 14:04
 */

namespace cashbox\models;


use common\models\Siteuser;
use yii\web\IdentityInterface;

class User extends Siteuser implements IdentityInterface{

    protected $permissions = [];

    public function afterFind(){
        $this->lastLoginIP = \Yii::$app->request->getUserIP();
        $this->lastActivity = date('Y-m-d H:i:s');

        $this->save();

        return parent::afterFind();
    }

    public static function findIdentity($id){
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null){
        return static::findOne(['auth_key' => $token]);
    }

    public static function findByUsername($username){
        return static::findOne(['username' => $username]);
    }

    public function getId(){
        return $this->getPrimaryKey();
    }

    public function getAuthKey(){
        return $this->auth_key;
    }

    public function validateAuthKey($authKey){
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password){
        return $this->password === hash("sha512", $password, false);
    }

    /*public function can($level){
        if($this->superAdmin == 1){
            return true;
        }

        if(empty($this->permissions)){
            foreach(SiteusersPrivacy::find()->where(['userID' => $this->getId()])->each() as $userPrivacy){
                $this->permissions[$userPrivacy->controller][$userPrivacy->action] = $userPrivacy->level;
            }
        }

        $permissionLevel = 0;

        if(isset($this->permissions[\Yii::$app->controller->className()]) && isset($this->permissions[\Yii::$app->controller->className()][\Yii::$app->controller->action->id])){
            $permissionLevel = $this->permissions[\Yii::$app->controller->className()][\Yii::$app->controller->action->id];
        }

        if(is_array($level)){
            return $level[$permissionLevel];
        }else{
            return $permissionLevel >= $level;
        }
    }*/

}