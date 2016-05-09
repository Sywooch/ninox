<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 02.05.16
 * Time: 17:50
 */

namespace backend\modules\orders\models;


use backend\models\History;
use yii\base\Model;

class OrderPreviewForm extends Model
{

    public $id;

    public $deliveryType;

    public $deliveryParam;

    public $deliveryInfo;

    public $responsibleUser;

    public $nakladna;

    public $paymentType;

    public $paymentParam;

    public $paymentConfirmed;

    public $globalMoneyPayment;

    public $actualAmount;

    /**
     * @var History
     */
    private $order;

    public function rules(){
        return [
            [['deliveryType', 'deliveryParam', 'responsibleUser', 'paymentType', 'paymentParam', 'id'], 'integer'],
            [['deliveryInfo', 'nakladna'], 'string'],
            [['actualAmount'], 'number'],
            [['paymentConfirmed', 'globalMoneyPayment'], 'boolean']
        ];
    }

    /**
     * @param $order History
     */
    public function loadOrder($order){
        $this->setAttributes([
            'id'                =>  $order->id,
            'deliveryType'      =>  $order->deliveryType,
            'deliveryParam'     =>  $order->deliveryParam,
            'deliveryInfo'      =>  $order->deliveryInfo,
            'responsibleUser'   =>  $order->responsibleUserID,
            'nakladna'          =>  $order->nakladna,
            'paymentType'       =>  $order->paymentType,
            'paymentParam'      =>  $order->paymentParam,
            'paymentConfirmed'  =>  $order->moneyConfirmed != 0,
            'actualAmount'      =>  $order->actualAmount,
            'globalMoneyPayment'=>  $order->globalmoney == 1
        ]);

        $this->order = $order;
    }

    public function save(){
        $this->order->setAttributes([
            'deliveryType'      =>  $this->deliveryType,
            'deliveryInfo'      =>  $this->deliveryInfo,
            'responsibleUserID' =>  $this->responsibleUser,
            'nakladna'          =>  $this->nakladna,
            'paymentType'       =>  $this->paymentType,
            'paymentParam'      =>  $this->paymentParam,
            'moneyConfirmed'    =>  $this->paymentConfirmed,
            'actualAmount'      =>  $this->actualAmount,
            'globalmoney'       =>  $this->globalMoneyPayment,
        ]);

        $this->order->save(false);
    }

    public function attributeLabels()
    {
        return [
            'deliveryType'      =>  '',
            'deliveryInfo'      =>  '',
            'responsibleUser'   =>  '',
            'nakladna'          =>  '',
            'paymentType'       =>  '',
            'paymentParam'      =>  '',
            'paymentConfirmed'  =>  '',
            'globalMoneyPayment'=>  '',
            'actualAmount'      =>  ''
        ];
    }

}