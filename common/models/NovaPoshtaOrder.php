<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 20.10.15
 * Time: 14:37
 *
 * @docs - https://devcenter.novaposhta.ua/docs/services/556eef34a0fe4f02049c664e/operations/556ef753a0fe4f02049c664f
 *
 * @DateTime                -   дата отправки в формате дд.мм.гггг
 * @ServiceType             -   значение из справочника "Технология доставки"
 * @Sender                  -   код отправителя
 * @CitySender              -   код города отправителя
 * @SenderAddress           -   код адреса отправителя
 * @ContactSender           -   код контактного лица отправителя
 * @SendersPhone            -   телефон отправителя в формате: +380660000000, 380660000000, 0660000000
 * @Recipient               -   код получателя
 * @CityRecipient           -   код города получателя
 * @RecipientAddress        -   код адреса получателя
 * @ContactRecipient        -   код контактного лица получателя
 * @RecipientsPhone         -   телефон получателя в формате: +380660000000, 80660000000, 0660000000
 * @PaymentMethod           -   значение из справочника "Форма оплаты"
 * @PayerType               -   значение из справочника "Тип плательщика"
 * @Cost                    -   целое число, объявленная стоимость
 * @SeatsAmount             -   целое число, количество мест отправления
 * @Description             -   значение из справочника Описание груза
 * @CargoType               -   значение из справочника Тип груза
 * @CargoDetails            -   параметр для указания вида груза "шины-диски" или "паллета", обязательно для заполнения, если тип груза (CargoType) - "Шини-диски" или "Палета"
 * @CargoDescription        -   код из справочника
 * @Amount                  -   целое число
 * @Weight                  -   Вес фактический, кг
 * @VolumeWeight            -   вес объемный, кг, не обязательное поле, если указаны значения VolumeGeneral или OptionsSeat
 * @VolumeGeneral           -   обязательно для заполнения, если не указаны значения OptionsSeat
 * @OptionsSeat             -   параметры груза для каждого места отправления, обязательно для заполнения, если не указано значение VolumeGeneral
 * @volumetricVolume        -   объем одного места отправления
 * @volumetricWidth         -   ширина одного места отправления
 * @volumetricLength        -   длинна одного места отправления
 * @volumetricHeight        -   высота одного места отправления
 * @Pack                    -   вид упаковки
 * @AdditionalInformation   -   дополнительная информация об отправлении (любая, необходимая Клиенту информация в ЭН, max - 100 символов)
 * @PackingNumber           -   возможность указать № упаковки (max - 10 символов)
 * @AccompanyingDocuments   -   сопровождающие документы (max - 50 символов)
 * @InfoRegClientBarcodes   -   номер внутреннего заказа Клиента (не хранится в ИС "Новая Почта")
 *
 */

namespace common\models;


use yii\base\Model;
use yii\helpers\Json;

class NovaPoshtaOrder extends Model{

    static $typesOfPayers = [
        'Sender'    =>  'Отправитель',
        'Recipient' =>  'Получатель',
        'ThirdPerson'=> 'Третее лицо'
    ];

    public $DateTime;
    public $ServiceType;
    public $Sender  =   "37c282ce-1624-11e4-acce-0050568002cf";
    public $CitySender = '8d5a980d-391c-11dd-90d9-001a92567626';
    public $SenderAddress   =   "1692283e-e1c2-11e3-8c4a-0050568002cf";
    public $ContactSender   =   "6a80255e-045d-11e5-ad08-005056801333";
    public $SendersPhone    =   "380503171161";
    public $Recipient;
    public $CityRecipient;
    public $RecipientAddress;
    public $ContactRecipient;
    public $RecipientsPhone;
    public $PaymentMethod;
    public $PayerType   = "Recipient";
    public $Cost;
    public $SeatsAmount;
    public $Description;
    public $CargoType   =   "Cargo";
    public $CargoDetails;

    public $CargoDescription;
    public $Amount;
    public $Weight = 0.1;
    public $VolumeWeight;
    public $VolumeGeneral;
    public $OptionsSeat;

    public $volumetricVolume = 0.0004;
    public $volumetricWidth = 5;
    public $volumetricLength = 5;
    public $volumetricHeight = 5;

    public $Pack;
    public $AdditionalInformation;
    public $PackingNumber;
    public $AccompanyingDocuments;
    public $InfoRegClientBarcodes;

    public $BackwardDeliveryData = [];
    private $orderData = [];
    //public $PayerType;
    //public $CargoType;

    private $recipientData = [];
    private $recipientContacts = [];
    private $recipientDelivery = [];

    private $notDefaultSender = false;
    private $citySenderData = [];
    private $cityRecipientData = [];
    private $senderAddressData = [];
    private $recipientAddressData = [];

    public function rules(){
        return [
            [['cargoDescription'], 'safe'],
            [['DateTime', 'ServiceType', 'Sender', 'CitySender', 'SenderAddress', 'ContactSender',
                'SendersPhone', 'Recipient', 'CityRecipient', 'RecipientAddress', 'ContactRecipient',
                'RecipientsPhone', 'PaymentMethod', 'PayerType', 'Cost', 'SeatsAmount', 'Description',
                'CargoType'], 'required'],
        ];
    }

