<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "priceListsImport".
 *
 * @property integer $id
 * @property string $name
 * @property string $file
 * @property string $format
 * @property string $created
 * @property integer $creator
 * @property integer $imported
 * @property string $importedDate
 * @property string $configuration
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
            [['created', 'importedDate'], 'safe'],
            [['creator', 'imported'], 'integer'],
            [['configuration'], 'string'],
            [['name', 'file', 'format'], 'string', 'max' => 255],
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
            'file' => 'File',
            'format' => 'Format',
            'created' => 'Created',
            'creator' => 'Creator',
            'imported' => 'Imported',
            'importedDate' => 'Imported Date',
            'configuration' => 'Configuration',
        ];
    }
}
