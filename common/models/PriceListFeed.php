<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "priceListFeeds".
 *
 * @property integer $id
 * @property string $name
 * @property string $categories
 * @property integer $format
 * @property integer $creator
 * @property integer $published
 */
class PriceListFeed extends \yii\db\ActiveRecord
{

    const FORMAT_YML = '1';
    const FORMAT_XML = '2';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'priceListFeeds';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categories'], 'string'],
            [['format', 'creator', 'published'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'name' => Yii::t('common', 'Name'),
            'categories' => Yii::t('common', 'Categories'),
            'format' => Yii::t('common', 'Format'),
            'creator' => Yii::t('common', 'Creator'),
            'published' => Yii::t('common', 'Published'),
        ];
    }
}
