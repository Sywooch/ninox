<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "goodsoptions_values".
 *
 * @property integer $option
 * @property integer $good
 * @property integer $value
 */
class GoodOptionsValue extends \yii\db\ActiveRecord
{

    public function getGoodOptions(){
        return $this->hasOne(GoodOptions::className(), ['id' => 'option'])->joinWith('optionVariants');
    }

    public function getGoodOptionsVariants(){
        return $this->hasOne(GoodOptionsVariant::className(), ['id' => 'value']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goodsoptions_values';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['option', 'good', 'value'], 'required'],
            [['option', 'good', 'value'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'option' => 'Option',
            'good' => 'Good',
            'value' => 'Value',
        ];
    }
}
