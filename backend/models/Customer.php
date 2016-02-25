<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 16.12.15
 * Time: 15:42
 */

namespace backend\models;


class Customer extends \common\models\Customer{

    public function init(){
        $this->discount = !empty($this->discount) ? $this->discount : 0;

        return parent::init();
    }

}