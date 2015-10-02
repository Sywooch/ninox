<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "dopfoto".
 *
 * @property integer $id
 * @property integer $itemid
 * @property string $ico
 */
class GoodsPhoto extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            'LoggableBehavior' => [
                'class' => 'sammaye\audittrail\LoggableBehavior',
                'options'   =>  [
                    'alternateKey'      =>  'itemid',
                    'discardSaveCreate' =>  true,
                    'field'             =>  'additionalPhoto',
                    'model'             =>  Good::className()
                ],
                'ignored' => [
                    'itemid',
                    'id',
                    ''
                ],
            ]
        ];
    }

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
        ];
    }
}
