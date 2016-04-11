<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 30.10.15
 * Time: 13:49
 */

namespace backend\models;


use yii\web\NotFoundHttpException;

class SborkaItem extends \common\models\SborkaItem{

    public $priceModified = false;
    public $addedCount = 0;

    private $_good;

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

    public function getGood(){
        if(empty($this->_good)){
            $good = Good::findOne($this->itemID);

            if(!$good){
                throw new NotFoundHttpException("Товара с идентификатором {$this->itemID} не существует!");
            }

            $this->_good = $good;
        }

        return $this->_good;
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

    public function __set($name, $value){
        parent::__set($name, $value);

        switch($name){
            case 'count':
                $this->addedCount = $value - $this->getOldAttribute('count');
                break;
        }
    }

}