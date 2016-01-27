<?php

namespace common\models;

use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "subDomains".
 *
 * @property integer $id
 * @property string $name
 * @property integer $autologin
 * @property string $autologinParams
 * @property integer $cashboxId
 */
class SubDomain extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subDomains';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'autologinParams'], 'required'],
            [['autologin', 'cashboxId'], 'integer'],
            [['autologinParams'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function beforeSave($insert){
        $this->autologinParams = Json::encode($this->autologinParams);

        return parent::beforeSave($insert);
    }

    public function afterFind(){
        $this->autologinParams = Json::decode($this->autologinParams);

        return parent::afterFind();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'name' => Yii::t('common', 'Name'),
            'autologin' => Yii::t('common', 'Autologin'),
            'autologinParams' => Yii::t('common', 'Autologin Params'),
            'cashboxId' => Yii::t('common', 'Cashbox ID'),
        ];
    }
}
