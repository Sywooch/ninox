<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "partnersAddresses".
 *
 * @property integer $ID
 * @property integer $partnerID
 * @property string $country
 * @property string $region
 * @property string $city
 * @property string $address
 * @property integer $shippingType
 * @property string $shippingParam
 * @property integer $paymentType
 * @property string $paymentParam
 */
class CustomerAddresses extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partnersAddresses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partnerID'], 'required'],
            [['partnerID', 'shippingType', 'paymentType'], 'integer'],
            [['country', 'region', 'city', 'address'], 'string'],
            [['shippingParam', 'paymentParam'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'partnerID' => 'Partner ID',
            'country' => 'Country',
            'region' => 'Region',
            'city' => 'City',
            'address' => 'Address',
            'shippingType' => 'Shipping Type',
            'shippingParam' => 'Shipping Param',
            'paymentType' => 'Payment Type',
            'paymentParam' => 'Payment Param',
        ];
    }
}
