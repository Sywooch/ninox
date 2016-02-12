<?php

namespace common\models;

use common\helpers\TranslitHelper;
use Yii;

/**
 * This is the model class for table "goods".
 *
 * @property integer $ID
 * @property string $ico
 * @property string $Code
 * @property string $BarCode1
 * @property string $BarCode2
 * @property string $BarCode3
 * @property string $Catalog1
 * @property string $Catalog2
 * @property string $Catalog3
 * @property string $Name
 * @property string $Name2
 * @property string $gabarity
 * @property string $shyryna
 * @property string $vysota
 * @property string $dovgyna
 * @property string $dyametr
 * @property integer $listorder
 * @property string $show_img
 * @property string $otkl_time
 * @property string $vkl_time
 * @property string $tovdate
 * @property string $orderDate
 * @property string $tovupdate
 * @property string $photodate
 * @property integer $otgruzka
 * @property string $otgruzka_time
 * @property integer $otgruzka2
 * @property string $otgruzka_time2
 * @property string $Measure1
 * @property string $Measure2
 * @property double $Ratio
 * @property string $num_opt
 * @property double $PriceIn
 * @property double $PriceOut1
 * @property double $PriceOut2
 * @property double $PriceOut3
 * @property double $PriceOut4
 * @property double $PriceOut5
 * @property double $PriceOut6
 * @property double $PriceOut7
 * @property double $PriceOut8
 * @property double $PriceOut9
 * @property double $PriceOut10
 * @property double $discountSize
 * @property integer $discountType
 * @property double $MinQtty
 * @property double $NormalQtty
 * @property string $Description
 * @property integer $Type
 * @property integer $IsRecipe
 * @property integer $TaxGroup
 * @property integer $IsVeryUsed
 * @property integer $GroupID
 * @property string $p_photo
 * @property integer $old_id
 * @property integer $Deleted
 * @property string $link
 * @property double $rate
 * @property integer $anotherCurrencyPeg
 * @property double $anotherCurrencyValue
 * @property string $anotherCurrencyTag
 * @property integer $supplierId
 * @property integer $garantyShow
 * @property integer $yandexExport
 * @property integer $originalGood
 * @property string $video
 * @property integer $count
 * @property integer $isUnlimited
 */
class Good extends \yii\db\ActiveRecord
{

    public static function searchGoods($string, $params = []){
        if(empty($params) || $string == ''){
            return [];
        }

        $query = self::find()->select('a.*, b.Name as categoryname')->from([self::tableName().' a', Category::tableName().' b']);
        $terms = [];

        if(sizeof($params) > 1){
            $terms[] = 'or';
            foreach($params as $p){
                $terms[] = [
                    'like', 'a.'.$p, $string
                ];
            }
        }else{
            $terms = ['like', 'a.'.$params['0'], $string];
        }
        $query->where(['and', 'a.GroupID = b.ID', $terms]);

        $query->limit(10);

        return $query->asArray()->all();
    }

    public function behaviors()
    {
        return [
            'LoggableBehavior' => [
                'class' => 'sammaye\audittrail\LoggableBehavior',
                'ignored' => [
                    'Name2',
                    'ID'
                ],
            ]
        ];
    }

