<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 04.04.16
 * Time: 17:35
 */

namespace backend\models;


use yii\web\NotFoundHttpException;

class NovaPoshtaRecipient extends NovaPoshtaModels
{
    /**
     * @type
     */
    public $CityRef;

    /**
     * @type
     */
    public $FirstName;

    /**
     * @type
     */
    public $MiddleName;

    /**
     * @type
     */
    public $LastName;

    /**
     * @type
     */
    public $Phone;

    /**
     * @type
     */
    public $Email;

    /**
     * @type string
     */
    public $CounterpartyType = "PrivatePerson";

    /**
     * @type string
     */
    public $CounterpartyProperty = "Recipient";

    /**
     * @type NovaPoshtaRecipient
     */
    private $recipient;

    public function setCity($value){
        if($this->isHash($value)){
            $this->CityRef = $value;
        }else{
            $cityRef = \Yii::$app->NovaPoshta->city($value);

            if(!$cityRef){
                throw new NotFoundHttpException("Город {$value} не найден!");
            }

            $this->CityRef = $cityRef['Ref'];
        }
    }

    public function getRecipient(){
        return $this->recipient;
    }

    /**
     * @return bool
     */
    public function save(){
        $this->recipient = \Yii::$app->NovaPoshta->createRecipient($this);

        if(!$this->recipient){
            return false;
        }

        return $this->recipient;
    }

}