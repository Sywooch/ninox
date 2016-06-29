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

    public $number;

    public $deliveryType;

    public $deliveryParam;

    public $deliveryInfo;

    public $responsibleUser;

    public $nakladna;

    public $paymentType;

    public $paymentParam;

    public $moneyConfirmed;

    public $actualAmount;

    public $nakladnaSendDate;

    public $moneyConfirmedDate;

    public $moneyCollector;

    /**
     * @var History
     */
    private $order;

    public function rules(){
        return [
            [['deliveryType', 'deliveryParam', 'responsibleUser', 'paymentType', 'paymentParam', 'id', 'number', 'moneyConfirmed'], 'integer'],
            [['deliveryInfo', 'nakladna'], 'string'],
            [['actualAmount'], 'number'],
            [['nakladnaSendDate', 'moneyConfirmedDate', 'moneyCollector'], 'safe'],
        ];
    }

    /**
     * @param $order History
     */
    public function loadOrder($order){
        $this->setAttributes([
            'id'                    =>  $order->id,
            'number'                =>  $order->number,
            'deliveryType'          =>  $order->deliveryType,
            'deliveryParam'         =>  $order->deliveryParam,
            'deliveryInfo'          =>  $order->deliveryInfo,
            'responsibleUser'       =>  $order->responsibleUserID,
            'nakladna'              =>  $order->nakladna,
            'paymentType'           =>  $order->paymentType,
            'paymentParam'          =>  $order->paymentParam,
            'moneyConfirmed'        =>  $order->moneyConfirmed,
            'actualAmount'          =>  $order->actualAmount,
            'nakladnaSendDate'      =>  $order->nakladnaSendDate,
            'moneyConfirmedDate'    =>  $order->moneyConfirmedDate,
            'moneyCollector'        =>  $order->moneyCollector,
        ]);

        $this->order = $order;
    }

    public function save(){
        $this->order->setAttributes([
            'deliveryType'      =>  $this->deliveryType,
            'deliveryParam'     =>  $this->deliveryParam,
            'deliveryInfo'      =>  $this->deliveryInfo,
            'responsibleUserID' =>  $this->responsibleUser,
            'nakladna'          =>  $this->nakladna,
            'paymentType'       =>  $this->paymentType,
            'paymentParam'      =>  $this->paymentParam,
            'moneyConfirmed'    =>  $this->moneyConfirmed,
            'actualAmount'      =>  $this->actualAmount,
        ]);

        $this->order->save(false);
    }

    public function attributeLabels()
    {
        return [
            'deliveryType'      =>  'Способ доставки:',
            'deliveryParam'     =>  '',
            'deliveryInfo'      =>  '',
            'responsibleUser'   =>  'Менеджер:',
            'nakladna'          =>  'ТТН:',
            'paymentType'       =>  'Способ оплаты:',
            'paymentParam'      =>  '',
            'moneyConfirmed'    =>  'Оплата:',
            'actualAmount'      =>  'Сумма к оплате:'
        ];
    }

}