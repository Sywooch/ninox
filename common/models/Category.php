<?php

namespace common\models;

use app\helpers\TranslitHelper;
use Yii;
use yii\db\Query;
use yii\helpers\Json;

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
 */
class Category extends \yii\db\ActiveRecord
{

    var $parentCategory;
	protected $items;

    private $parents = [];
    private $goodsCount = null;
    private $goodsCountSubcategories = null;

    public function getParents(){
        if(!empty($this->parents)){
            return $this->parents;
        }

        $this->parents = self::getParentCategories($this->Code);

        return $this->parents;
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

    public static function getList(){
        $cats = Category::find()->select(['ID', 'Name', 'Code'])->all();
        $r = [];
        $n = "";
        foreach($cats as $c){
            if(strlen($c->Code) == 3){
                $n = $c->Name;
            }
            $r[$n][$c->ID] = $c->Name;
        }
        return $r;
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
        return Category::find()->where(['like', 'Code', $this->Code.'%', false])->andWhere(['LENGTH(`Code`)' => $s])->all();
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
                    'ID'
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
        $this->pageType = $this->pageType == '' ? 0 : $this->pageType;
        $this->listorder = $this->listorder == '' ? 0 : $this->listorder;

        return parent::beforeSave($insert);
    }

	static function buildTree(array &$elements, $parentCode = ''){
		$branch = [];

		foreach($elements as $element){

			if($element->enabled == 1){
				if(substr($element->Code, 0, -3) == $parentCode){
					$branch[$element->Code]['label'] = $element->Name;
					$branch[$element->Code]['url'] = $element->link;

                    if(!empty($element->imgSrc)){
                        $branch[$element->Code]['imgSrc'] = $element->imgSrc;
                    }

					$items = self::buildTree($elements, $element->Code);
					if($items && $parentCode == ''){
						array_unshift($items, ['label' => $element->Name, 'url' => $element->link, 'options' => ['class' => 'see-all']]);
						$branch[$element->Code]['items'] = $items;
						$branch[$element->Code]['url'] = '#0';
					}
				}
			}

		}

		return $branch;
	}

    public function afterFind(){
        $this->link = urldecode($this->link);

        if(empty($this->viewOptions)){
            $this->viewOptions = '{}';
        }

        $this->viewOptions = Json::decode($this->viewOptions);

        return parent::afterFind();
    }
}
