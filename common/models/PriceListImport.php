<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "priceListsImport".
 *
 * @property integer $id
 * @property string $file
 * @property string $name
 * @property string $format
 * @property string $created
 * @property integer $creator
 * @property integer $imported
 * @property string $columns
 */
class PriceListImport extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'priceListsImport';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created'], 'safe'],
            [['creator', 'imported'], 'integer'],
            [['columns'], 'string'],
            [['file', 'format', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file' => 'File',
            'name' => 'Name',
            'format' => 'Format',
            'created' => 'Created',
            'creator' => 'Creator',
            'imported' => 'Imported',
            'columns' => 'Columns',
        ];
    }
}
