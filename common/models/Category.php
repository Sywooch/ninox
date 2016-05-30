<?php

namespace common\models;

use common\helpers\TranslitHelper;
use Yii;
use yii\db\Query;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "goodsgroups".
 *
 * @property integer $ID
 * @property string $Name
 * @property string $Code
 * @property string $p_photo
 * @property string $link
 * @property string $text2
 * @property string $title
 * @property string $titlenew
 * @property string $titleasc
 * @property string $titledesc
 * @property string $descr
 * @property string $keyword
 * @property string $cat_img
 * @property integer $listorder
 * @property boolean $canBuy
 * @property boolean $onePrice
 * @property boolean $hasFilter
 * @property integer $enabled
 * @property string $viewFile
 * @property string $viewOptions
 * @property string $yandexName
 * @property string $catNameVinitelny
 * @property string $h1asc
 * @property string $h1desc
 * @property string $h1new
 * @property string $h1
 * @property string $catNameVinitelny2
 * @property integer $ymlExport
 * @property Category[]|null $parents
 * @property CategoryTranslation translation
 * @property CategoryTranslation[] translations
 */
class Category extends \yii\db\ActiveRecord
{

    //var $parentCategory;
	protected $items;

    private $_translation;
    private $parents = [];
    private $goodsCount = null;
    private $goodsCountSubcategories = null;

    public function getTranslations(){
        return $this->hasMany(CategoryTranslation::className(), ['ID' => 'ID']);
    }

    public function getPhotos(){
        return $this->hasMany(CategoryPhoto::className(), ['categoryID' => 'ID'])->orderBy('order');
    }

    public function getParents(){
        if(!empty($this->parents)){
            return $this->parents;
        }

        $this->parents = self::getParentCategories($this->Code);

        return $this->parents;
    }

    public function getTranslation(){
        if(empty($this->_translation)){
            $this->_translation = $this->getTranslationByKey(\Yii::$app->language);
        }

        return $this->_translation;
    }

