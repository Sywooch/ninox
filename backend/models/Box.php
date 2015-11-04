<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "boxes".
 *
 * @property integer $id
 * @property integer $volumeWeight
 * @property double $volumeGeneral
 * @property double $volumetricVolume
 * @property integer $volumetricWidth
 * @property integer $volumetricLength
 * @property integer $volumetricHeight
 */
class Box extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'boxes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['volumeWeight', 'volumetricWidth', 'volumetricLength', 'volumetricHeight'], 'integer'],
            [['volumeGeneral', 'volumetricVolume'], 'number'],
            ['volumeGeneral', 'compare', 'compareValue' => '0.0004', 'operator' => '>='],
            ['volumetricVolume', 'compare', 'compareValue' => '0.0004', 'operator' => '>='],
            ['volumetricVolume', 'compare', 'compareValue' => '0.0004', 'operator' => '>='],
            ['volumetricWidth', 'compare', 'compareValue' => 5, 'operator' => '>='],
            ['volumetricLength', 'compare', 'compareValue' => 5, 'operator' => '>='],
            ['volumetricHeight', 'compare', 'compareValue' => 5, 'operator' => '>='],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'volumeWeight' => 'Объёмный вес, кг',
            'volumeGeneral' => 'Объём общий, м. куб',
            'volumetricVolume' => 'Объём одного места, м. куб',
            'volumetricWidth' => 'Длина',
            'volumetricLength' => 'Ширина',
            'volumetricHeight' => 'Высота',
        ];
    }
}
