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

    public static function isPasswordResetTokenValid($token){
        return strlen($token) == 32;
    }

    public static function findIdentityByAccessToken($token, $type = null){
        return static::findOne(['auth_key' => $token]);
    }

    public static function findByPasswordResetToken($token){
        return static::findOne(['password_reset_token' => $token]);
    }

    public function removePasswordResetToken(){
        $this->password_reset_token = null;
    }

    public function generatePasswordResetToken(){
        $this->password_reset_token = \Yii::$app->security->generateRandomString();
    }

    public function getId(){
        return $this->getPrimaryKey();
    }

    public function getAuthKey(){
        return $this->auth_key;
    }

    public function validatePassword($password){
        return hash("sha512", $password, false) == $this->password;
    }

    public function setPassword($password){
        $this->password = hash("sha512", $password, false);
    }

    public function generateAuthKey(){
        $this->auth_key = \Yii::$app->security->generateRandomString();
    }

    public function validateAuthKey($authKey){
        return $this->getAuthKey() === $authKey;
    }

    public static function findByPhone($phone){
        return static::findOne(['phone' => $phone]);
    }

}