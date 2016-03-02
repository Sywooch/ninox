<?php

namespace backend\modules\categories\controllers;

use backend\controllers\SiteController as Controller;
use backend\models\Category;
use backend\models\Good;
use backend\models\CategorySearch;
use common\models\CategoryUk;
use common\models\GoodSearch;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `categories` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex(){
        $breadcrumbs = $goodsCount = [];

        $category = Category::findOne(['Code' => \Yii::$app->request->get("category")]);

        if(empty($category)){
            $category = new Category();
        }elseif(empty($category->childs)){
            $this->redirect(['/goods', 'category' => $category->Code]);
        }

        $categorySearch = new CategorySearch();

        $categorySearch = $categorySearch->search(\Yii::$app->request->get());

        $this->getView()->params['breadcrumbs'] = $this->createBreadcrumbs($category);

        return $this->render('index', [
            'categories'    =>  $categorySearch,
            'breadcrumbs'   =>  $breadcrumbs,
            'goodsCount'    =>  $this->getGoodsCount($category),
            'nowCategory'   =>  $category
        ]);
    }

    /**
     * Обновляет порядок сортировки категорий
     *
     * @return array
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionUpdateorder(){
        if(!\Yii::$app->request->isAjax){
            throw new BadRequestHttpException("Данный метод возможен только через ajax!");
        }

        $response = [];

        \Yii::$app->response->format = "json";

        $data = \Yii::$app->request->post("data");
        $category = \Yii::$app->request->post("category");
        $length = (strlen($category) + 3);

        $data = array_flip($data);

        $categories = Category::find()->where(['like', 'Code', $category.'%', false])
            ->andWhere(['LENGTH(`Code`)' => $length])
            ->orderBy('listorder, ID ASC');

        foreach($categories->each() as $category){
            $response[] = $category->ID;

            $category->listorder = $data[$category->ID];
            $category->save(false);
        }

        return $response;
    }

    /**
     * @param $param int - ID категории
     *
     * @return mixed|string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView($param){
        $category = Category::findOne(['ID' => $param]);

        if(!$category){
            throw new NotFoundHttpException("Такая категория не найдена!");
        }

        $b = $category->parents;

        $breadcrumbs = [];

        if(!empty($b)){
            foreach($b as $bb){
                $breadcrumbs[] = [
                    'label' =>  $bb->Name,
                    'url'   =>  Url::toRoute(['/goods', 'category' => $bb->Code])
                ];
            }
        }

        if(\Yii::$app->request->post("Category") && \Yii::$app->request->get("act") == "edit"){
            $r = \Yii::$app->request;
            $c = Category::findOne(['ID' => $param]);

            if(isset($r->post("Category")['keywords'])){
                $r->post("Category")['keywords'] = implode(", ", $r->post("Category")['keywords']);
            }

            foreach($r->post("Category") as $k => $v){
                if($k == 'keywords'){
                    $k = 'keyword';
                    $v = implode(', ', $v);
                }
                if(isset($c->$k) && (!empty($v) || $v == "0")){
                    $c->$k = $v;
                }else{
                    $c->$k = " ";
                }
            }

            $c->save(false);
        }

        return $this->render(\Yii::$app->request->get("act") == "edit" ? 'edit' : 'view', [
            'category'      =>  $category,
            'subCats'       =>  Category::getSubCategories($category->Code),
            'parentCategory'=>  Category::getParentCategory($category->Code),
            'categoryUk'    =>  CategoryUk::findOne(['ID' => $category->ID])
        ]);
    }

    public function actionEdit(){

    }


    public function actionAdd(){
        $c = new Category();
        $cUk = new CategoryUk();
        $breadcrumbs = [];
        $ct = \Yii::$app->request->get("category");

        if(!empty($ct)){
            $cc = Category::findOne(['ID' => $ct]);
            $c->Code = $cc->Code.'AAA';

            if(!empty($cc)){
                $b = Category::getParentCategories($cc->Code);

                if(!empty($b)){
                    foreach($b as $bb){
                        $breadcrumbs[] = [
                            'label' =>  $bb->Name,
                            'url'   =>  Url::toRoute(['/goods', 'category' => $bb->Code])
                        ];
                    }
                }

                $b = Category::findOne(['ID' => $ct]);
                if(!empty($b)){
                    $breadcrumbs[] = $b->Name;
                }
            }
        }

        if(\Yii::$app->request->post() && \Yii::$app->request->post("parent_category") != ''){
            $m = new Category();
            $mUk = new CategoryUk();

            foreach(\Yii::$app->request->post("Category") as $y=>$yy){
                if($y == 'keywords'){
                    $y = 'keyword';
                    $yy = implode(', ', $yy);
                }
                $m->$y = $yy;
            }

            $m->Code = Category::createCategoryCode(\Yii::$app->request->post("parent_category"));

            foreach(\Yii::$app->request->post("CategoryUk") as $y=>$yy){
                if($y == 'keywords'){
                    $y = 'keyword';
                    $yy = implode(', ', $yy);
                }
                $mUk->$y = $yy;
            }

            if($m->save()){
                $mUk->ID = $m->ID;
                $mUk->Code = $m->Code;
                $mUk->save(false);
            }else{
                $c = $m;
                $mUk->validate();
                $cUk = $mUk;
            }
        }

        $this->getView()->params['breadcrumbs'] = $this->createBreadcrumbs();

        $this->getView()->params['breadcrumbs'][] = [
            'label' =>  'Новая категория'
        ];

        return $this->render('edit', [
            'category'      =>  $c,
            'breadcrumbs'   =>  [],
            'parentCategory'=>  $ct == '' ? new Category : Category::findOne(['ID' => $ct]),
            'categoryUk'    =>  $cUk
        ]);
    }

    /**
     * Делает хлебные крошки
     *
     * @param Category $category
     *
     * @return array
     */
    public function createBreadcrumbs($category = null){
        $breadcrumbs = [];

        $moduleBreadcrumb = [
            'label' =>  'Категории',
        ];

        if(\Yii::$app->request->url != '/categories'){
            $moduleBreadcrumb['url'] = Url::toRoute(['/categories', 'smartFilter' => \Yii::$app->request->get("smartFilter")]);
        }

        $breadcrumbs[] = $moduleBreadcrumb;

        if(empty($category) || $category->isNewRecord){
            return $breadcrumbs;
        }

        $parentCategories = $category->parents;

        if(sizeof($parentCategories) >= 1) {
            foreach ($parentCategories as $parentCategory) {
                if ($parentCategory != '') {
                    $breadcrumbs[] = [
                        'label' => $parentCategory->Name,
                        'url'   =>  Url::toRoute(['/categories', 'category' => $parentCategory->Code, 'smartFilter' => \Yii::$app->request->get("smartFilter")])
                    ];
                }
            }
        }

        $breadcrumbs[] = ['label' => $category->Name];


        return $breadcrumbs;
    }


    /**
     * Возвращает массив с колличеством товаров в подкатегориях категории
     *
     * @param Category $category
     *
     * @return array
     */
    public function getGoodsCount($category){
        $goodsCount = [];
        $enabledGoods = $disabledGoods = 0;

        $categoryCodeLength = strlen($category->Code) + 3;

        $goodsCountQuery = Category::find()
            ->select(['`a`.`Code` as `Code`', 'SUM(`b`.`show_img`) as `enabled`', 'COUNT(`b`.`ID`) as `all`'])
            ->from([Category::tableName().' a', Good::tableName().' b'])
            ->where('`b`.`GroupID` = `a`.`ID`')
            ->groupBy('`b`.`GroupID`');

        if(!empty($category->Code)){
            $goodsCountQuery->andWhere(['like', '`a`.`Code`', $category->Code.'%', false]);
        }

        foreach($goodsCountQuery->asArray()->each() as $row){
            $row['Code'] = substr($row['Code'], 0, $categoryCodeLength);

            if(isset($goodsCount[$row['Code']])){
                $goodsCount[$row['Code']] = [
                    'all'       =>  ($row['all'] + $goodsCount[$row['Code']]['all']),
                    'enabled'   =>  ($row['enabled'] + $goodsCount[$row['Code']]['enabled']),
                    'disabled'  =>  (($row['all'] - $row['enabled']) + $goodsCount[$row['Code']]['disabled'])
                ];
            }else{
                $goodsCount[$row['Code']] = [
                    'all'   =>  $row['all'],
                    'enabled'   =>  $row['enabled'],
                    'disabled'  =>  ($row['all'] - $row['enabled'])
                ];
            }

            $enabledGoods += $row['enabled'];
            $disabledGoods += ($row['all'] - $row['enabled']);
        }

        $goodsCount['all'] = [
            'enabled'   =>  $enabledGoods,
            'disabled'  =>  $disabledGoods
        ];

        return $goodsCount;
    }

}
