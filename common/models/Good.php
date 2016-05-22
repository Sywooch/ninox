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
    private $_realTranslation;
    private $_realTranslationFinded = false;

    public function init(){
        if($this->isNewRecord){
            $this->realTranslation = new GoodTranslation([
                'ID'        =>  $this->ID,
                'language'  =>  'ru-RU'
            ]);
        }
    }

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
        if(empty($this->_realTranslation) || !$this->_realTranslationFinded){
            $this->_realTranslation = $this->getTranslationByKeyReal(\Yii::$app->language);
        }

        return $this->_realTranslation;
    }

    public function setRealTranslation($val){
        $this->_realTranslation = $val;
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
        return htmlspecialchars_decode($this->translation->name);
    }

    public function getLink(){
        return $this->translation->link;
    }

    public function getDescription(){
        return htmlspecialchars_decode($this->translation->description);
    }

    public function getEnabled(){
        return $this->translation->enabled;
    }

    public function setDescription($val){
        $this->realTranslation->description = $val;
    }

    public function setName($val){
        $this->realTranslation->name = $val;
    }

    public function setEnabled($val){
        $this->realTranslation->enabled = $val;
    }

    public function getCode(){
        $needleZeros = 7 - strlen($this->ID);
        $code = $this->ID;

        for($i = 1; $i < $needleZeros; $i++){
            $code = "0".$code;
        }

        return "2".$code;
    }

    public function getBarcode(){
        $code = str_pad($this->code, 12, "0", STR_PAD_LEFT);

        $sum = 0;

        for($i = (strlen($code) - 1); $i >= 0; $i--){
            $sum += (($i % 2) * 2 + 1) * $code[$i];
        }

        if(($sum % 10) != 0){
            $checksum = (10 - ($sum % 10));
        }else{
            $checksum = 0;
        }

        return $this->code.$checksum;
    }

    public function beforeSave($insert){
        if($this->isNewRecord){
            if(empty($this->ID)){
                if(empty($this->realTranslation->ID)){
                    $maxTranslationID = (GoodTranslation::find()->where(['language' => 'ru-RU'])->max("ID") + 1);
                    $maxID = (Good::find()->max("ID") + 1);
                    $this->ID = $maxID > $maxTranslationID ? $maxID : $maxTranslationID;

                    $this->realTranslation->ID = $this->ID;
                }else{

                    $this->ID = $this->realTranslation->ID;
                }
            }

            if(empty($this->Code)){
                $this->Code = $this->getCode();
            }

            if(empty($this->BarCode1)){
                $this->BarCode1 = $this->getBarcode();
            }
        }

        if(empty($this->photos)){
            GoodTranslation::updateAll(['enabled' => 0], ['ID' => $this->ID]);
        }

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        if($this->realTranslation->isNewRecord){
            $this->realTranslation->ID = $this->ID;
        }

        $this->realTranslation->save(false);

        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
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
            [['dimensions'], 'default', 'vaule' => ''],
            [['Code', 'BarCode1', 'BarCode2', 'BarCode3', 'Catalog1', 'Catalog2', 'Catalog3', 'Name', 'Name2', 'dimensions', 'measure', 'Measure2', 'anotherCurrencyTag', 'video'], 'string', 'max' => 255],
            [['width', 'height', 'length', 'diameter'], 'string', 'max' => 20],
            [['num_opt'], 'string', 'max' => 50],
            [['Description'], 'string', 'max' => 2550],
            [['p_photo'], 'string', 'max' => 55],
            [['num_opt'], 'default', 'value' => 1],
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
