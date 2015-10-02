<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "deliverytypes".
 *
 * @property integer $id
 * @property string $name
 * @property integer $enable
 */
class DeliveryTypes extends \yii\db\ActiveRecord
{

    public static $deliveryTypesArray = [];

    public static function getName($id){
        if(empty(self::$deliveryTypesArray)){
            self::$deliveryTypesArray = self::getDeliveryTypes();
        }

        $m = DeliveryTypes::find()->select("name")->where(['id' => $id])->scalar();

        return empty($m) ? $id : $m;
    }

    public static function getDeliveryTypes(){
        if(!empty(self::$deliveryTypesArray)){
            return self::$deliveryTypesArray;
        }

        self::$deliveryTypesArray = self::find()->select('id, name')->asArray()->all();

        $t = [];

        foreach(self::$deliveryTypesArray as $s){
            $t[$s['id']] = $s['name'];
        }

        self::$deliveryTypesArray = $t;

        return self::$deliveryTypesArray;
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'deliverytypes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['enable'], 'integer'],
            [['name'], 'string', 'max' => 255]
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
            'enable' => 'Enable',
        ];
    }
}
