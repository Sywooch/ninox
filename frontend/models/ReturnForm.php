<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 10.04.16
 * Time: 17:30
 */

namespace frontend\models;


use common\models\OrderReturn;
use yii\base\Model;

class ReturnForm extends Model
{

    /**
     * @type integer
     */
    public $orderNumber;

    /**
     * @type string
     */
    public $customerPhone;

    /**
     * @type string
     */
    public $sendDate;

    /**
     * @type integer
     */
    public $nakladna;

    /**
     * @type string
     */
    public $comment;

    /**
     * @type string
     */
    public $refundMethod = 'на карту';

    /**
     * @type bool
     */
    public $brokenGood = 0;

    /**
     * @type bool
     */
    public $notMatchGood = 0;

    /**
     * @type bool
     */
    public $notLikeGood = 0;

    /**
     * @type string
     */
    public $cardNumber;

    /**
     * @type string
     */
    public $cardHolder;

    public function rules()
    {
        return [
            [['customerPhone', 'sendDate', 'refundMethod', 'cardNumber', 'cardHolder'], 'string'],
            [['orderNumber', 'nakladna', 'brokenGood', 'notMatchGood', 'notLikeGood'], 'integer']
        ];
    }


    public function save(){
        $return = new OrderReturn();

        $return->setAttributes([
            'orderNumber'   =>  $this->orderNumber,
            'sendDate'      =>  $this->sendDate,
            'ttn'           =>  $this->nakladna,
            'telefon'       =>  $this->customerPhone,
            'brak'          =>  $this->brokenGood,
            'sootvetstvie'  =>  $this->notMatchGood,
            'nepodoshel'    =>  $this->notLikeGood,
            'vozvrat_deneg' =>  $this->refundMethod,
            'comment'       =>  $this->comment,
            'bank_cart'     =>  $this->cardNumber,
            'bank_pib'      =>  $this->cardHolder
        ], false);

        $return->save(false);
    }

}