<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 22.10.15
 * Time: 18:09
 */

namespace common\models;


use yii\base\Model;

class NovaPoshtaContactRecipient extends Model{

    public $CounterpartyRef;
    public $FirstName;
    public $LastName;
    public $MiddleName;
    public $Phone;

    public function save(){
        return \Yii::$app->NovaPoshta->createRecipientContact($this);
    }

}