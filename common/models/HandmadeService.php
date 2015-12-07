<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "handmade_services".
 *
 * @property integer $id
 * @property string $customerID
 * @property string $title
 * @property string $description
 */
class HandmadeService extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'handmade_services';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customerID'], 'integer'],
            [['title', 'description'], 'required'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'customerID' => Yii::t('common', 'Customer ID'),
            'title' => Yii::t('common', 'Title'),
            'description' => Yii::t('common', 'Description'),
        ];
    }
}
