<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "social_profiles".
 *
 * @property string $customerID
 * @property integer $social_id_vk
 * @property integer $social_id_ok
 * @property integer $social_id_fb
 * @property integer $social_id_gplus
 * @property integer $social_id_yandex
 * @property string $social_photo
 * @property string $social_gender
 * @property string $social_name
 */
class SocialProfile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'social_profiles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customerID', 'social_id_vk', 'social_id_ok', 'social_id_fb', 'social_id_gplus', 'social_id_yandex'], 'integer'],
            [['social_photo', 'social_gender', 'social_name'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customerID' => Yii::t('common', 'Customer ID'),
            'social_id_vk' => Yii::t('common', 'Social Id Vk'),
            'social_id_ok' => Yii::t('common', 'Social Id Ok'),
            'social_id_fb' => Yii::t('common', 'Social Id Fb'),
            'social_id_gplus' => Yii::t('common', 'Social Id Gplus'),
            'social_id_yandex' => Yii::t('common', 'Social Id Yandex'),
            'social_photo' => Yii::t('common', 'Social Photo'),
            'social_gender' => Yii::t('common', 'Social Gender'),
            'social_name' => Yii::t('common', 'Social Name'),
        ];
    }
}
