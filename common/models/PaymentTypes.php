<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "paytypes".
 *
 * @property integer $id
 * @property string $name
 * @property string $bank_name
 * @property string $bank_account
 * @property integer $enable
 */
class PaymentTypes extends \yii\db\ActiveRecord
{

    public static $paymentTypesArray = [];

    public static function getName($id){
        if(empty(self::$paymentTypesArray)){
            self::$paymentTypesArray = self::getPaymentTypes();
        }

        if(isset(self::$paymentTypesArray[$id])){
            return self::$paymentTypesArray[$id];
        }else{
            return $id;
        }
    }

    public static function getPaymentTypes(){
        if(empty(self::$paymentTypesArray)){
            self::$paymentTypesArray = PaymentTypes::find()->select(['name', 'id'])->asArray()->all();

            $t = [];
            foreach(self::$paymentTypesArray as $s){
                $t[$s['id']] = $s['name'];
            }

            self::$paymentTypesArray = $t;
        }

        return self::$paymentTypesArray;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'paytypes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['enable'], 'integer'],
            [['name', 'bank_name', 'bank_account'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'bank_name' => 'Bank Name',
            'bank_account' => 'Bank Account',
            'enable' => 'Enable',
        ];
    }
}
