<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "send_pay".
 *
 * @property integer $id
 * @property string $nomer_id
 * @property string $summ
 * @property string $data_oplaty
 * @property string $kvitanciya
 * @property string $sposoboplaty
 * @property integer $read_confirm
 */
class CustomerOrderPayment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'send_pay';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nomer_id', 'summ', 'data_oplaty', 'kvitanciya', 'sposoboplaty'], 'required'],
            [['read_confirm'], 'integer'],
            [['nomer_id', 'summ', 'data_oplaty'], 'string', 'max' => 10],
            [['kvitanciya'], 'string', 'max' => 255],
            [['sposoboplaty'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nomer_id' => 'Nomer ID',
            'summ' => 'Summ',
            'data_oplaty' => 'Data Oplaty',
            'kvitanciya' => 'Kvitanciya',
            'sposoboplaty' => 'Sposoboplaty',
            'read_confirm' => 'Read Confirm',
        ];
    }
}
