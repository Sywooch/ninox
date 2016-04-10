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

    public function __set($name, $value){
        parent::__set($name, $value);

        switch($name){
            case 'count':
                $this->addedCount = $value - $this->getOldAttribute('count');
                break;
        }
    }

}