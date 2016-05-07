<?php

namespace common\models;

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
 * @property Category $category
 * @property GoodsPhoto $mainPhoto
 * @property GoodsPhoto[] $photos
 * @property string $photo
 * @property double $wholesalePrice
 * @property double $retailPrice
 * @property double $realWholesalePrice
 * @property double $realRetailPrice
 * @property string $categorycode
 * @property GoodTranslation $realTranslation
 */
class Good extends \yii\db\ActiveRecord
{

    const STATE_ENABLED = 1;
    const STATE_DISABLED = 0;

    private $_translation;


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations(){
        return $this->hasMany(GoodTranslation::className(), ['ID' => 'ID']);
    }

    public function getTranslation(){
        if(empty($this->_translation)){
            $this->_translation = $this->getTranslationByKey(\Yii::$app->language);
        }

        return $this->_translation;
    }

    public function getRealTranslation(){
        return $this->getTranslationByKeyReal(\Yii::$app->language);
    }

    public function getWholesalePrice(){
        return $this->PriceOut1;
    }

    public function getRetailPrice(){
        return $this->PriceOut2;
    }

    public function getTranslationByKeyReal($key){
        foreach($this->translations as $translation){
            if($translation->language == $key){
                return $translation;
            }
        }

        return new GoodTranslation([
            'ID'    =>  $this->ID
        ]);
    }

    /**
     * @param $key string
     * @return GoodTranslation
     */
    public function getTranslationByKey($key){
        $defaultLang = 'ru-RU';
        $defaultLangModel = new GoodTranslation();
        $currentLangModel = new GoodTranslation();
        foreach($this->translations as $translation){
            if($translation->language == $defaultLang){
                $defaultLangModel = $translation;
            }
            if($translation->language == $key){
                $currentLangModel = $translation;
            }
        }

        if($key != $defaultLang && !empty($defaultLangModel) && !empty($currentLangModel)){
            foreach($currentLangModel as $key => $value){
                $currentLangModel[$key] = empty($currentLangModel[$key]) ? $defaultLangModel[$key] : $value;
            }
        }

        return $currentLangModel;
    }

    /**
     * Возвращает оптовую цену товара
     *
     * @return float
     */
    public function getRealWholesalePrice(){
        return $this->PriceOut1;
    }

    /**
     * Возвращает розничную цену товара
     *
     * @return float
     */
    public function getRealRetailPrice(){
        return $this->PriceOut2;
    }

    /**
     * Возвращает главную фотографию товара
     *
     * @return string
     */
    public function getPhoto(){
        return $this->mainPhoto->ico;
    }

    /**
     * Возвращает модель главной фотографии товара
     *
     * @return string
     */
    public function getMainPhoto(){
        return isset($this->photos[0]) ? $this->photos[0] : new GoodsPhoto();
    }

    /**
     * Возвращает массив объектов фотографий товара
     *
     * @return GoodsPhoto[]
     */
    public function getPhotos(){
        return $this->hasMany(GoodsPhoto::className(), ['itemid' => 'ID'])->orderBy('order');
    }

    /**
     * Возвращает объект категории товара
     *
     * @return Category
     */
    public function getCategory(){
        return $this->hasOne(Category::className(), ['ID' => 'GroupID']);
    }

    /**
     * Возвращает код категории товара
     *
     * @return string
     */
    public function getCategorycode(){
        return $this->category->Code;
    }

    /**
     * @param $string
     * @param array $params
     * @deprecated Проверить на использование и удалить
     *
     * @return array|\yii\db\ActiveRecord[]
     */
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

    public function getName(){
        return $this->translation->name;
    }

    public function getLink(){
        return $this->translation->link;
    }

    public function getDescription(){
        return $this->translation->description;
    }

    public function getEnabled(){
        return $this->translation->enabled;
    }

    public function setEnabled($val){
        $this->realTranslation->enabled = $val;
    }

    public function setName($val){
        $this->realTranslation->name = $val;
    }

    public function beforeSave($insert){
        $this->realTranslation->save(false);

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
            [['listorder', 'otgruzka', 'otgruzka2', 'discountType', 'Type', 'IsRecipe', 'TaxGroup', 'IsVeryUsed', 'GroupID', 'old_id', 'Deleted', 'anotherCurrencyPeg', 'supplierId', 'garantyShow', 'yandexExport', 'originalGood', 'count', 'isUnlimited'], 'integer'],
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