    public function init(){
        $this->DateTime = empty($this->DateTime) ? date('d.m.Y') : $this->DateTime;

        /*
         * Написать тут такой код, чтобы можно было редактировать эти поля (Recipient, CityRecipient и тд.)
         * Сейчас логика такова:
         * init() -> [вставляем данные POST] -> beforeSave() -> save()
         * то есть данные не попадают
         * НО
         * для простоты жизни мы храним некоторые из этих переменных в БД
         * нужно проверять что строки не отличаются от предыдущих, тогда мы вставляем поля из БД
         * если отличаются - делаем запросы...
         * */

        $this->Recipient = empty($this->Recipient) ? $this->recipientData->recipientID : $this->Recipient;
        $this->CityRecipient = empty($this->CityRecipient) ? $this->recipientDelivery->cityRecipient : $this->CityRecipient;
        $this->RecipientAddress = empty($this->RecipientAddress) ? $this->recipientDelivery->recipientAddress : $this->RecipientAddress;
        $this->ContactRecipient = empty($this->ContactRecipient) ? $this->recipientContacts->contactRecipient : $this->ContactRecipient;
        $this->RecipientsPhone = empty($this->RecipientsPhone) ? $this->recipientContacts->value : $this->RecipientsPhone;

        $this->Cost = empty($this->Cost) ? $this->orderData->actualAmount : $this->Cost;
    }

    private function isHash($hash){
        return preg_match('/\w{8}-\w{4}-\w{4}-\w{4}-\w{12}/', $hash) ? true : false;
    }

    public function beforeValidate(){

        return parent::beforeValidate();
    }

    public function getRecipient(){
        //if()
    }

    public function beforeSave(){
        if($this->notDefaultSender){
            if(!$this->isHash($this->citySenderData)){
                $this->citySenderData = \Yii::$app->NovaPoshta->city($this->CitySender);

                if(!($this->citySenderData)){
                    $this->addError('CitySender', 'Не удалось распознать город отправителя!');
                }else{
                    $this->CitySender = $this->citySenderData['Ref'];
                }
            }

            if(!$this->isHash($this->SenderAddress)){
                $this->senderAddressData = \Yii::$app->NovaPoshta->department($this->SenderAddress, $this->CitySender);

                if(!($this->senderAddressData)){
                    $this->addError('SenderAddress', 'Не удалось распознать отделение отправителя!');
                }else{
                    $this->SenderAddress = $this->senderAddressData['Ref'];
                }
            }
        }

        if(!$this->isHash($this->CityRecipient)){
            $this->cityRecipientData = \Yii::$app->NovaPoshta->city($this->CityRecipient);

            if(!($this->cityRecipientData)){
                $this->addError('CityRecipient', 'Не удалось распознать город получателя!');
            }else{
                $this->CityRecipient = $this->cityRecipientData['Ref'];
                $this->recipientDelivery->cityRecipient = $this->CityRecipient;
            }
        }

        if(!$this->isHash($this->RecipientAddress)){
            $this->recipientAddressData = \Yii::$app->NovaPoshta->department($this->RecipientAddress, $this->CityRecipient);

            if(!($this->recipientAddressData)){
                $this->addError('RecipientAddress', 'Не удалось распознать отделение получателя!');
            }else{
                $this->RecipientAddress = $this->recipientAddressData['Ref'];
                $this->recipientDelivery->recipientAddress = $this->RecipientAddress;
            }
        }

        if(!$this->isHash($this->Recipient)){
            $recipient = new NovaPoshtaRecipient([
                'CityRef'   =>  $this->CityRecipient,
                'FirstName' =>  $this->orderData->customerName,
                'MiddleName'=>  '',
                'LastName'  =>  $this->orderData->customerSurname,
                'Phone'     =>  $this->orderData->customerPhone,
                'Email'     =>  $this->orderData->customerEmail,
            ]);

            $recipient = $recipient->save();

            if(!$recipient){
                $this->addError("Recipient", "Невозможно создать получателя: возможно, данные не точные!");
            }else{
                $this->Recipient = $recipient;
                $this->recipientData->recipientID = $recipient;
            }
        }

        if(!$this->isHash($this->ContactRecipient)){
            $contactRecipient = new NovaPoshtaContactRecipient([
               'CounterpartyRef'    =>  $this->Recipient,
               'FirstName'          =>  $this->orderData->customerName,
               'LastName'           =>  $this->orderData->customerSurname,
               'Phone'              =>  $this->recipientContacts->value
            ]);

            $contactRecipient = $contactRecipient->save();

            if(!$contactRecipient){
                $this->addError('ContactRecipient', 'Невозможно создать контактное лицо получателя: возможно, данные неверны');
            }else{
                $this->ContactRecipient = $contactRecipient['Ref'];
                $this->recipientContacts->contactRecipient = $contactRecipient['Ref'];
            }
        }

        if($this->orderData->paymentType == 1){
            $this->BackwardDeliveryData = [
                'PayerType'                 =>  'Recipient',
                'CargoType'                 =>  $this->orderData->globalmoney == 1 ? 'Money' : 'Documents',
                'RedeliveryString'          =>  $this->orderData->globalmoney == 1 ? '\u0426\u0435\u043d\u043d\u044b\u0435 \u0431\u0443\u043c\u0430\u0433\u0438' : '\u0414\u043e\u043a\u0443\u043c\u0435\u043d\u0442\u044b',
                'AfterpaymentOnGoodsCost'   =>  $this->Cost
            ];
        }

        $this->recipientContacts->save();
        $this->recipientDelivery->save();
        $this->recipientData->save();
    }

    public function __set($a, $b){
        switch($a){
            case 'orderData':
            case 'recipientData':
            case 'recipientContacts':
            case 'recipientDelivery':
                $this->$a = $b;
                break;
            default:
                parent::__set($a, $b);
                break;
        }
    }

    public function __get($a){
        switch($a){
            case 'orderData':
            case 'recipientData':
            case 'recipientContacts':
            case 'recipientDelivery':
                return $this->$a;
                break;
            default:
                return parent::__get($a);
                break;
        }
    }

    public function save(){
        $this->beforeSave();
        //if(!$this->validate()){
        //    return $this->getErrors();
        //}

        return \Yii::$app->NovaPoshta->createOrder($this);
    }

}