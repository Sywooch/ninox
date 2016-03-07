<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "goodsoptions".
 *
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property integer $deleted
 */
class GoodOptions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goodsoptions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['name'], 'string'],
            [['type', 'deleted'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'type' => 'Type',
            'deleted' => 'Deleted',
        ];
    }
}
