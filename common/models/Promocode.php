<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "promocodes".
 *
 * @property integer $id
 * @property string $code
 * @property integer $rule
 * @property integer $state
 * @property integer $oneDemand
 */
class Promocode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'promocodes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rule', 'state', 'oneDemand'], 'integer'],
            [['code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'code' => Yii::t('common', 'Code'),
            'rule' => Yii::t('common', 'Rule'),
            'state' => Yii::t('common', 'State'),
            'oneDemand' => Yii::t('common', 'One Demand'),
        ];
    }
}
