<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 27.05.16
 * Time: 15:59
 */

namespace backend\modules\orders\models;


use backend\models\History;
use yii\base\Model;

class OrderCustomerForm extends Model
{

    public $name;

    public $surname;

    public $phone;

    public $email;

    public $paymentType;

    public $paymentParam;

    public $paymentInfo;

    public $coupon;

    public $added;

    public $number;

    /**
     * @var History
     */
    private $order;

    public function attributeLabels()
    {
        return [
            'name'          =>  'Имя',
            'surname'       =>  'Фамилия',
            'phone'         =>  'Телефон',
            'email'         =>  'Эл. почта',
            'paymentType'   =>  'Тип оплаты',
            'paymentParam'  =>  'Способ оплаты',
            'coupon'        =>  'Промокод',
            'added'         =>  'Добавлено',
            'number'        =>  'Номер заказа',
        ];
    }

    public function rules()
    {
        return [
            [['name', 'surname', 'coupon'], 'string'],
            [['phone', 'paymentType', 'paymentParam', 'added', 'number'], 'number'],
            [['email'], 'email'],
        ];
    }

    /**
     * @param History $order
     */
    public function loadOrder($order){
        $this->order = $order;

        return $this->setAttributes([
            'name'          =>  $order->customerName,
            'surname'       =>  $order->customerSurname,
            'phone'         =>  $order->customerPhone,
            'email'         =>  $order->customerEmail,
            'paymentType'   =>  $order->paymentType,
            'paymentParam'  =>  $order->paymentParam,
            'coupon'        =>  $order->coupon,
            'added'         =>  $order->added,
            'number'        =>  $order->number
        ]);
    }

    public function save(){

    }

}