<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "passwordrecovery".
 *
 * @property string $customerID
 * @property string $link
 * @property string $active
 */
class PasswordRecovery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'passwordrecovery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customerID', 'link'], 'required'],
            [['customerID'], 'integer'],
            [['link'], 'string'],
            [['active'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customerID' => Yii::t('common', 'Customer ID'),
            'link' => Yii::t('common', 'Link'),
            'active' => Yii::t('common', 'Active'),
        ];
    }
}
