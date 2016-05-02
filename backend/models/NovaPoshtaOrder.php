<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 04.04.16
 * Time: 16:04
 */

namespace backend\models;

use common\models\NovaPoshtaContactRecipient;
use yii\base\InvalidParamException;
use yii\base\Object;
use yii\helpers\Json;

class NovaPoshtaOrder extends NovaPoshtaModels
{

    /**
     * @required
     * @type string - значение из справочника "тип плательщика"
     */
    public $PayerType   = "Recipient";

    /**
     * @required
     * @type string - Значение из справочника "форма оплаты"
     */
    public $PaymentMethod = "Cash";

    /**
     * @required
     * @type string - значение из справочника "тип груза"
     */
    public $CargoType   =   "Cargo";

    /**
     * @required
     * @type string - Значение из справочника "Технология доставки"
     */
    public $ServiceType;

    /**
     * @required
     * @type int - целое число, количество мест отправления
     */
    public $SeatsAmount = 1;

    /**
     * @required
     * @type string - значение из справочника "описание груза"
     */
    public $Description;

    /**
     * @required
     * @type int - целое число, объявленая стоимость
     */
    public $Cost;

    /**
     * @required
     * @type string - Город отправителя
     */
    public $CitySender = '8d5a980d-391c-11dd-90d9-001a92567626';

    /**
     * @required
     * @type string - Код отправителя
     */
    public $Sender  =   "37c282ce-1624-11e4-acce-0050568002cf";

    /**
     * @required
     * @type string - код аддреса отправителя
     */
    public $SenderAddress   =   "1692283e-e1c2-11e3-8c4a-0050568002cf";

    /**
     * @required
     * @type string - код контактного лица отправителя
     */
    public $ContactSender   =   "6a80255e-045d-11e5-ad08-005056801333";

    /**
     * @required
     * @type string - телефон отправителя
     */
    public $SendersPhone    =   "380503171161";

    /**
     * @required
     * @type string - Дата отправки, в формате dd.mm.yyyy
     */
    public $DateTime;

    /**
     * @required
     * @type string - Код получателя
     */
    public $Recipient;

    /**
     * @required
     * @type string - Код города получателя
     */
    public $CityRecipient;

    /**
     * @required
     * @type string - Код аддреса получателя
     */
    public $RecipientAddress;

    /**
     * @required
     * @type string - Код контактного лица получателя
     */
    public $ContactRecipient;

    /**
     * @required
     * @type string - Телефон получателя
     */
    public $RecipientsPhone;

    /**
     * @required
     * @type string - параметр для указания вида груза "шины-диски" или "паллета", обязательно для заполнения, если тип груза ($CargoType) - "Шини-диски" или "Палета"
     */
    public $CargoDetails;

    /**
     * @type string - Код из справочника
     */
    public $CargoDescription;

    /**
     * @type int - целое число
     */
    public $Amount;

    /**
     * @type
     */
    public $OptionsSeat = [];

    /**
     * @type
     */
    public $Pack;

    /**
     * @type
     */
    public $AdditionalInformation;

    /**
     * @type
     */
    public $PackingNumber;

    /**
     * @type
     */
    public $AccompanyingDocuments;

    /**
     * @type integer - номер заказа, в системе Krasota-Style
     */
    public $InfoRegClientBarcodes;

    /**
     * @type integer - ID заказа в системе Krasota-style
     */
    private $_orderID;

    /**
     * @type
     */
    private $_number;

    /**
     * @type History
     */
    private $_order;

    /**
     * @type array
     */
    public $BackwardDeliveryData = [];

    private $notDefaultSender = false;

    public function rules(){
        return [
            [['PackingNumber','OptionsSeat', 'CargoDescription', 'number', 'RecipientContactName', 'recipientArea', 'recipientsPhone', 'orderID', 'deliveryCost', 'deliveryReference', 'deliveryEstimatedDate'], 'safe'],
            [['DateTime', 'ServiceType', 'Sender', 'CitySender', 'SenderAddress', 'ContactSender',
                'SendersPhone', 'Recipient', 'CityRecipient', 'RecipientAddress', 'ContactRecipient',
                'RecipientsPhone', 'PaymentMethod', 'PayerType', 'Cost', 'SeatsAmount', 'Description',
                'CargoType'], 'required'],
        ];
    }

