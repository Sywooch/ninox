<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pricerules".
 *
 * @property integer $ID
 * @property string $Name
 * @property string $Formula
 * @property integer $Enabled
 * @property integer $Priority
 */
class Pricerule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pricerules';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Enabled', 'Priority'], 'integer'],
            [['Name'], 'string', 'max' => 255],
            [['Formula'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'Name' => 'Name',
            'Formula' => 'Formula',
            'Enabled' => 'Enabled',
            'Priority' => 'Priority',
        ];
    }
}
