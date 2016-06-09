<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 17.05.16
 * Time: 17:17
 */

namespace backend\models;


class SendedPayment extends \common\models\SendedPayment
{

    public function getOrder(){
        return $this->hasOne(History::className(), ['number' => 'nomer_id']);
    }

}