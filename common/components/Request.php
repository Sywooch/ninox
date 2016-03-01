<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 03.02.16
 * Time: 13:04
 */

namespace common\components;


class Request extends \yii\web\Request{

    public function getUserIP(){
        return empty($_SERVER["HTTP_CF_CONNECTING_IP"]) ? empty($_SERVER['HTTP_X_REAL_IP']) ? parent::getUserIP() : $_SERVER['HTTP_X_REAL_IP'] : $_SERVER["HTTP_CF_CONNECTING_IP"];
    }

}