<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 10.04.16
 * Time: 17:48
 */

namespace frontend\models;


use common\models\CustomerOrderPayment;
use yii\base\Model;

class PaymentConfirmForm extends Model
{

    public $orderNumber;

    public $sum;

    public $paymentDate;

    public $paymentType;

    public function getPaymentTypes(){
        return [
            'Приват24'              =>  'Приват24',
            'терминал Приватбанк'   =>  'терминал Приватбанк',
            'касса Приватбанк'      =>  'касса Приватбанк',
            'система Liqpay'        =>  'Система Liqpay'
        ];
    }

    public function save(){
        $customerOrderPayment = new CustomerOrderPayment();

        $customerOrderPayment->setAttributes([
            'nomer_id'      =>  $this->orderNumber,
            'summ'          =>  $this->sum,
            'data_oplaty'   =>  $this->paymentDate,
            'sposoboplaty'  =>  $this->paymentType
        ], false);

        $customerOrderPayment->save(false);
    }

    public function attributeLabels(){
        return [
            'orderNumber'           =>  \Yii::t('shop', '№ заказа'),
            'sum'                   =>  \Yii::t('shop', 'Сумма оплаты'),
            'paymentDate'           =>  \Yii::t('shop', 'Дата оплаты'),

        ];
    }

}