<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "costs".
 *
 * @property integer $id
 * @property string $date
 * @property integer $costId
 * @property double $costSumm
 * @property string $costComment
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
            [['costId'], 'integer'],
            [['costSumm'], 'number'],
            [['costComment'], 'string'],
        ];
    }

    public function getType(){
        return $this->hasOne(CostsType::className(), ['id' => 'costId']);
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
            'costSumm' => 'Сумма',
            'costComment' => 'Коментарий',
        ];
    }
}
