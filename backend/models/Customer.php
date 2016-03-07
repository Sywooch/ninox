<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 16.12.15
 * Time: 15:42
 */

namespace backend\models;


class Customer extends \common\models\Customer{

    public $city = '';
    public $region = '';

    private $dCity;
    private $dRegion;

    public function init(){
        if(!empty($this->City)){
            $city = explode(', ', $this->City);
            $this->city = $this->dCity = $city[0];
            $this->region = $this->dRegion = $city[1];
        }

        $this->discount = !empty($this->discount) ? $this->discount : 0;

        return parent::init();
    }

    public function beforeSave($insert)
    {
        if($this->city != $this->dCity || $this->region != $this->dRegion){
            $this->City = $this->city.', '.$this->region;
        }


        return parent::beforeSave($insert);
    }

}