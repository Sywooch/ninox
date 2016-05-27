<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "exchanges".
 *
 * @property string $date
 * @property integer $summ
 */
class MoneyExchange extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'exchanges';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'required'],
            [['date'], 'safe'],
            [['summ'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'date' => 'Date',
            'summ' => 'Summ',
        ];
    }
}
