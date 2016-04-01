<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "partnersContacts".
 *
 * @property integer $ID
 * @property integer $partnerID
 * @property integer $type
 * @property string $value
 */
class CustomerContacts extends \yii\db\ActiveRecord
{
    const TYPE_EMAIL = 1;
    const TYPE_PHONE = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partnersContacts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'partnerID', 'type', 'value'], 'required'],
            [['ID', 'partnerID', 'type'], 'integer'],
            [['value'], 'string', 'max' => 255]
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
            'type' => 'Type',
            'value' => 'Value',
        ];
    }
}
