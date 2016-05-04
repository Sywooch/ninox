<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "item_translations".
 *
 * @property integer $ID
 * @property string $language
 * @property integer $enabled
 * @property string $name
 * @property string $link
 * @property string $description
 *
 * @property Goods $goods
 */
class GoodTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item_translations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'language'], 'required'],
            [['ID', 'enabled'], 'integer'],
            [['language'], 'string', 'max' => 5],
            [['name', 'link', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'language' => 'Language',
            'enabled' => 'Enabled',
            'name' => 'Name',
            'link' => 'Link',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGoods()
    {
        return $this->hasOne(Goods::className(), ['ID' => 'ID']);
    }
}
