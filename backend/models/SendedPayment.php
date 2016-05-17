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
        return History::findOne(['number' => filter_var($this->nomer_id, FILTER_SANITIZE_NUMBER_INT)]);
    }

}