    public function getTranslationByKey($key){
        $defaultLang = 'ru-RU';
        $defaultLangModel = new CategoryTranslation();
        $currentLangModel = new CategoryTranslation();
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
                $currentLangModel[$key] = $currentLangModel[$key] === '' ? $defaultLangModel[$key] : $value;
            }
        }

        return $currentLangModel;
    }


    /**
     * @return array Список категорий по их
     */
    public static function getList(){
        $categories = self::find()->with('translations')->all();
        $result = [];
        $name = "";

        foreach($categories as $category){
            if(strlen($category->Code) == 3){
                $name = $category->name;
            }
            $result[$name][$category->ID] = $category->name;
        }

        return $result;
    }

    public static function getParentsCodes($code){
        $codes = [];

        while(strlen($code) > '3'){
            $code = substr($code, 0, -3);
            $codes[] = $code;
        }

        return $codes;
    }

    public static function getParentCategories($code){
        $codes = self::getParentsCodes($code);

        $categories = self::find()->where([
            'in', 'Code', $codes
        ])->all();

        return $categories;
    }

    public function getTitleasc(){
        return $this->translation->titleOrderAscending;
    }

    public function getTitledesc(){
        return $this->translation->titleOrderDescending;
    }

    public function getTitlenew(){
        return $this->translation->titleOrderNew;
    }

    public function getH1(){
        return $this->translation->header;
    }

    public function getH1new(){
        return $this->translation->headerOrderNew;
    }

    public function getH1desc(){
        return $this->translation->headerOrderDescending;
    }

    public function getH1asc(){
        return $this->translation->headerOrderAscending;
    }

    public function getDescr(){
        return $this->translation->metaDescription;
    }

    public function getText2(){
        return $this->translation->categoryDescription;
    }

    public function getName(){
        return $this->translation->Name;
    }

    public function setName($val){
        return $this->translation->Name = $val;
    }

    public function getLink(){
        return $this->translation->link;
    }

    public function setLink($value){
        $this->translation->link = $value;
    }

    public function getTitle(){
        return $this->translation->title;
    }

    public function setTitle($value){
        $this->translation->title = $value;
    }

    public function getTitleOrderAscending(){
        return $this->translation->titleOrderAscending;
    }

    public function getTitleOrderDescending(){
        return $this->translation->titleOrderDescending;
    }

    public function getTitleOrderNew(){
        return $this->translation->titleOrderNew;
    }

    public function getHeader(){
        return $this->translation->header;
    }

    public function getHeaderOrderAscending(){
        return $this->translation->headerOrderAscending;
    }

    public function getHeaderOrderDescending(){
        return $this->translation->headerOrderDescending;
    }

    public function getHeaderOrderNew(){
        return $this->translation->headerOrderNew;
    }

    public function getDescription(){
        return htmlspecialchars_decode($this->translation->categoryDescription);
    }

    public function setDescription($value){
        $this->translation->categoryDescription = htmlspecialchars($value);
    }

    public function getMetaDescription(){
        return htmlspecialchars_decode($this->translation->metaDescription);
    }

    public function setMetaDescription($value){
        $this->translation->metaDescription = htmlspecialchars($value);
    }

    public function getEnabled(){
        return empty($this->translation->enabled) ? 0 : $this->translation->enabled;
    }

    public function setEnabled($value){
        $this->translation->enabled = $value;
    }

    public function getSequence(){
        return empty($this->translation->sequence) ? 0 : $this->translation->sequence;
    }

    public function getPhoneNumber(){
        return empty($this->translation->phoneNumber) ? \Yii::$app->params['categoryPhoneNumber'] : $this->translation->phoneNumber;
    }

    public function getKeywords(){
        return $this->translation->metaKeywords;
    }

    public function setKeywords($val){
        $this->translation->metaKeywords = $val;
    }

    /**
     * @param $link string
     * @return Category
     * @throws NotFoundHttpException
     */
    public static function findByLink($link){
        $category = self::find()
            ->joinWith(['translations'])
            ->where([CategoryTranslation::tableName().'.link' => $link])
            ->one();
        if(empty($category)){
            throw new NotFoundHttpException("Категория по ссылке {$link} не найдена!");
        }

        if(!empty($category->translation->link) && $category->translation->link != $link){
            \Yii::$app->controller->redirect([$category->translation->link], 301);
            \Yii::$app->end();
        }

        return $category;
    }


    /**
     * @param bool $withSubcategories
     * @return int|null
     * @deprecated
     */

    public function goodsCount($withSubcategories = false){
        if(!$withSubcategories && $this->goodsCount != null){
            return $this->goodsCount;
        }elseif($withSubcategories && $this->goodsCountSubcategories != null){
            return $this->goodsCountSubcategories;
        }

        $q = new Query();
        $q = $q->select('COUNT(*)')
            ->from([Category::tableName().' a', Good::tableName().' b'])
            ->andWhere('a.ID = b.GroupID')
            ->andWhere('b.show_img = 1 AND b.deleted = 0 AND (b.PriceOut1 != 0 AND b.PriceOut2 != 0)');

        if($withSubcategories){
            $q->andWhere(['like', 'a.Code', $this->Code.'%', false]);
        }

        $q = $q->scalar();

        $q = intval($q);

        if($withSubcategories){
            $this->goodsCountSubcategories = $q;
        }else{
            $this->goodsCount = $q;
        }

        return $withSubcategories ? $this->goodsCountSubcategories : $this->goodsCount;
    }


    public static function createCategoryCode($parentCategory){
        if($parentCategory == ''){
            return false;
        }

        if(filter_var($parentCategory, FILTER_VALIDATE_INT)){
            $c = Category::findOne(['ID' => $parentCategory]);
            $parentCategory = $c->Code;
        }

        $c = Category::find()
            ->select('Code')
            ->where(['like', 'Code', $parentCategory.'%', false])
            ->andWhere(['LENGTH(`Code`)' => (strlen($parentCategory) + 3)])
            ->orderBy('`Code` DESC')
            ->scalar();

        if(empty($c)){
            $c = Category::find()
                ->select('Code')
                ->where(['like', 'Code', $parentCategory.'%', false])
                ->andWhere(['LENGTH(`Code`)' => strlen($parentCategory)])
                ->orderBy('`Code` DESC')
                ->scalar();

            $c .= 'AAA';
        }else{
            $c++;
        }

        return $c;
    }

    public function getCategoryLink($category){
        if(filter_var($category, FILTER_VALIDATE_INT)){
            $c = Category::findOne(['ID' => $category]);
            $category = $c->Code;
        }

        $c = Category::getParentCategories($category);

        if(empty($c)){
            return false;
        }

        $links = [];

        foreach($c as $cc){
            $links[] = TranslitHelper::to($cc->Name);
        }

        return implode('/', $links);
    }

    public static function change($id, $param){
        $a = Category::findOne(['ID'    =>  $id]);
        if($a){
            $a->$param = $a->$param == "1" ? "0" : "1";
            $a->save(false);

            return $a->$param;
        }

        return false;
    }

    public static function changeSell($id){
        return Category::change($id, 'canBuy');
    }

    public static function changeOneprice($id){
        return Category::change($id, 'onePrice');
    }

    public function getSubCategories(){
        $s = strlen($this->Code) + 3;
        return $this::find()
            ->joinWith(['translations'])
            ->where(['`category_translations`.`language`' => \Yii::$app->language])
            ->andWhere(['`category_translations`.`enabled`' => 1])
            ->andWhere(['like', 'Code', $this->Code.'%', false])
            ->andWhere(['LENGTH(`Code`)' => $s])
            ->all();
    }

    public static function getParentCategory($identifier){
        if(filter_var($identifier, FILTER_VALIDATE_INT)){
            $c = Category::findOne(['ID' => $identifier]);
            $identifier = $c->Code;
        }

        $identifier = substr($identifier, 0, -3);
        if(strlen($identifier) >= 3){
            return Category::findOne(['Code' => $identifier]);
        }else{
            return [];
        }
    }

    public function behaviors()
    {
        return [
            'LoggableBehavior' => [
                'class' => 'sammaye\audittrail\LoggableBehavior',
                'ignored' => [
                    'Name2',
                    'ID',
                    'viewOptions'
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goodsgroups';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name'], 'required'],
            [['text2', 'descr', 'keyword', 'yandexName', 'h1asc', 'h1desc', 'h1new', 'h1'], 'string'],
            [['listorder', 'enabled', 'ymlExport', 'canBuy', 'onePrice', 'hasFilter'], 'integer'],
            [['Name', 'Code', 'link', 'title', 'titlenew', 'titleasc', 'titledesc', 'catNameVinitelny2', 'catNameVinitelny', 'viewFile', 'viewOptions'], 'string', 'max' => 255],
            [['retailPercent'], 'number'],
            [['p_photo'], 'string', 'max' => 55],
            [['cat_img'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'Name' => 'Название',
            'Code' => 'Код',
            'p_photo' => 'P Photo',
            'link' => 'Ссылка',
            'text2' => 'Описание',
            'title' => 'Тайтл',
            'titlenew' => 'Titlenew',
            'titleasc' => 'Titleasc',
            'titledesc' => 'Titledesc',
            'descr' => 'Descr',
            'keyword' => 'Keyword',
            'cat_img' => 'Cat Img',
            'listorder' => 'Listorder',
            'canBuy' => 'Can Buy',
            'onePrice' => 'One Price',
            'hasFilter' => 'Has Filter',
            'enabled' => 'Menu Show',
            'viewFile' => 'Page Type',
            'viewOptions' => 'View Options',
            'yandexName' => 'Yandex Name',
            'catNameVinitelny' => 'Cat Name Vinitelny',
            'h1asc' => 'H1asc',
            'h1desc' => 'H1desc',
            'h1new' => 'H1new',
            'h1' => 'H1',
            'catNameVinitelny2' => 'Cat Name Vinitelny2',
            'ymlExport' => 'Yml Export',
        ];
    }

    public function beforeSave($insert){
        if(is_array($this->viewOptions)){
            $this->viewOptions = Json::encode($this->viewOptions);
        }

        $this->viewFile = empty($this->viewFile) ? 'category' : $this->viewFile;

        return parent::beforeSave($insert);
    }

	static function buildTree($elements){
		$tree = [];

		foreach($elements as $element){
			if($element->enabled == 1){
				if(strlen($element->Code) == 3){
                    $tree[$element->Code]['label'] = $element->name;
                    $tree[$element->Code]['url'] = $element->link;
                    $tree[$element->Code]['sequence'] = $element->sequence;

                    if(!empty($element->imgSrc)){
                        $tree[$element->Code]['imgSrc'] = $element->imgSrc;
                    }

                    if(!empty($element->photos)){
                        $tree[$element->Code]['slider'] = $element->photos;
                    }
				}elseif(strlen($element->Code) == 6 && isset($tree[substr($element->Code, 0, -3)])){
                    $tree[substr($element->Code, 0, -3)]['items'][] = [
                        'label'     =>  $element->name,
                        'url'       =>  $element->link,
                        'sequence'  =>  $element->sequence
                    ];
                }
			}
		}

        uasort($tree, function($a, $b){
            return $a['sequence'] > $b['sequence'];
        });

        foreach($tree as $key => $branch){
            if(isset($branch['items']) && is_array($branch['items'])){
                uasort($branch['items'], function($a, $b){
                    return $a['sequence'] > $b['sequence'];
                });
                $tree[$key] = $branch;
            }
        }

		return $tree;
	}

    public function afterFind(){
        if(empty($this->viewOptions)){
            $this->viewOptions = '{}';
        }

        $this->viewOptions = Json::decode($this->viewOptions);

        return parent::afterFind();
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->translation->save(false);

        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }
}