    public function beforeSave($insert){
        if($this->isNewRecord || $this->oldAttributes['Name'] != $this->Name){
            $this->link = TranslitHelper::to($this->Name);
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ico', 'gabarity', 'shyryna', 'vysota', 'dovgyna', 'dyametr', 'listorder', 'otkl_time', 'vkl_time', 'tovdate', 'tovupdate', 'photodate', 'otgruzka', 'otgruzka_time', 'p_photo', 'link', 'rate', 'originalGood', 'video'], 'required'],
            [['listorder', 'otgruzka', 'otgruzka2', 'discountType', 'Type', 'IsRecipe', 'TaxGroup', 'IsVeryUsed', 'GroupID', 'old_id', 'Deleted', 'anotherCurrencyPeg', 'supplierId', 'garantyShow', 'yandexExport', 'originalGood', 'count', 'isUnlimited'], 'integer'],
            [['otkl_time', 'vkl_time', 'tovdate', 'orderDate', 'tovupdate', 'photodate', 'otgruzka_time', 'otgruzka_time2'], 'safe'],
            [['Ratio', 'PriceIn', 'PriceOut1', 'PriceOut2', 'PriceOut3', 'PriceOut4', 'PriceOut5', 'PriceOut6', 'PriceOut7', 'PriceOut8', 'PriceOut9', 'PriceOut10', 'discountSize', 'MinQtty', 'NormalQtty', 'rate', 'anotherCurrencyValue'], 'number'],
            [['link'], 'string'],
            [['ico', 'Code', 'BarCode1', 'BarCode2', 'BarCode3', 'Catalog1', 'Catalog2', 'Catalog3', 'Name', 'Name2', 'gabarity', 'Measure1', 'Measure2', 'anotherCurrencyTag', 'video', ], 'string', 'max' => 255],
            [['shyryna', 'vysota', 'dovgyna', 'dyametr'], 'string', 'max' => 20],
            [['show_img'], 'string', 'max' => 1],
            [['num_opt'], 'string', 'max' => 50],
            [['Description'], 'string', 'max' => 2550],
            [['p_photo'], 'string', 'max' => 55],
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
            'BarCode1' => Yii::t('common', 'Bar Code1'),
            'BarCode2' => Yii::t('common', 'Bar Code2'),
            'BarCode3' => Yii::t('common', 'Bar Code3'),
            'Catalog1' => Yii::t('common', 'Catalog1'),
            'Catalog2' => Yii::t('common', 'Catalog2'),
            'Catalog3' => Yii::t('common', 'Catalog3'),
            'Name' => Yii::t('common', 'Name'),
            'Name2' => Yii::t('common', 'Name2'),
            'gabarity' => Yii::t('common', 'Gabarity'),
            'shyryna' => Yii::t('common', 'Shyryna'),
            'vysota' => Yii::t('common', 'Vysota'),
            'dovgyna' => Yii::t('common', 'Dovgyna'),
            'dyametr' => Yii::t('common', 'Dyametr'),
            'listorder' => Yii::t('common', 'Listorder'),
            'show_img' => Yii::t('common', 'Show Img'),
            'otkl_time' => Yii::t('common', 'Otkl Time'),
            'vkl_time' => Yii::t('common', 'Vkl Time'),
            'tovdate' => Yii::t('common', 'Tovdate'),
            'orderDate' => Yii::t('common', 'Order Date'),
            'tovupdate' => Yii::t('common', 'Tovupdate'),
            'photodate' => Yii::t('common', 'Photodate'),
            'otgruzka' => Yii::t('common', 'Otgruzka'),
            'otgruzka_time' => Yii::t('common', 'Otgruzka Time'),
            'otgruzka2' => Yii::t('common', 'Otgruzka2'),
            'otgruzka_time2' => Yii::t('common', 'Otgruzka Time2'),
            'Measure1' => Yii::t('common', 'Measure1'),
            'Measure2' => Yii::t('common', 'Measure2'),
            'Ratio' => Yii::t('common', 'Ratio'),
            'num_opt' => Yii::t('common', 'Num Opt'),
            'PriceIn' => Yii::t('common', 'Price In'),
            'PriceOut1' => Yii::t('common', 'Price Out1'),
            'PriceOut2' => Yii::t('common', 'Price Out2'),
            'PriceOut3' => Yii::t('common', 'Price Out3'),
            'PriceOut4' => Yii::t('common', 'Price Out4'),
            'PriceOut5' => Yii::t('common', 'Price Out5'),
            'PriceOut6' => Yii::t('common', 'Price Out6'),
            'PriceOut7' => Yii::t('common', 'Price Out7'),
            'PriceOut8' => Yii::t('common', 'Price Out8'),
            'PriceOut9' => Yii::t('common', 'Price Out9'),
            'PriceOut10' => Yii::t('common', 'Price Out10'),
            'discountSize' => Yii::t('common', 'Discount Size'),
            'discountType' => Yii::t('common', 'Discount Type'),
            'MinQtty' => Yii::t('common', 'Min Qtty'),
            'NormalQtty' => Yii::t('common', 'Normal Qtty'),
            'Description' => Yii::t('common', 'Description'),
            'Type' => Yii::t('common', 'Type'),
            'IsRecipe' => Yii::t('common', 'Is Recipe'),
            'TaxGroup' => Yii::t('common', 'Tax Group'),
            'IsVeryUsed' => Yii::t('common', 'Is Very Used'),
            'GroupID' => Yii::t('common', 'Group ID'),
            'p_photo' => Yii::t('common', 'P Photo'),
            'old_id' => Yii::t('common', 'Old ID'),
            'Deleted' => Yii::t('common', 'Deleted'),
            'link' => Yii::t('common', 'Link'),
            'rate' => Yii::t('common', 'Rate'),
            'anotherCurrencyPeg' => Yii::t('common', 'Another Currency Peg'),
            'anotherCurrencyValue' => Yii::t('common', 'Another Currency Value'),
            'anotherCurrencyTag' => Yii::t('common', 'Another Currency Tag'),
            'supplierId' => Yii::t('common', 'Supplier ID'),
            'garantyShow' => Yii::t('common', 'Garanty Show'),
            'yandexExport' => Yii::t('common', 'Yandex Export'),
            'originalGood' => Yii::t('common', 'Original Good'),
            'video' => Yii::t('common', 'Video'),
            'count' => Yii::t('common', 'Count'),
            'isUnlimited' => Yii::t('common', 'Is Unlimited'),
        ];
    }
}
