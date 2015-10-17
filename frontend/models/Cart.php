<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "cart".
 *
 * @property integer $id
 * @property integer $userID
 * @property integer $good
 * @property integer $count
 * @property string $cartCode
 * @property integer $goodId
 * @property string $date
 */
class Cart extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cart';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userID', 'good', 'count', 'cartCode', 'goodId', 'date'], 'required'],
            [['userID', 'good', 'count', 'goodId'], 'integer'],
            [['date'], 'safe'],
            [['cartCode'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userID' => 'User ID',
            'good' => 'Good',
            'count' => 'Count',
            'cartCode' => 'Cart Code',
            'goodId' => 'Good ID',
            'date' => 'Date',
        ];
    }
}
