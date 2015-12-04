<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 04.12.15
 * Time: 14:03
 */

namespace frontend\models;


use frontend\helpers\OrderHelper;

class History extends \common\models\History{

    public function beforeSave($insert){
        if(!isset($this->oldAttributes['id'])){
            OrderHelper::createOrder($this);
        }

        return parent::beforeSave($insert);
    }

}