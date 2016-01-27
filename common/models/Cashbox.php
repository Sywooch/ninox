<?php

namespace common\models;

use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "cashbox".
 *
 * @property integer $ID
 * @property string $autologin
 * @property string $name
 * @property string $created
 * @property string $lastOrder
 * @property integer $lastManager
 * @property integer $defaultCustomer
 * @property integer $defaultWholesaleCustomer
 */
class Cashbox extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cashbox';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created', 'lastOrder'], 'safe'],
            [['lastManager', 'defaultCustomer', 'defaultWholesaleCustomer'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('common', 'ID'),
            'name' => Yii::t('common', 'Name'),
            'created' => Yii::t('common', 'Created'),
            'lastOrder' => Yii::t('common', 'Last Order'),
            'lastManager' => Yii::t('common', 'Last Manager'),
            'defaultCustomer' => Yii::t('common', 'Default Customer'),
            'defaultWholesaleCustomer' => Yii::t('common', 'Default Wholesale Customer'),
        ];
    }
}
