<?php

namespace backend\modules\categories\controllers;

use backend\controllers\SiteController as Controller;
use backend\models\Category;
use backend\models\Good;
use backend\models\CategorySearch;
use common\models\CategoryTranslation;
use common\models\CategoryUk;
use common\models\GoodTranslation;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\widgets\Pjax;

/**
 * Default controller for the `categories` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     * @throws \yii\base\InvalidParamException
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

    public function actionManipulate(){
        if(!\Yii::$app->request->isAjax){
            throw new BadRequestHttpException('Данный метод доступен только через ajax!');
        }

        $categoryID = \Yii::$app->request->post('category');

        $category = Category::findOne($categoryID);

        if(!$category){
            throw new NotFoundHttpException("Категория с идентификатором {$categoryID} не найдена!");
        }

        $attribute = \Yii::$app->request->post('attribute');

        $value = \Yii::$app->request->post('value');

        if(empty($value)){
            $value = $category->$attribute == 1 ? 0 : 1;
        }

        $category->$attribute = $value;

        if(!$category->save(false)){
            return false;
        }

        return $category->$attribute;
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
            throw new NotFoundHttpException("Категория с идентификатором {$param} не найдена!");
        }

        $this->getView()->params['breadcrumbs'] = $this->createBreadcrumbs($category, false);

        if(\Yii::$app->request->get('act') == 'edit'){
            $this->getView()->params['breadcrumbs'][] = \Yii::t('backend', 'Редактирование');

            if(\Yii::$app->request->post("Category")){
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
        }else{
            $this->getView()->params['breadcrumbs'][] = \Yii::t('backend', 'Просмотр');
        }

        return $this->render(\Yii::$app->request->get("act") == "edit" ? 'edit' : 'view', [
            'category'      =>  $category,
            'subCats'       =>  $category->subCategories,
            'parentCategory'=>  Category::getParentCategory($category->Code),
            'categoryUk'    =>  CategoryUk::findOne(['ID' => $category->ID])
        ]);
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


    public function actionUploadcategoryphoto(){
        if(\Yii::$app->request->isAjax){
            \Yii::$app->response->format = 'json';
            $f = UploadHelper::__upload($_FILES['categoryPhoto']);
            if($f){
                $m = Category::findOne(['ID' => \Yii::$app->request->post("ItemId")]);
                if($m){
                    $m->cat_img = $f;
                    if($m->save(false)){ //TODO: потом поровнять так, чтобы было норм, с валидацией, ёпта
                        return [
                            'link'  =>  $f
                        ];
                    }
                }
            }
            return [
                'state' =>  0
            ];
        }else{
            return $this->run('site/error');
        }
    }

    public function actionUpdateattribute(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Этот метод работает только через ajax!");
        }

        $attribute = \Yii::$app->request->post("attribute");

        $category = Category::findOne(['id' => \Yii::$app->request->post("categoryID")]);

        if(!$category){
            throw new NotFoundHttpException("Категория не найден!");
        }

        $category->$attribute = \Yii::$app->request->post("value");

        $category->save(false);
    }


    /**
     * Делает хлебные крошки
     *
     * @param Category $category
     *
     * @return array
     */
    public function createBreadcrumbs($category = null, $last = true){
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

        $lastBreadcrumb = ['label' => $category->Name];

        if(!$last){
            $lastBreadcrumb['url'] = Url::toRoute(['/categories', 'category' => $category->Code, 'smartFilter' => \Yii::$app->request->get("smartFilter")]);
        }

        $breadcrumbs[] = $lastBreadcrumb;


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
            ->select(['`a`.`Code` as `Code`', 'SUM(`c`.`enabled`) as `enabled`', 'COUNT(`b`.`ID`) as `all`'])
            ->from([Category::tableName().' a'])
            ->leftJoin(Good::tableName().' b', '`b`.`GroupID` = `a`.`ID`')
            ->leftJoin(GoodTranslation::tableName().' c', '`c`.`ID` = `b`.`ID`')
            ->andWhere(['c.language'    =>  \Yii::$app->language])
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

    public function actionRecalc(){
        if(!\Yii::$app->request->isAjax){
            throw new BadRequestHttpException("Данный метод доступен только через ajax!");
        }

        $categoryID = \Yii::$app->request->post("categoryID");

        switch(\Yii::$app->request->get("act")){
            case 'retailPrice':
                $category = Category::findOne($categoryID);

                if(!$category){
                    throw new NotFoundHttpException("Категория с идентификатором {$categoryID} не найдена!");
                }

                \Yii::$app->response->format = 'json';

                $category->updatePrices(\Yii::$app->request->post("size"), 'retail');

                return ['categoryCode' => $category->Code];
                break;
        }
    }

}
