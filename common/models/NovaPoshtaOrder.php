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
use yii\base\Object;

class NovaPoshtaOrder extends Model{

    public $DateTime;
    public $ServiceType;
    public $Sender;
    public $CitySender;
    public $SenderAddress;
    public $ContactSender;
    public $SendersPhone;
    public $Recipient;
    public $CityRecipient;
    public $RecipientAddress;
    public $ContactRecipient;
    public $RecipientsPhone;
    public $PaymentMethod;
    public $PayerType;
    public $Cost;
    public $SeatsAmount;
    public $Description;
    public $CargoType;
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
    //public $PayerType;
    //public $CargoType;

    public function save(){
        if(empty($this->BackwardDeliveryData)){
            unset($this->BackwardDeliveryData);
        }

        $order = new Object([
            'modelName'         =>  'InternetDocument',
            'calledMethod'      =>  'save',
            'methodProperties'  =>  $this
        ]);

        \Yii::$app->NovaPoshta->createOrder($order);
    }

}