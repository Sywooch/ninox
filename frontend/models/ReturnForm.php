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
    public $comment = '';

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
            [['orderNumber', 'sendDate', 'nakladna', 'customerPhone', 'refundMethod', 'cardNumber'], 'required'],
            [['customerPhone', 'sendDate', 'refundMethod', 'cardNumber', 'cardHolder', 'comment'], 'string'],
            [['orderNumber', 'nakladna', 'brokenGood', 'notMatchGood', 'notLikeGood'], 'integer']
        ];
    }

    public function getRefundMethods(){
        return [
            'на карту'          =>  'на карту ПриватБанк',
            'на личный счет'    =>  'добавить на ваш личный счет Krasota-Style.ua'
        ];
    }

    public function save(){
        $return = new OrderReturn();

        $return->orderNumber = $this->orderNumber;

        $return->setAttributes([
            'data_otpravki' =>  $this->sendDate,
            'ttn'           =>  $this->nakladna,
            'telefon'       =>  $this->customerPhone,
            'brak'          =>  $this->brokenGood,
            'sootvetstvie'  =>  $this->notMatchGood,
            'nepodoshel'    =>  $this->notLikeGood,
            'vozvrat_deneg' =>  $this->refundMethod,
            'comment'       =>  $this->comment,
            'bank_cart'     =>  $this->cardNumber,
            'bank_pib'      =>  $this->cardHolder,
            'prichina_vozvrata' =>  '',
        ], false);

        $return->save(false);

    }

    public function attributeLabels(){
        return [
            'orderNumber'           =>  \Yii::t('shop', '№ заказа'),
            'customerPhone'         =>  \Yii::t('shop', 'Тел. отправителя'),
            'sendDate'              =>  \Yii::t('shop', 'Дата отправки'),
            'nakladna'              =>  \Yii::t('shop', '№ ТТН'),
            'comment'               =>  \Yii::t('shop', ''),
            'brokenGood'            =>  \Yii::t('shop', 'брак товара'),
            'notMatchGood'          =>  \Yii::t('shop', 'не соответствует заказу'),
            'notLikeGood'           =>  \Yii::t('shop', 'просто не подошел'),
            'cardNumber'            =>  \Yii::t('shop', 'Номер карты для возврата денег*'),
            'cardHolder'            =>  \Yii::t('shop', 'Имя и фамилия владельца карты'),
            'refundMethod'          =>  \Yii::t('shop', ''),
        ];
    }

}