<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "banners".
 *
 * @property integer $ID
 * @property integer $category
 * @property integer $type
 * @property string $added
 * @property integer $order
 * @property string $dateFrom
 * @property string $dateTo
 * @property integer $deleted
 *
 * @property BannersTranslations $iD
 */
class Banner extends \yii\db\ActiveRecord
{

    const TYPE_IMAGE = 1;
    const TYPE_HTML = 2;
    const TYPE_GOOD = 3;

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
            [['category', 'added'], 'required'],
            [['category', 'type', 'order', 'deleted'], 'integer'],
            [['added', 'dateFrom', 'dateTo'], 'safe'],
            [['ID'], 'exist', 'skipOnError' => true, 'targetClass' => BannerTranslation::className(), 'targetAttribute' => ['ID' => 'ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'category' => 'Category',
            'type' => 'Type',
            'added' => 'Added',
            'order' => 'Order',
            'dateFrom' => 'Date From',
            'dateTo' => 'Date To',
            'deleted' => 'Deleted',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBanner()
    {
        return $this->hasOne(BannerTranslation::className(), ['ID' => 'ID'])->where(['language' => \Yii::$app->language]);
    }
}
