<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 30.10.15
 * Time: 13:49
 */

namespace backend\models;

use sammaye\audittrail\AuditTrail;
use yii\helpers\ArrayHelper;

class SborkaItem extends \common\models\SborkaItem{

    public $priceModified = false;

    public function behaviors()
    {
        return [
            'LoggableBehavior' => [
                'class' => 'sammaye\audittrail\LoggableBehavior',
                'ignored' => [
                    'added',
                ],
            ]
        ];
    }

    public static function find(){
        return parent::find()->with('good');
    }

    public function getControlled(){
        return $this->realyCount == $this->originalCount;
    }

    public function getLeftControl(){
        return $this->originalCount - $this->realyCount;
    }

    public function getInOrder(){
        return $this->vzakaz == 1;
    }

    public function getNotFounded(){
        return $this->nezakaz == 1;
    }

    public function setInOrder($val){
        return $this->vzakaz = $val;
    }

    public function setNotFounded($val){
        return $this->nezakaz = $val;
    }

    public function getCode(){
        return $this->good->Code;
    }

    public function setCount($val){
        $this->count = $val;
    }

    public function getSum(){
        return $this->price * $this->count;
    }

    public function getOriginalSum(){
        return $this->originalPrice * $this->count;
    }

    public function getDiscountSum(){
        return $this->originalPrice - $this->price;
    }

    public function getCustomerDiscountSum(){
        if(!empty($this->discountSize) && $this->customerRule < 0){
            return $this->discountSum;
        }

        return 0;
    }

    public function getParentOrders(){
        $parentOrders = ArrayHelper::getColumn(AuditTrail::find()->select("old_value")->where(['model_id' => $this->ID, 'field' => 'orderID'])->andWhere(['like', 'model', 'SborkaItem'])->asArray()->all(), 'old_value');

        return History::find()->where(['in', 'ID', $parentOrders])->all();
    }

}