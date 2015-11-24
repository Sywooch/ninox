<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "yii_controllers".
 *
 * @property integer $id
 * @property string $controller
 */
class Controller extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii_controllers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['controller'], 'required'],
            [['controller'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'controller' => Yii::t('common', 'Controller'),
        ];
    }
}
