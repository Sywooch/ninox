<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "goodsoptions_variants".
 *
 * @property integer $id
 * @property integer $option
 * @property string $value
 * @property integer $deleted
 */
class GoodOptionsVariant extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goodsoptions_variants';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['option', 'deleted'], 'integer'],
            [['value'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'option' => 'Option',
            'value' => 'Value',
            'deleted' => 'Deleted',
        ];
    }
}
