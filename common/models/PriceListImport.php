<?php

namespace common\models;

use Yii;
use yii\helpers\Json;

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
    public function init(){
        if(empty($this->configuration)){
            $this->configuration = [];
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'priceListsImport';
    }

    public function beforeSave($insert)
    {
        if($this->isNewRecord){
            $this->creator = \Yii::$app->user->id;
            $this->created = date('Y-m-d H:i:s');
        }

        $this->configuration = Json::encode($this->configuration);

        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        if(!is_array($this->configuration)){
            $this->configuration = !empty($this->configuration) ? Json::decode($this->configuration) : [];
        }

        parent::afterFind();
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
