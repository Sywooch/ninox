<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "banners_translations".
 *
 * @property integer $ID
 * @property integer $state
 * @property string $value
 * @property string $link
 * @property string $language
 */
class BannerTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'banners_translations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'language'], 'required'],
            [['ID', 'state'], 'integer'],
            [['value'], 'string'],
            [['link', 'language'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'state' => 'State',
            'value' => 'Value',
            'link' => 'Link',
            'language' => 'Language',
        ];
    }
}
