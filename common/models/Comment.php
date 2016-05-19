<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "comments".
 *
 * @property integer $id
 * @property string $comment
 * @property string $model
 * @property string $modelID
 * @property string $stamp
 * @property integer $userID
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment'], 'string'],
            [['stamp'], 'default', 'value' => date('Y-m-d H:i:s')],
            [['model', 'modelID', 'stamp'], 'required'],
            [['stamp'], 'safe'],
            [['userID'], 'integer'],
            [['model', 'modelID'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'comment' => 'Comment',
            'model' => 'Model',
            'modelID' => 'Model ID',
            'stamp' => 'Stamp',
            'userID' => 'User ID',
        ];
    }

    public function getCommenter(){
        return $this->hasOne(Siteuser::className(), ['id' => 'userID']);
    }
}
