<?php

namespace frontend\modules\blog\models;

use Yii;

/**
 * This is the model class for table "redirects".
 *
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

    public static function getDb(){
        return Yii::$app->dbBlog;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['source'], 'required'],
            [['source', 'target'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'source' => 'Source',
            'target' => 'Target',
        ];
    }
}
