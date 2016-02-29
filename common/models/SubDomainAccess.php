<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "subDomainsAccess".
 *
 * @property integer $userId
 * @property integer $subDomainId
 */
class SubDomainAccess extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subDomainsAccess';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'subDomainId'], 'required'],
            [['userId', 'subDomainId'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userId' => Yii::t('common', 'User ID'),
            'subDomainId' => Yii::t('common', 'Sub Domain ID'),
        ];
    }
}
