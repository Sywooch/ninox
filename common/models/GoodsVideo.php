<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "dopvideo".
 *
 * @property integer $ID
 * @property integer $goodID
 * @property string $video
 */
class GoodsVideo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dopvideo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goodID', 'video'], 'required'],
            [['goodID'], 'integer'],
            [['video'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'goodID' => 'Good ID',
            'video' => 'Video',
        ];
    }
}
