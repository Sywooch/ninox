<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "handmade_goods".
 *
 * @property integer $ID
 * @property string $ico
 * @property string $Code
 * @property string $Name
 * @property string $gabarity
 * @property integer $listorder
 * @property string $show_img
 * @property string $dateCreate
 * @property string $dateDisable
 * @property string $tovdate
 * @property string $orderDate
 * @property string $tovupdate
 * @property double $Ratio
 * @property double $Price
 * @property string $Description
 * @property integer $GroupID
 * @property string $customerID
 * @property string $d_photo1
 * @property string $d_photo2
 * @property string $link
 * @property double $rate
 */
class HandmadeItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'handmade_goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ico', 'gabarity', 'listorder', 'tovdate', 'tovupdate', 'd_photo1', 'd_photo2', 'link', 'rate'], 'required'],
            [['listorder', 'GroupID', 'customerID'], 'integer'],
            [['dateCreate', 'dateDisable', 'tovdate', 'orderDate', 'tovupdate'], 'safe'],
            [['Ratio', 'Price', 'rate'], 'number'],
            [['link'], 'string'],
            [['ico', 'Code', 'Name', 'gabarity'], 'string', 'max' => 255],
            [['show_img'], 'string', 'max' => 1],
            [['Description'], 'string', 'max' => 2550],
            [['d_photo1', 'd_photo2'], 'string', 'max' => 55],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('common', 'ID'),
            'ico' => Yii::t('common', 'Ico'),
            'Code' => Yii::t('common', 'Code'),
            'Name' => Yii::t('common', 'Name'),
            'gabarity' => Yii::t('common', 'Gabarity'),
            'listorder' => Yii::t('common', 'Listorder'),
            'show_img' => Yii::t('common', 'Show Img'),
            'dateCreate' => Yii::t('common', 'Date Create'),
            'dateDisable' => Yii::t('common', 'Date Disable'),
            'tovdate' => Yii::t('common', 'Tovdate'),
            'orderDate' => Yii::t('common', 'Order Date'),
            'tovupdate' => Yii::t('common', 'Tovupdate'),
            'Ratio' => Yii::t('common', 'Ratio'),
            'Price' => Yii::t('common', 'Price'),
            'Description' => Yii::t('common', 'Description'),
            'GroupID' => Yii::t('common', 'Group ID'),
            'customerID' => Yii::t('common', 'Customer ID'),
            'd_photo1' => Yii::t('common', 'D Photo1'),
            'd_photo2' => Yii::t('common', 'D Photo2'),
            'link' => Yii::t('common', 'Link'),
            'rate' => Yii::t('common', 'Rate'),
        ];
    }
}
