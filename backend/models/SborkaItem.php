<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 30.10.15
 * Time: 13:49
 */

namespace backend\models;

class SborkaItem extends \common\models\SborkaItem{

    public $priceModified = false;
    public $addedCount = 0;

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

    public function getPhoto()
    {
        return $this->good->photo;
    }

    public function getCode(){
        return $this->good->Code;
    }

    public function setCount($val){
        $this->count = $val;
    }

    public function __set($name, $value){
        parent::__set($name, $value);

        switch($name){
            case 'count':
                $this->addedCount = $value - $this->getOldAttribute('count');
                break;
        }
    }

}