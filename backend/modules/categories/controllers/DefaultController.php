<?php

namespace backend\modules\categories\controllers;

use backend\controllers\SiteController as Controller;
use backend\models\Category;
use backend\models\Good;
use backend\models\CategorySearch;
use common\models\GoodSearch;
use yii\helpers\Url;

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
        /*

        //Делаем запрос на колл-во товаров
        $tGoodsCount = Category::find()->
        select(['`a`.`Code` as `Code`', 'SUM(`b`.`show_img`) as `enabled`', 'COUNT(`b`.`ID`) as `all`'])->
        from([Category::tableName().' a', Good::tableName().' b'])->
        where('`b`.`GroupID` = `a`.`ID`')
            ->groupBy('`b`.`GroupID`');

        if ($categoryLength != 3) {
            //Добавляем новое условие для запроса на колл-во товаров
            $tGoodsCount->andWhere(['like', '`a`.`Code`', $category.'%', false]);

            $parentCategories = Category::getParentCategories($category);

            //делаем хлебные крошки
            if (sizeof($parentCategories) >= 1) {
                foreach ($parentCategories as $parentCategory) {
                    if ($parentCategory != '') {
                        $breadcrumbs[] = [
                            'label' => $parentCategory->Name,
                            'url'   =>  Url::toRoute(['/goods', 'category' => $parentCategory->Code, 'smartfilter' => \Yii::$app->request->get("smartfilter")])
                        ];
                    }
                }
            }
        }

        //Считаем колличество товаров по запросу
        foreach($tGoodsCount->asArray()->each() as $row){
            $row['Code'] = substr($row['Code'], 0, $categoryLength);

            if(isset($goodsCount[$row['Code']])){
                $goodsCount[$row['Code']] = [
                    'all'   =>  ($row['all'] + $goodsCount[$row['Code']]['all']),
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

        $breadcrumbs = array_reverse($breadcrumbs);

        $categorySearch = new CategorySearch();
        $categorySearch = $categorySearch->search([
            'len'   =>  $categoryLength,
            'cat'   =>  $category,
            'data'  =>  \Yii::$app->request->get()
        ]);

        if (\Yii::$app->request->get("onlyGoods") != 'true' && sizeof($categorySearch) >= 1) {
            return $this->render('index', [
                'categories' => $categorySearch,
                'breadcrumbs' => $breadcrumbs,
                'goodsCount'    =>  $goodsCount,
                'nowCategory' => Category::findOne(['Code' => $category])
            ]);
        }

        $category = Category::findOne(['Code' => $category]);

        if(empty($category)){
            return $this->run('site/error');
        }

        $goodsSearch = new GoodSearch();

        $breadcrumbs = array_reverse($breadcrumbs);

        return $this->render('goods', [
            'breadcrumbs'   => $breadcrumbs,
            'goods'         => $goodsSearch->search([
                'catID'     => $category->ID
            ]),
            'goodsCount'    => $goodsCount,
            'nowCategory'   => $category,
        ]);

        */

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
            'goodsCount'    =>  $goodsCount,
            'nowCategory'   =>  $category
        ]);
    }

    public function actionChangeorder(){

    }

    public function actionView(){

    }

    public function actionEdit(){

    }

    /**
     * Делает хлебные крошки
     *
     * @param Category $category
     *
     * @return array
     */
    public function createBreadcrumbs($category){
        $breadcrumbs = [];

        $moduleBreadcrumb = [
            'label' =>  'Категории',
        ];

        if(\Yii::$app->request->url != '/categories'){
            $moduleBreadcrumb['url'] = Url::toRoute(['/categories', 'smartfilter' => \Yii::$app->request->get("smartfilter")]);
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
                        'url'   =>  Url::toRoute(['/categories', 'category' => $parentCategory->Code, 'smartFilter' => \Yii::$app->request->get("smartfilter")])
                    ];
                }
            }
        }

        $breadcrumbs[] = ['label' => $category->Name];


        return $breadcrumbs;
    }
}
