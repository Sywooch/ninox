<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pagetype".
 *
 * @property integer $id
 * @property string $page
 */
class Pagetype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pagetype';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'page'], 'required'],
            [['id'], 'integer'],
            [['page'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'page' => 'Page',
        ];
    }
}
