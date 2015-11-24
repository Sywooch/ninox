<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "yii_actions".
 *
 * @property integer $id
 * @property integer $controllerID
 * @property string $action
 * @property string $description
 */
class ControllerAction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii_actions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['controllerID', 'action'], 'required'],
            [['controllerID'], 'integer'],
            [['action', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'controllerID' => Yii::t('common', 'Controller ID'),
            'action' => Yii::t('common', 'Action'),
            'description' => Yii::t('common', 'Description'),
        ];
    }
}
