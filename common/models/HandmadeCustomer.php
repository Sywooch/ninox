<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "handmade_partners".
 *
 * @property string $customerID
 * @property string $handmade_desc
 * @property integer $enabled
 * @property string $phone
 * @property string $email
 * @property string $company
 * @property string $city
 * @property string $address
 * @property string $photo
 * @property integer $manager
 * @property string $skills
 * @property integer $account
 * @property string $payments_type
 * @property string $delivery_type
 * @property integer $balance
 * @property string $dateActive
 */
class HandmadeCustomer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'handmade_partners';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customerID', 'handmade_desc', 'enabled', 'phone', 'email', 'company', 'city', 'address', 'photo', 'manager', 'skills', 'account', 'payments_type', 'delivery_type', 'balance'], 'required'],
            [['customerID', 'enabled', 'manager', 'account', 'balance'], 'integer'],
            [['handmade_desc'], 'string'],
            [['dateActive'], 'safe'],
            [['phone'], 'string', 'max' => 10],
            [['email', 'company', 'city', 'address', 'photo', 'skills', 'payments_type', 'delivery_type'], 'string', 'max' => 255],
            [['customerID'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customerID' => Yii::t('common', 'Customer ID'),
            'handmade_desc' => Yii::t('common', 'Handmade Desc'),
            'enabled' => Yii::t('common', 'Enabled'),
            'phone' => Yii::t('common', 'Phone'),
            'email' => Yii::t('common', 'Email'),
            'company' => Yii::t('common', 'Company'),
            'city' => Yii::t('common', 'City'),
            'address' => Yii::t('common', 'Address'),
            'photo' => Yii::t('common', 'Photo'),
            'manager' => Yii::t('common', 'Manager'),
            'skills' => Yii::t('common', 'Skills'),
            'account' => Yii::t('common', 'Account'),
            'payments_type' => Yii::t('common', 'Payments Type'),
            'delivery_type' => Yii::t('common', 'Delivery Type'),
            'balance' => Yii::t('common', 'Balance'),
            'dateActive' => Yii::t('common', 'Date Active'),
        ];
    }
}
