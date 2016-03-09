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

    public static function getList($option){
        $list = [];

        foreach(self::find()->where(['option' => $option])->each() as $item){
            $list[$item->id] = $item->value;
        }

        return $list;
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
