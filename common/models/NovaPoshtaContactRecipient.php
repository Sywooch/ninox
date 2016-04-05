<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 22.10.15
 * Time: 18:09
 */

namespace common\models;


use yii\base\Model;
use yii\helpers\Json;

class NovaPoshtaContactRecipient extends Model{

    public $CounterpartyRef;
    public $FirstName;
    public $LastName;
    public $MiddleName;
    public $Phone;

    private $_response;

    public function getResponse(){
        return $this->_response;
    }

    public function save(){
        return \Yii::$app->NovaPoshta->createRecipientContact($this);
    }

}