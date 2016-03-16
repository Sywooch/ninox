<?php

namespace common\models;

use common\helpers\TranslitHelper;
use Yii;

/**
 * This is the model class for table "goods".
 *
 * @property integer $ID
 * @property string $Code
 * @property string $BarCode1
 * @property string $BarCode2
 * @property string $BarCode3
 * @property string $Catalog1
 * @property string $Catalog2
 * @property string $Catalog3
 * @property string $Name
 * @property string $Name2
 * @property string $dimensions
 * @property string $width
 * @property string $height
 * @property string $length
 * @property string $diameter
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
 * @property string $measure
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

    const STATE_ENABLED = 1;
    const STATE_DISABLED = 0;

    public $wholesale_price;
    public $wholesale_real_price;
    public $retail_price;
    public $retail_real_price;

    private $_photos = [];

    /**
     * @type Category
     */
    private $_category = null;

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

    public function getIco(){
        return GoodsPhoto::find()->select('ico')->where(['itemid' => $this->ID])->orderBy('order')->limit(1)->scalar();
    }

    public function getCategorycode(){
        return $this->category->Code;
    }

    /**
     * @return GoodsPhoto[]
     */
    public function getPhotos(){
        if(!empty($this->_photos)){
            return $this->_photos;
        }

        return $this->_photos = GoodsPhoto::find()->where(['itemid' => $this->ID])->orderBy('order')->all();
    }

    /**
     *
     * @return Category
     */
    public function getCategory(){
        if(empty($this->_category)){
            $this->_category = Category::findOne($this->GroupID);
        }

        return $this->_category;
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

    public function afterFind(){
        $this->wholesale_price = $this->PriceOut1;
        $this->wholesale_real_price = $this->PriceOut1;
        $this->retail_price = $this->PriceOut2;
        $this->retail_real_price = $this->PriceOut2;

        $this->Description = htmlspecialchars_decode($this->Description);

        return parent::afterFind();
    }

    public function beforeSave($insert){
        if($this->isNewRecord || $this->oldAttributes['Name'] != $this->Name){
            $this->link = TranslitHelper::to($this->Name);
        }

        $this->Description = htmlspecialchars($this->Description);

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
            [['dimensions', 'width', 'height', 'length', 'diameter', 'listorder', 'otkl_time', 'vkl_time', 'tovdate', 'tovupdate', 'photodate', 'otgruzka', 'otgruzka_time', 'p_photo', 'link', 'rate', 'originalGood', 'video'], 'required'],
            [['listorder', 'otgruzka', 'show_img', 'otgruzka2', 'discountType', 'Type', 'IsRecipe', 'TaxGroup', 'IsVeryUsed', 'GroupID', 'old_id', 'Deleted', 'anotherCurrencyPeg', 'supplierId', 'garantyShow', 'yandexExport', 'originalGood', 'count', 'isUnlimited'], 'integer'],
            [['otkl_time', 'vkl_time', 'tovdate', 'orderDate', 'tovupdate', 'photodate', 'otgruzka_time', 'otgruzka_time2'], 'safe'],
            [['Ratio', 'PriceIn', 'PriceOut1', 'PriceOut2', 'PriceOut3', 'PriceOut4', 'PriceOut5', 'PriceOut6', 'PriceOut7', 'PriceOut8', 'PriceOut9', 'PriceOut10', 'discountSize', 'MinQtty', 'NormalQtty', 'rate', 'anotherCurrencyValue'], 'number'],
            [['link'], 'string'],
            [['Code', 'BarCode1', 'BarCode2', 'BarCode3', 'Catalog1', 'Catalog2', 'Catalog3', 'Name', 'Name2', 'dimensions', 'measure', 'Measure2', 'anotherCurrencyTag', 'video'], 'string', 'max' => 255],
            [['width', 'height', 'length', 'diameter'], 'string', 'max' => 20],
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
            'ID' => 'ID',
            'Code' => 'Code',
            'BarCode1' => 'Bar Code1',
            'BarCode2' => 'Bar Code2',
            'BarCode3' => 'Bar Code3',
            'Catalog1' => 'Catalog1',
            'Catalog2' => 'Catalog2',
            'Catalog3' => 'Catalog3',
            'Name' => 'Name',
            'Name2' => 'Name2',
            'dimensions' => 'Dimensions',
            'width' => 'Width',
            'height' => 'Height',
            'length' => 'Length',
            'diameter' => 'Diameter',
            'listorder' => 'Listorder',
            'show_img' => 'Show Img',
            'otkl_time' => 'Otkl Time',
            'vkl_time' => 'Vkl Time',
            'tovdate' => 'Tovdate',
            'orderDate' => 'Order Date',
            'tovupdate' => 'Tovupdate',
            'photodate' => 'Photodate',
            'otgruzka' => 'Otgruzka',
            'otgruzka_time' => 'Otgruzka Time',
            'otgruzka2' => 'Otgruzka2',
            'otgruzka_time2' => 'Otgruzka Time2',
            'measure' => 'Measure',
            'Measure2' => 'Measure2',
            'Ratio' => 'Ratio',
            'num_opt' => 'Num Opt',
            'PriceIn' => 'Price In',
            'PriceOut1' => 'Price Out1',
            'PriceOut2' => 'Price Out2',
            'PriceOut3' => 'Price Out3',
            'PriceOut4' => 'Price Out4',
            'PriceOut5' => 'Price Out5',
            'PriceOut6' => 'Price Out6',
            'PriceOut7' => 'Price Out7',
            'PriceOut8' => 'Price Out8',
            'PriceOut9' => 'Price Out9',
            'PriceOut10' => 'Price Out10',
            'discountSize' => 'Discount Size',
            'discountType' => 'Discount Type',
            'MinQtty' => 'Min Qtty',
            'NormalQtty' => 'Normal Qtty',
            'Description' => 'Description',
            'Type' => 'Type',
            'IsRecipe' => 'Is Recipe',
            'TaxGroup' => 'Tax Group',
            'IsVeryUsed' => 'Is Very Used',
            'GroupID' => 'Group ID',
            'p_photo' => 'P Photo',
            'old_id' => 'Old ID',
            'Deleted' => 'Deleted',
            'link' => 'Link',
            'rate' => 'Rate',
            'anotherCurrencyPeg' => 'Another Currency Peg',
            'anotherCurrencyValue' => 'Another Currency Value',
            'anotherCurrencyTag' => 'Another Currency Tag',
            'supplierId' => 'Supplier ID',
            'garantyShow' => 'Garanty Show',
            'yandexExport' => 'Yandex Export',
            'originalGood' => 'Original Good',
            'video' => 'Video',
            'count' => 'Count',
            'isUnlimited' => 'Is Unlimited',
        ];
    }
}
