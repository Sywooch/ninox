<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "categoryPhotos".
 *
 * @property integer $categoryID
 * @property integer $order
 * @property string $photo
 */
class CategoryPhoto extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categoryPhotos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categoryID', 'order'], 'required'],
            [['categoryID', 'order'], 'integer'],
            [['photo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'categoryID' => 'Category ID',
            'order' => 'Order',
            'photo' => 'Photo',
        ];
    }
}
