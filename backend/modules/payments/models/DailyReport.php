<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 19.05.16
 * Time: 18:53
 */

namespace backend\modules\payments\models;


use backend\models\History;
use common\models\MoneyExchange;
use yii\db\ActiveRecord;

/**
 * @property string $date
 * @property History[] selfDelivered
 * @property History[] shopSells
 */
class DailyReport extends ActiveRecord
{

    private $_shopSells = null;
    private $_selfDelivered = null;
    public $findedDate;

    public static function tableName(){
        return History::tableName();
    }

    public static function find(){
        return parent::find()->select("FROM_UNIXTIME(`added`, '%Y-%m-%d') as `findedDate`")->groupBy('findedDate')->orderBy('added DESC');
    }

    public function getMoneyExchange(){
        $exchange = MoneyExchange::findOne(['date' => \Yii::$app->formatter->asDate($this->date, 'php:Y-m-d')]);
        
        if(empty($exchange)){
            $exchange = new MoneyExchange([
                'date'  =>  \Yii::$app->formatter->asDate($this->date, 'php:Y-m-d')
            ]);
        }
        
        return $exchange;
    }

    public function getDate(){
        return $this->findedDate;
    }

    public function getShopSells(){
        if(empty($this->_shopSells)){
            $this->_shopSells = parent::find()
                ->where("FROM_UNIXTIME(`added`, '%Y-%m-%d') = '{$this->date}'")
                ->andWhere(['orderSource'   =>  \Yii::$app->params['configuration']->id])
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