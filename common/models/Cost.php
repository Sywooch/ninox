<?php

namespace common\models;

use backend\models\User;
use Yii;

/**
 * This is the model class for table "costs".
 *
 * @property integer $id
 * @property string $date
 * @property integer $costId
 * @property double $costSumm
 * @property string $costComment
 * @property integer $creator
 */
class Cost extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'costs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date', 'costId', 'costComment'], 'required'],
            [['date'], 'safe'],
            [['costId', 'creator'], 'integer'],
            [['costSumm'], 'number'],
            [['costComment'], 'string'],
        ];
    }

    public function getType(){
        return $this->hasOne(CostsType::className(), ['id' => 'costId']);
    }

    public function getCreatorModel(){
        return $this->hasOne(User::className(), ['id' => 'creator']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Дата',
            'costId' => 'Cost ID',
            'creator' => 'создатель',
            'costSumm' => 'Сумма',
            'costComment' => 'Коментарий',
        ];
    }

    public function beforeSave($insert)
    {
        if($this->isNewRecord){
            $this->creator = \Yii::$app->user->id;
        }

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
