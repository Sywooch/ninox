<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 30.05.16
 * Time: 13:20
 */

namespace backend\modules\categories\models;


use backend\models\Category;
use yii\base\Model;

class CategoryForm extends Model
{

    public $name;

    public $title;

    public $titleAsc;

    public $titleDesc;

    public $titleNew;

    public $header;

    public $headerAsc;

    public $headerDesc;

    public $headerNew;

    public $metaDescription;

    public $description;

    public $keywords = '';

    public $parentCategory;

    public $enabled = false;

    public $retailPercent = 20;

    public $onePrice = false;

    public $umlExport = false;

    public $sellProducts = false;

    private $category;

    public function rules(){
        return [
            [['name'], 'required'],
            [['name', 'title', 'titleAsc', 'titleDesc', 'titleNew', 'header', 'headerAsc', 'headerDesc', 'headerNew', 'keywords'], 'string', 'max' => 255],
            [['description', 'metaDescription'], 'string'],
            [['retailPercent', 'parentCategory'], 'number'],
            [['enabled', 'onePrice', 'umlExport', 'sellProducts'], 'boolean']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'          =>  'Название',
            'title'         =>  'Заголовок',
            'titleAsc'      =>  'Дешёво',
            'titleDesc'     =>  'Дорого',
            'titleNew'      =>  'Новинки',
            'header'        =>  'Заголовок на странице',
            'headerAsc'     =>  'Дешёво',
            'headerDesc'    =>  'Дорого',
            'headerNew'     =>  'Новинки',
            'metaDescription'=>  'Meta-описание',
            'description'   =>  'Описание',
            'keywords'      =>  'Ключевые слова',
            'enabled'       =>  'Состояние категории',
            'retailPercent' =>  'Розничная цена больше',
            'onePrice'      =>  'Одна цена на сайте',
            'umlExport'     =>  'Экспортировать в xml',
            'sellProducts'  =>  'Продаются товары',
            'parentCategory'=>  'Категория-родитель'
        ];
    }

    public function getKeywordsArray(){
        $keywords = explode(', ', $this->keywords);

        if(empty($keywords)){
            return;
        }

        return $keywords;
    }

    public function save(){
        /*if(!$this->validate()){
            return false;
        }*/

        if(empty($this->category)){
            $this->category = new Category();
        }

        $this->category->setAttributes([
            'retailPercent' =>  $this->retailPercent,
            'onePrice'      =>  $this->onePrice,
            'ymlExport'     =>  $this->umlExport,
            'canBuy'        =>  $this->sellProducts
        ], false);

        $this->category->translation->setAttributes([
            'Name'              =>  $this->name,
            'enabled'           =>  $this->enabled,
            'title'             =>  $this->title,
            'titleOrderAscending'=>  $this->titleAsc,
            'titleOrderDescending'=>  $this->titleDesc,
            'titleOrderNew'     =>  $this->titleNew,
            'header'            =>  $this->header,
            'headerOrderAscending'=>  $this->headerAsc,
            'headerOrderDescending'=>  $this->headerDesc,
            'headerOrderNew'    =>  $this->headerNew,
            'categoryDescription'=> htmlspecialchars($this->description),
            'metaDescription'   =>  strip_tags($this->metaDescription),
        ], false);

        return $this->category->save(false);
    }

    public function getCategory(){
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function loadCategory($category){
        $this->setAttributes([
            'name'          =>  $category->name,
            'title'         =>  $category->title,
            'titleAsc'      =>  $category->titleasc,
            'titleDesc'     =>  $category->titledesc,
            'titleNew'      =>  $category->titlenew,
            'header'        =>  $category->header,
            'headerAsc'     =>  $category->headerOrderAscending,
            'headerDesc'    =>  $category->headerOrderDescending,
            'headerNew'     =>  $category->headerOrderNew,
            'metaDescription'=> $category->metaDescription,
            'description'   =>  $category->description,
            'keywords'      =>  $category->keywords,
            'enabled'       =>  $category->enabled,
            'retailPercent' =>  $category->retailPercent,
            'onePrice'      =>  $category->onePrice,
            'umlExport'     =>  $category->ymlExport,
            'sellProducts'  =>  $category->canBuy
        ]);

        $parentCategory = Category::getParentCategory($category->Code);

        if($parentCategory){
            $this->parentCategory = $parentCategory->ID;
        }

        $this->category = $category;
    }
    
}