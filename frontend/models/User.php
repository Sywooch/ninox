<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 15.10.15
 * Time: 15:02
 */

namespace frontend\models;


use yii\web\IdentityInterface;

class User extends Customer implements IdentityInterface{

	private $_pricerules = [];
	private $_wishlist = [];

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

    public function hasInWishlist($id){
        if(empty($this->_wishlist)){
            $this->_wishlist = array_column(CustomerWishlist::find()->where(['customerID' => $this->ID])->asArray()->all(), 'itemID');
        }
        return in_array($id, $this->_wishlist);
    }

    /**
     * Метод считает и возвращает сумму оплаченных заказов в этом месяце
     * @return int
     */
    public function getMonthOrdersSum(){
        $sum = 0;
        date_default_timezone_set('UTC');
        $date = strtotime(date('Y-m-1'));
        foreach($this->orders as $order){
            if($order->added >= $date && $order->moneyConfirmed){
                $sum += $order->actualAmount;
            }
        }

        return $sum;
    }

}