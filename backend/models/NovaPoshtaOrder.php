<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 04.04.16
 * Time: 16:04
 */

namespace backend\models;

use yii\base\InvalidParamException;

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
     * @type float - общий объём, м.куб (min 0.0004), обязательно для заполнения, если не указаны значения OptionsSeat
     */
    public $VolumeGeneral;

    /**
     * @required
     * @type float - вес фактический, кг
     */
    public $Weight = 0.1;

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
     * @type float - вес объёмный, кг, не обязательное поле, если указаны значения VolumeGeneral или OptionsSeat
     */
    public $VolumeWeight;

    /**
     * @type
     */
    public $OptionsSeat;

    /**
     * @type float
     */
    public $volumetricVolume = 0.0004;

    /**
     * @type int
     */
    public $volumetricWidth = 5;

    /**
     * @type int
     */
    public $volumetricLength = 5;

    /**
     * @type int
     */
    public $volumetricHeight = 5;

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
     * @type
     */
    public $InfoRegClientBarcodes;

    /**
     * @type
     */
    private $_number;

    /**
     * @type array
     */
    public $BackwardDeliveryData = [];
    /*private $orderData = [];

    private $recipientData = [];
    private $recipientContacts = [];
    private $recipientDelivery = [];*/

    private $notDefaultSender = false;
    /*private $citySenderData = [];
    private $cityRecipientData = [];
    private $senderAddressData = [];
    private $recipientAddressData = [];*/


    public function rules(){
        return [
            [['cargoDescription', 'number', 'RecipientContactName', 'recipientArea', 'recipientsPhone'], 'safe'],
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

        if(empty($order->customer->cityCode)){
            $order->customer->recipient->cityID = \Yii::$app->NovaPoshta->city($order->deliveryCity);
        }

        if(empty($order->customer->recipientID)){
            \Yii::trace('empty customerReceip');
            $orderRecipient = new NovaPoshtaRecipient([
                'FirstName' =>  $order->customerName,
                'LastName'  =>  $order->customerSurname,
                'MiddleName'=>  $order->customerFathername,
                'Phone'     =>  $order->customerPhone,
                'Email'     =>  $order->customerEmail,
                'CityRef'   =>  $order->customer->recipient->cityID
            ]);

            if($orderRecipient->save()){

                \Yii::trace($orderRecipient, __METHOD__);
                $order->customer->recipientID = $orderRecipient['Ref'];

                /*$order->customer->recipient->setAttributes([
                    'contactID' =>  $orderRecipient['']
                ]);*/

                \Yii::trace($orderRecipient, __METHOD__);
            }
        }

        /*if(empty($order->customer->recipientCityID)){
            $orderRecipient = new NovaPoshtaRecipient([]);
        }

        if(empty($order->customer->recipientAddressID)){
            $orderRecipient = new NovaPoshtaRecipient([]);
        }*/

        //$order->customer->save(false);

        $this->setAttributes([
            'number'                =>  $order->nakladna,
            'Cost'                  =>  $order->actualAmount,
            //'RecipientContactName'  =>  trim($order->customerSurname.' '.$order->customerName.' '.$order->customerFathername),
            'Recipient'             =>  $order->customer->recipientID,
            'CityRecipient'         =>  $order->customer->cityCode,
            'RecipientAddress'      =>  $order->customer->recipient->recipientAddress,
            //'RecipientArea'         =>  $order->deliveryRegion,
            'RecipientsPhone'       =>  $order->customerPhone,

            /*'FirstName' =>  $d$orderata->customerName,
            'MiddleName'=>  $order->customerFathername,
            'LastName'  =>  $order->customerSurname,
            'Phone'     =>  $order->customerPhone*/
        ]);
    }

    public function setRecipientData($data){
        if($data instanceof \common\models\Customer == false){
            throw new InvalidParamException("переменная recipientData должна быть \\common\\models\\Customer!");
        }
        \Yii::trace($data);
    }

    public function setRecipientContacts($data){
        if($data instanceof \common\models\CustomerContacts == false){
            throw new InvalidParamException("переменная recipientContacts должна быть \\common\\models\\CustomerContacts!");
        }
        \Yii::trace($data);
    }

    public function setRecipientDelivery($data){
        if($data instanceof \common\models\CustomerAddresses == false){
            throw new InvalidParamException("переменная recipientDelivery должна быть \\common\\models\\CustomerAddresses!");
        }
        \Yii::trace($data);
    }

    public function getNumber(){
        return $this->_number;
    }

    public function setNumber($value){
        $this->_number = $value;
    }

    public function find($id){

    }

    public function save(){

        empty($this->DateTime) ? $this->DateTime = date('d.m.Y') : null;
        return \Yii::$app->NovaPoshta->createOrder($this);
    }

}