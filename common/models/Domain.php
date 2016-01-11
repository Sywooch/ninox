<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "domains".
 *
 * @property integer $id
 * @property string $name
 * @property string $currencyCode
 * @property string $currencyShortName
 * @property double $currencyExchange
 * @property integer $coins
 */
class Domain extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'domains';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['currencyExchange'], 'number'],
            [['coins'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['currencyCode'], 'string', 'max' => 3],
            [['currencyShortName'], 'string', 'max' => 5],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID домена'),
            'name' => Yii::t('common', 'Имя домена'),
            'currencyCode' => Yii::t('common', 'Код валюты'),
            'currencyShortName' => Yii::t('common', 'Сокращенное название валюты'),
            'currencyExchange' => Yii::t('common', 'Currency Exchange'),
            'coins' => Yii::t('common', 'Coins'),
        ];
    }
}
