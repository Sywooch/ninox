<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 21.10.15
 * Time: 17:06
 */

namespace common\models;


use yii\base\Model;

class NovaPoshtaRecipient extends Model{

    public $CityRef;
    public $FirstName;
    public $MiddleName;
    public $LastName;
    public $Phone;
    public $Email;
    public $CounterpartyType = "PrivatePerson";
    public $CounterpartyProperty = "Recipient";

    private $recipient = [];

    public function save(){
        $this->recipient = \Yii::$app->NovaPoshta->createRecipient($this);

        if(!$this->recipient){
            return false;
        }

        return $this->recipient['Ref'];
    }

}