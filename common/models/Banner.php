<?php

namespace common\models;

use Yii;
use yii\base\InvalidConfigException;

/**
 * This is the model class for table "banners".
 *
 * @property integer $id
 * @property integer $bannerTypeId
 * @property string $banner
 * @property string $link
 * @property string $type
 * @property string $date
 * @property integer $state
 * @property string $categoryCode
 * @property string $bg
 * @property integer $bannerOrder
 * @property string $dateStart
 * @property string $dateEnd
 */
class Banner extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'banners';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bannerTypeId', 'banner', 'link', 'type', 'date', 'categoryCode', 'bg', 'dateStart', 'dateEnd'], 'required'],
            [['bannerTypeId', 'state', 'bannerOrder'], 'integer'],
            [['banner', 'link'], 'string'],
            [['date', 'dateStart', 'dateEnd'], 'safe'],
            [['type'], 'string', 'max' => 255],
            [['categoryCode'], 'string', 'max' => 50],
            [['bg'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'bannerTypeId'  => 'Категория',
            'banner'        => 'Баннер',
            'link'          => 'Ссылка',
            'type'          => 'Тип',
            'date'          => 'Дата',
            'state'         => 'Включен',
            'categoryCode'  => 'Код категории',
            'bg'            => 'Фон',
            'bannerOrder'   => 'Порядок сортировки',
            'dateStart'     => 'Показывать с',
            'dateEnd'       => 'Показывать до',
        ];
    }
}
