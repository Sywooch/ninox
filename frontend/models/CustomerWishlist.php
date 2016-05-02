<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "customersWishlist".
 *
 * @property integer $itemID
 * @property string $customerID
 * @property string $date
 * @property double $price
 */
class CustomerWishlist extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customersWishlist';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['itemID', 'customerID'], 'required'],
            [['itemID', 'customerID'], 'integer'],
            [['date'], 'safe'],
            [['price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'itemID' => 'Item ID',
            'customerID' => 'Customer ID',
            'date' => 'Date',
            'price' => 'Price',
        ];
    }
}
