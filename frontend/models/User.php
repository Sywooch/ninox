<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 15.10.15
 * Time: 15:02
 */

namespace frontend\models;


use yii\web\IdentityInterface;

class User extends Customer implements IdentityInterface {

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

    public function validatePassword($password){
        //Функция валидации пароля
        return true;
    }

    public function validateAuthKey($authKey){
        return $this->getAuthKey() === $authKey;
    }

    public static function findByPhone($phone){
        return static::findOne(['Phone' => $phone]);
    }

}