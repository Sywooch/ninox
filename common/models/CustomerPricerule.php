<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "partnersPricerules".
 *
 * @property integer $ID
 * @property string $customerID
 * @property string $Formula
 * @property integer $Enabled
 * @property integer $Priority
 */
class CustomerPricerule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partnersPricerules';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customerID', 'Formula'], 'required'],
            [['customerID', 'Enabled', 'Priority'], 'integer'],
            [['Formula'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('common', 'ID'),
            'customerID' => Yii::t('common', 'Customer ID'),
            'Formula' => Yii::t('common', 'Formula'),
            'Enabled' => Yii::t('common', 'Enabled'),
            'Priority' => Yii::t('common', 'Priority'),
        ];
    }
}
