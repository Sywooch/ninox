<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "dopfoto".
 *
 * @property integer $id
 * @property integer $itemid
 * @property string $ico
 * @property integer $order
 */
class GoodsPhoto extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dopfoto';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'itemid' => 'Itemid',
            'ico' => 'Ico',
            'order' =>  'Order'
        ];
    }
}
