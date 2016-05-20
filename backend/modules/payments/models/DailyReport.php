<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 19.05.16
 * Time: 18:53
 */

namespace backend\modules\payments\models;


use backend\models\History;
use yii\db\ActiveRecord;

class DailyReport extends ActiveRecord
{

    private $_shopSells = null;
    private $_selfDelivered = null;

    public static function tableName(){
        return History::tableName();
    }

    public static function find(){
        return parent::find()->select("*, DATE_FORMAT(FROM_UNIXTIME(`added`), '%Y-%m-%d') as `date`")->groupBy('date')->orderBy('added DESC');
    }

    public function getDate(){
        return \Yii::$app->formatter->asDate($this->added, 'php:Y-m-d');
    }

    public function getShopSells(){
        if(empty($this->_shopSells)){
            $this->_shopSells = parent::find()
                ->where("DATE_FORMAT(FROM_UNIXTIME(`added`), '%Y-%m-%d') = '{$this->date}'")
                ->andWhere(['sourceType' => History::SOURCETYPE_SHOP])
                ->all();
        }

        return $this->_shopSells;
    }

    public function getSelfDelivered(){
        if(empty($this->_selfDelivered)){
            $this->_selfDelivered = parent::find()
                ->where("STR_TO_DATE(`moneyConfirmedDate`, '%Y-%m-%d') = '{$this->date}'")
                ->andWhere(['deliveryType' => '3', 'sourceType' => History::SOURCETYPE_INTERNET])
                ->all();
        }

        return $this->_selfDelivered;
    }

    public function getSelfDeliveredAccepted(){
        $acceptedSum = 0;

        foreach($this->selfDelivered as $order){
            if($order->moneyConfirmed == 1){
                $acceptedSum += $order->actualAmount;
            }
        }

        return $acceptedSum;
    }

    public function getSelfDeliveredUserAccepted(){
        $acceptedSum = 0;

        foreach($this->selfDelivered as $order){
            if($order->moneyConfirmed == 1 && $order->moneyCollectorUserId == \Yii::$app->user->identity->id){
                $acceptedSum += $order->actualAmount;
            }
        }

        return $acceptedSum;
    }

    public function getSelfDeliveredNotAccepted(){
        $acceptedSum = 0;

        foreach($this->selfDelivered as $order){
            if($order->moneyConfirmed != 1){
                $acceptedSum += $order->actualAmount;
            }
        }

        return $acceptedSum;
    }

    public function getShopAccepted(){
        $acceptedSum = 0;

        foreach($this->shopSells as $order){
            if($order->moneyConfirmed == 1){
                $acceptedSum += $order->actualAmount;
            }
        }

        return $acceptedSum;
    }

    public function getShopUserAccepted(){
        $acceptedSum = 0;

        foreach($this->shopSells as $order){
            if($order->moneyConfirmed == 1 && $order->moneyCollectorUserId == \Yii::$app->user->identity->id){
                $acceptedSum += $order->actualAmount;
            }
        }

        return $acceptedSum;
    }

    public function getShopNotAccepted(){
        $acceptedSum = 0;

        foreach($this->shopSells as $order){
            if($order->moneyConfirmed != 1){
                $acceptedSum += $order->actualAmount;
            }
        }

        return $acceptedSum;
    }

}