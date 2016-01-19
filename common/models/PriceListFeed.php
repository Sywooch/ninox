<?php

namespace common\models;

use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "priceListFeeds".
 *
 * @property integer $id
 * @property string $name
 * @property array $categories
 * @property integer $format
 * @property integer $creator
 * @property integer $published
 */
class PriceListFeed extends \yii\db\ActiveRecord
{

    const FORMAT_YML = '1';
    const FORMAT_XML = '2';

    public function beforeSave($insert){
        $this->categories = Json::encode($this->categories);

        if($this->isNewRecord){
            $this->creator = \Yii::$app->user->identity->getId();
        }

        return parent::beforeSave($insert);
    }

    public function afterFind(){
        $this->categories = Json::decode($this->categories);

        return parent::afterFind();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'priceListFeeds';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['format', 'creator', 'published'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'name' => Yii::t('common', 'Name'),
            'categories' => Yii::t('common', 'Categories'),
            'format' => Yii::t('common', 'Format'),
            'creator' => Yii::t('common', 'Creator'),
            'published' => Yii::t('common', 'Published'),
        ];
    }
}