    /**
     * @param History $order
     */
    public function setOrderData($order){
        if($order instanceof \common\models\History == false){
            throw new InvalidParamException("переменная orderData должна быть \\common\\models\\History!");
        }

        if(empty($order->customer->recipient->cityID)){
            $order->customer->recipient->cityID = \Yii::$app->NovaPoshta->city($order->deliveryCity);
        }

        if(empty($order->customer->recipientID)){
            $orderRecipient = new NovaPoshtaRecipient([
                'FirstName' =>  $order->customerName,
                'LastName'  =>  $order->customerSurname,
                'MiddleName'=>  $order->customerFathername,
                'Phone'     =>  $order->customerPhone,
                'Email'     =>  $order->customerEmail,
                'CityRef'   =>  $order->customer->recipient->cityID
            ]);

            if($orderRecipient->save()){
                $order->customer->recipientID = $orderRecipient->recipient['Ref'];

                $order->customer->recipient->contactID = $orderRecipient->recipient['ContactPerson']['data'][0]['Ref'];
            }
        }

        if(empty($order->customer->recipient->contactID)){
            $contact = new NovaPoshtaContactRecipient([
                'CounterpartyRef'   =>  $order->customer->recipientID,
                'FirstName'         =>  $order->customerName,
                'LastName'          =>  $order->customerSurname,
                'MiddleName'        =>  $order->customerFathername,
                'Phone'             =>  $order->customerPhone
            ]);

            $contact = $contact->save();

            if($contact){
                $order->customer->recipient->contactID = $contact['Ref'];
            }
        }

        $department = \Yii::$app->NovaPoshta->department($order->deliveryInfo, $order->customer->recipient->cityID);

        if(!($department)){
            $this->addError('RecipientAddress', 'Не удалось распознать отделение получателя!');
        }else{
            $order->customer->recipient->recipientAddress = $department['Ref'];
        }

        $order->customer->save(false);

        $this->setAttributes([
            'number'                =>  $order->nakladna,
            'Cost'                  =>  empty($order->actualAmount) ? $order->originalSum : $order->actualAmount,
            'Recipient'             =>  $order->customer->recipientID,
            'CityRecipient'         =>  $order->customer->recipient->cityID,
            'RecipientAddress'      =>  $order->customer->recipient->recipientAddress,
            'ContactRecipient'      =>  $order->customer->recipient->contactID,
            'RecipientsPhone'       =>  $order->customerPhone,
            'InfoRegClientBarcodes' =>  $order->number,
        ], false);

        $this->setOrderID($order->id);

        if($order->paymentType == 1){
            $backwardDelivery = new \stdClass();

            $backwardDelivery->PayerType = 'Recipient';
            $backwardDelivery->CargoType = 'Money';
            $backwardDelivery->RedeliveryString = $this->Cost;

            $this->BackwardDeliveryData = [
                $backwardDelivery
            ];
        }
    }

    public function getOrderID(){
        return $this->_orderID;
    }

    public function setOrderID($val){
        $this->_orderID = $val;
    }

    public function getNumber(){
        return $this->_number;
    }

    public function setNumber($value){
        $this->_number = $value;

        if($this->order){
            $this->order->nakladna = $value;

            $this->order->save(false);
        }
    }

    public function getOrder(){
        if(empty($this->_order) && !empty($this->orderID)){
            $this->_order = History::findOne($this->orderID);
        }

        return $this->_order;
    }

    public function setDeliveryReference($value){
        if($this->order){
            $this->order->deliveryReference = $value;

            $this->order->save(false);
        }
    }

    public function setDeliveryCost($value){
        if($this->order){
            $this->order->deliveryCost = $value;

            $this->order->save(false);
        }
    }

    public function setDeliveryEstimatedDate($value){
        if($this->order){
            $this->order->deliveryEstimatedDate = $value;

            $this->order->save(false);
        }
    }

    public function getDeliveryReference(){
        return $this->order->deliveryReference;
    }


    public function getDeliveryCost(){
        return $this->order->deliveryCost;
    }

    public function getDeliveryEstimatedDate(){
        return $this->order->deliveryEstimatedDate;
    }

    public function find($id){

    }

    public function save(){
        empty($this->DateTime) ? $this->DateTime = date('d.m.Y') : null;

        $this->SeatsAmount = sizeof($this->OptionsSeat);

        return \Yii::$app->NovaPoshta->createOrder($this);
    }

    public function attributeLabels()
    {
        return [
            'ServiceType'   =>  'Тип доставки',
            'PaymentMethod' =>  'Метод оплаты доставки',
            'PayerType'     =>  'Кто оплачивает доставку',
            'Cost'          =>  'Стоимость посылки',
            'Description'   =>  'Описание'
        ];
    }

}