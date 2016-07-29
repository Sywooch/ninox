<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "redirects".
 *
 * @property string $language
 * @property string $source
 * @property string $target
 */
class Redirect extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'redirects';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language', 'source'], 'required'],
            [['language'], 'string', 'max' => 5],
            [['source', 'target'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'language' => 'Language',
            'source' => 'Source',
            'target' => 'Target',
        ];
    }
}
