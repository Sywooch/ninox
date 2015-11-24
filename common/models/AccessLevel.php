<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "accessLevels".
 *
 * @property integer $actionID
 * @property integer $level
 * @property string $description
 */
class AccessLevel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accessLevels';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['actionID', 'level'], 'required'],
            [['actionID', 'level'], 'integer'],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'actionID' => Yii::t('common', 'Action ID'),
            'level' => Yii::t('common', 'Level'),
            'description' => Yii::t('common', 'Description'),
        ];
    }
}
