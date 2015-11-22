<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "history".
 *
 * @property integer $id
 * @property string $customerEmail
 * @property string $customerName
 * @property string $customerSurname
 * @property string $customerPhone
 * @property string $deliveryAddress
 * @property string $deliveryRegion
 * @property integer $added
 * @property string $customerFathername
 * @property integer $deliveryType
 * @property string $deliveryCity
 * @property string $customerComment
 * @property integer $customerID
 * @property string $deliveryInfo
 * @property string $coupon
 * @property integer $paymentType
 * @property string $paymentInfo
 * @property integer $callback
 * @property integer $canChangeItems
 * @property integer $actualAmount
 * @property double $amountDeductedOrder
 * @property integer $moneyCollectorUserId
 * @property string $nakladna
 * @property integer $globalmoney
 * @property integer $nakladnaSendState
 * @property integer $done
 * @property integer $responsibleUserID
 * @property integer $confirmed
 * @property integer $moneyConfirmed
 * @property integer $confirm_otd
 * @property string $moneyConfirmedDate
 * @property integer $processed
 * @property string $doneDate
 * @property string $sendDate
 * @property string $receivedDate
 * @property integer $smsState
 * @property integer $deleted
 * @property integer $takeOrder
 * @property string $takeOrderDate
 * @property integer $takeTTNMoney
 * @property string $takeTTNMoneyDate
 * @property integer $boxesCount
 * @property integer $isNew
 * @property string $deleteDate
 * @property integer $transactionSended
 * @property string $confirmedDate
 * @property string $sendSmsDate
 * @property integer $callsCount
 */
class History extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['added', 'deliveryType', 'customerID', 'paymentType', 'callback', 'canChangeItems', 'actualAmount', 'moneyCollectorUserId', 'globalmoney', 'nakladnaSendState', 'done', 'responsibleUserID', 'confirmed', 'moneyConfirmed', 'confirm_otd', 'processed', 'smsState', 'deleted', 'takeOrder', 'takeTTNMoney', 'boxesCount', 'isNew', 'transactionSended', 'callsCount'], 'integer'],
            [['customerComment'], 'string'],
            [['amountDeductedOrder'], 'number'],
            [['nakladna', 'takeOrderDate', 'takeTTNMoneyDate'], 'required'],
            [['moneyConfirmedDate', 'doneDate', 'sendDate', 'receivedDate', 'takeOrderDate', 'takeTTNMoneyDate', 'deleteDate', 'confirmedDate', 'smsSendDate'], 'safe'],
            [['customerEmail', 'deliveryAddress', 'deliveryRegion', 'deliveryCity', 'deliveryInfo', 'coupon', 'paymentInfo'], 'string', 'max' => 255],
            [['customerName', 'customerSurname', 'customerPhone', 'customerFathername'], 'string', 'max' => 64],
            [['nakladna'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                    => 'ID',
            'customerEmail'         => 'email клиента',
            'customerName'          => 'Имя клиента',
            'customerSurname'       => 'Фамилия клиента',
            'customerPhone'         => 'Телефон клиента',
            'deliveryAddress'       => 'Адрес доставки',
            'deliveryRegion'        => 'Область доставки',
            'added'                 => 'Дата',
            'customerFathername'    => 'Отчество клиента',
            'deliveryType'          => 'Способ доставки',
            'deliveryCity'          => 'Город доставки',
            'customerComment'       => 'Комментарий клиента',
            'customerID'            => 'ID клиента',
            'deliveryInfo'          => 'Номер склада',
            'coupon'                => 'Промокод',
            'paymentType'           => 'Тип оплаты',
            'paymentInfo'           => 'Опция оплаты',
            'callback'              => 'Перезванивали?',
            'canChangeItems'        => 'Можно делать замену?',
            'actualAmount'          => 'Фактическая сумма',
            'amountDeductedOrder'   => 'Списано со счёта пользователя',
            'moneyCollectorUserId'  => 'Забрал деньги',
            'nakladna'              => 'Номер ТТН',
            'globalmoney'           => 'Globalmoney',
            'nakladnaSendState'     => 'Отправлена накладная',
            'done'                  => 'Заказ выполнен',
            'responsibleUserID'     => 'Менеджер заказа',
            'confirmed'             => 'Подтверждён',
            'moneyConfirmed'        => 'Подтверждена оплата',
            'confirm_otd'           => 'Confirm Otd',
            'moneyConfirmedDate'    => 'Money Confirmed Date',
            'processed'             => 'Processed',
            'doneDate'              => 'Дата выполнения заказа',
            'sendDate'              => 'Дата отправки заказа',
            'receivedDate'          => 'Received Date',
            'smsState'              => 'Отправлена смс',
            'deleted'               => 'Удалён',
            'takeOrder'             => 'Take Order',
            'takeOrderDate'         => 'Take Order Date',
            'takeTTNMoney'          => 'Take Ttnmoney',
            'takeTTNMoneyDate'      => 'Take Ttnmoney Date',
            'boxesCount'            => 'Количество коробок',
            'isNew'                 => 'Is New',
            'deleteDate'            => 'Дата удаления заказа',
            'transactionSended'     => 'Состояние транзакции Google Analytics',
            'confirmedDate'         => 'Confirmed Date',
            'smsSendDate'           => 'Send Sms Date',
            'callsCount'            => 'Calls Count',
        ];
    }
}
