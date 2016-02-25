<?php

namespace backend\modules\goods\controllers;

use backend\modules\goods\models\GoodMainForm;
use common\helpers\UploadHelper;
use common\helpers\TranslitHelper;
use common\models\Category;
use common\models\CategorySearch;
use common\models\CategoryUk;
use backend\models\Good;
use common\models\GoodSearch;
use common\models\GoodsPhoto;
use common\models\GoodUk;
use backend\models\History;
use backend\models\SborkaItem;
use common\models\PriceListImport;
use common\models\UploadPhoto;
use sammaye\audittrail\AuditTrail;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use backend\controllers\SiteController as Controller;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class DefaultController extends Controller
{

    public function actionIndex(){
        $category = \Yii::$app->request->get("category");
        $categoryLength = $category != '' ? (strlen($category) + 3) : 3;
        $breadcrumbs = $goodsCount = [];
        $enabledGoods = $disabledGoods = 0;

        /*
         *
         * SELECT
         *  `a`.*
         * FROM
         *  `goodsgroups` `a`,
         *  (
         *      SELECT
         *       SUBSTR(`a`.`Code`, '1', '6') AS `codeAlias` FROM `goodsgroups`
         *      `a` LEFT JOIN `goods` `b` ON b.GroupID = a.ID
         *          WHERE (`a`.`Code` LIKE 'AAB%')
         *          AND ((LENGTH(a.Code) > 3) AND (`b`.`show_img`=0))
         *          GROUP BY `codeAlias` ORDER BY `a`.`listorder`, `a`.`ID`) AS `tmp` WHERE `a`.`Code` = `tmp`.`codeAlias`;
         *
         *
         */

        $query = Category::find()
            ->select("SUBSTR(`goodsgroups`.`Code`, '1', '6') AS `codeAlias`")
            ->leftJoin('goods', '`goods`.`GroupID` = `goodsgroups`.`ID`')
            ->where('`goodsgroups`.`Code` LIKE \''.$category.'\'')
            ->andWhere('(LENGTH(`goodsgroups`.`Code`) > \'3) AND (`goods`.`show_img` = \'0\')');

        $query = Category::find()->from('`goodsgroups` `a`');

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
    }

    public function actionImport(){
        $pricelist = PriceListImport::findOne(\Yii::$app->request->get("fileid"));

        if(\Yii::$app->request->isAjax){
            switch(\Yii::$app->request->post("action")){
                case 'renameFile':
                    $file = PriceListImport::findOne(\Yii::$app->request->post("id"));

                    if(!$file){
                        throw new NotFoundHttpException("Такой файл не найден!");
                    }

                    $file->name = \Yii::$app->request->post("value");
                    $file->save(false);
                    break;
            }
        }

        if($pricelist){
            $data = $importInfo = [];

            $xls = \PHPExcel_IOFactory::load(\Yii::getAlias('@webroot').'/files/importedPrices/'.$pricelist->file);

            $models = $xls->getActiveSheet()->toArray();

            $dataProvider = new ArrayDataProvider();

            if(isset($pricelist->configuration['withHeaders'])){
                $header = $models[0];
                unset($models[0]);
            }
            //TODO: сделать чтобы заголовки отображались заголовками таблицы

            $dataProvider->setModels($models);

            if(\Yii::$app->request->post("PriceListImportTable")){
                $keys = $attributes = [];
                $replaceExisting = false;
                $keysCount = $added = $updated = 0;

                $columns = \Yii::$app->request->post("PriceListImportTable")['columns'];

                foreach($columns as $key => $subarray){
                    if(!empty($subarray['key'])){
                        $keys[$key] = $subarray['attribute'];
                    }

                    if(!empty($subarray['attribute'])){
                        $attributes[$key] = $subarray['attribute'];
                    }
                }

                if(!empty($keys)){
                    $keysCount = sizeof($keys);
                    $replaceExisting = true;
                }

                foreach($dataProvider->getModels() as $model){
                    $good = $badParams = false;

                    if($replaceExisting){
                        $good = Good::find();

                        $conditions = [];

                        foreach($keys as $key => $attribute){
                            $conditions[$attribute] = $model[$key];
                        }

                        if(!empty($conditions) && $keysCount == sizeof($conditions)){
                            $good->andWhere($conditions);
                        }else{
                            $badParams = true;
                        }

                        if(!$badParams){
                            $good = $good->one();
                        }
                    }

                    if(!$good){
                        $good = new Good();
                    }

                    if(!$badParams){
                        foreach($model as $key => $field){
                            if(!empty($attributes[$key])){
                                $changedAttribute = $attributes[$key];
                                $good->$changedAttribute = $field;
                            }
                        }

                        $thisAdded = $good->isNewRecord ? 1 : 0;

                        if($good->save(false)){
                            $added += $thisAdded;
                            $updated++;
                        }
                    }
                }

                $importInfo = [
                    'updated'   =>  $updated - $added,
                    'added'     =>  $added,
                    'totalCount'=>  sizeof($models)
                ];

                $pricelist->imported = 1;
                $pricelist->importedDate = date('Y-m-d H:i:s');

                $pricelist->save(false);
            }

            return $this->render('import_table', [
                'data'          =>  $data,
                'columns'       =>  $xls->getActiveSheet()->getHighestColumn(),
                'filename'      =>  $pricelist->name,
                'dataProvider'  =>  $dataProvider,
                'importInfo'    =>  $importInfo
            ]);
        }

        if($_FILES && $_FILES['pricelist']){
            \Yii::$app->response->format = 'json';

            $file = UploadHelper::__upload($_FILES['pricelist'], [
                'filename'  =>  \Yii::$app->security->generateRandomString(),
                'directory' =>  'files/importedPrices',
                'fullReturn'=>  true
            ]);

            $pricelist = new PriceListImport([
                'file'  =>  $file['filename'],
                'format'=>  $file['mime'],
                'name'  =>  $file['original_filename']
            ]);

            if($pricelist->save()){
                return [
                    'id'    =>  $pricelist->id,
                    'name'  =>  $file['original_filename']
                ];
            }

            return false;
        }

        return $this->render('import_index', [
            'priceListsProvider'    =>  new ActiveDataProvider([
                'query' =>  PriceListImport::find(),
                'sort'  =>  [
                    'defaultOrder'  =>  [
                        'created'   =>  SORT_DESC
                    ]
                ]
            ])
        ]);
    }

    public function actionLog(){
        $query = AuditTrail::find()->where([
           'model'  =>  Good::className()
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' =>  $query->orderBy('id desc')
        ]);

        return $this->render('log', [
            'dataProvider'  =>  $dataProvider
        ]);
    }

    public function actionSearchgoods(){
        \Yii::$app->response->format = "json";

        return Good::searchGoods(\Yii::$app->request->get("string"), [
            'Name', 'Code'
        ]);
    }

    /**
     * @return string
     * Метод добавляет товары на сайт
     * @deprecated метод actionGood должен позволять редактировать и создавать товар
     */
    public function actionAddgood(){
        $good = new Good();
        $goodUk = new GoodUk();
        $c = \Yii::$app->request->get("category");
        $breadcrumbs = [];

        if(\Yii::$app->request->post("Good")){
            $m = new Good();
            $m->attributes = \Yii::$app->request->post("Good");
            if($m->save()){
                $mUk = new GoodUk();
                $mUk->attributes = \Yii::$app->request->post("GoodUk");
                $mUk->ID = $m->ID;
                $mUk->save(false);
            }
        }

        if(isset($c)){
            $good->GroupID = $c;

            $a = Category::findOne(['ID' => $good->GroupID]);
            $p = Category::getParentCategories($a->Code);


            $breadcrumbs[] = [
                'label' =>  'Категории',
                'url'   =>  Url::toRoute(['/goods'])
            ];

            if (sizeof($p) >= 1) {
                $p = array_reverse($p);

                foreach ($p as $c) {
                    if ($c != '') {
                        $breadcrumbs[] = [
                            'label' => $c->Name,
                            'url' => Url::toRoute(['/goods', 'category' => $c->Code])
                        ];
                    }
                }
            }

            $breadcrumbs[] = [
                'label' =>  $a->Name
            ];
        }else{
            $a = [];
        }

        return $this->render('editgood', [
            'breadcrumbs' => $breadcrumbs,
            'good'       => $good,
            'goodUk'     => $goodUk,
            'nowCategory' => $a,
            'uploadPhoto'  =>  new UploadPhoto(),
            'additionalPhotos'  =>  ''
        ]);
    }

    public function actionRating(){

    }

    /**
     * @return string
     * метод добавляет категорию на сайт
     * @deprecated метод actionCategory должен добавлять и редактировать категорию
     */
    public function actionAddcategory(){
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

        return $this->render('editcategory', [
            'category'      =>  $c,
            'breadcrumbs'   =>  $breadcrumbs,
            'parentCategory'=>  $ct == '' ? new Category : Category::findOne(['ID' => $ct]),
            'categoryUk'    =>  $cUk
        ]);
    }

    public function actionShowcategory($param){
        $c = Category::findOne(['ID' => $param]);

        if(empty($c)){
            return $this->run('site/error');
        }

        $b = Category::getParentCategories($c->Code);
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

        return $this->render(\Yii::$app->request->get("act") == "edit" ? 'editcategory' : 'showcategory', [
            'category'      =>  $c,
            'breadcrumbs'   =>  $breadcrumbs,
            'subCats'       =>  Category::getSubCategories($c->Code),
            'parentCategory'=>  Category::getParentCategory($c->Code),
            'categoryUk'    =>  CategoryUk::findOne(['ID' => $c->ID])
        ]);
    }

    public function actionView($param){
        $good = Good::findOne($param);

        if(!$good){
            throw new NotFoundHttpException("Товар с ID ".$param." не найден!");
        }

        //Начало хлебных крошек
        $category = Category::findOne($good->GroupID);
        $parents = Category::getParentCategories($category->Code);

        $this->getView()->params['breadcrumbs'][] = [
            'label' =>  'Категории',
            'url'   =>  '/goods'
        ];

        if (sizeof($parents) >= 1) {
            $parents = array_reverse($parents);

            foreach ($parents as $parentCategory) {
                if ($parentCategory != '') {
                    $this->getView()->params['breadcrumbs'][] = [
                        'label' => $parentCategory->Name,
                        'url'   => Url::toRoute(['/goods', 'category' => $parentCategory->Code])
                    ];
                }
            }
        }

        $this->getView()->params['breadcrumbs'][] = [
            'label' =>  $category->Name,
            'url'   => Url::toRoute(['/goods', 'category' => $category->Code])
        ];

        $this->getView()->params['breadcrumbs'][] = $good->Name;
        //Конец хлебных крошек

        $goodMainForm = new GoodMainForm();
        $goodMainForm->loadGood($good);

        if(\Yii::$app->request->get("act") == "edit"){
            if(\Yii::$app->request->post("GoodMainForm") && $goodMainForm->load(\Yii::$app->request->post("Good"))){
                $goodMainForm->save();
            }
            /*$post = \Yii::$app->request->post();

            if(\Yii::$app->request->isAjax && isset($post['validate']) && $post['validate']){
                $good = new Good;
                $good->load($post['Good']);
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($good);
            }

            if(!empty($post)){
                if(empty($goodUK)){
                    $goodUK = new GoodUk;
                    $goodUK->ID = $param;
                }

                $goodUK->Name = $post['GoodUk']['Name'];
                $goodUK->Name2 = $post['GoodUk']['Name'];
                $goodUK->Description = $post['GoodUk']['Description'];

                $goodUK->save(false);

                $good->attributes = $post['Good'];

                if($good->save(false)){

                    //Модель успешно сохранена
                }else{
                    //Произошла ошибка валидации
                }
                //TODO: добавить сообщение об успешном обновлении инфо о товаре, или о ошибке
            }*/
        }

        return $this->render('edit', [
            'good'              =>  $good,
            //'goodUk'          =>  $goodUK,
            'goodMainForm'      =>  $goodMainForm,
            'nowCategory'       =>  $category,
            'uploadPhoto'       =>  new UploadPhoto(),
            'additionalPhotos'  =>  new ActiveDataProvider([
                'query' =>  GoodsPhoto::find()->where(['ItemId' => $good->ID]),
                'pagination'    =>  [
                    'pageSize'  =>  0
                ]
            ])
        ]);
    }

    public function actionShowgood($param){
        if(!filter_var($param, FILTER_VALIDATE_INT)){
            return $this->run('site/error');
        }

        $good = Good::findOne(['ID' => $param]);
        $goodUK = GoodUk::findOne(['ID' => $param]);

        if(empty($good)){
            throw new NotFoundHttpException("Такой товар не найден!");
        }

        //Начало хлебных крошек
        $a = Category::findOne(['ID' => $good->GroupID]);
        $p = Category::getParentCategories($a->Code);

        $breadcrumbs = [];

        $breadcrumbs[] = [
            'label' =>  'Категории',
            'url'   =>  '/goods'
        ];

        if (sizeof($p) >= 1) {
            $p = array_reverse($p);

            foreach ($p as $c) {
                if ($c != '') {
                    $breadcrumbs[] = [
                        'label' => $c->Name,
                        'url' => Url::toRoute(['/goods', 'category' => $c->Code])
                    ];
                }
            }
        }

        $breadcrumbs[] = [
            'label' =>  $a->Name,
            'url' => Url::toRoute(['/goods', 'category' => $a->Code])
        ];

        $breadcrumbs[] = $good->Name;
        //Конец хлебных крошек

        if(\Yii::$app->request->get("act") == "edit"){
            $post = \Yii::$app->request->post();

            if(\Yii::$app->request->isAjax && isset($post['validate']) && $post['validate']){
                $good = new Good;
                $good->load($post['Good']);
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($good);
            }

            if(!empty($post)){
                if(empty($goodUK)){
                    $goodUK = new GoodUk;
                    $goodUK->ID = $param;
                }

                $goodUK->Name = $post['GoodUk']['Name'];
                $goodUK->Name2 = $post['GoodUk']['Name'];
                $goodUK->Description = $post['GoodUk']['Description'];

                $goodUK->save(false);

                $good->attributes = $post['Good'];

                if($good->save(false)){

                    //Модель успешно сохранена
                }else{
                    //Произошла ошибка валидации
                }
                //TODO: добавить сообщение об успешном обновлении инфо о товаре, или о ошибке
            }
        }

        $gp = GoodsPhoto::find()->where(['ItemId' => $good->ID]);

        return $this->render(\Yii::$app->request->get("act") == "edit" ? 'editgood' : 'showgood', [
            'breadcrumbs' => $breadcrumbs,
            'good'       => $good,
            'goodUk'     => $goodUK,
            'nowCategory' => $a,
            'uploadPhoto'  =>  new UploadPhoto(),
            'additionalPhotos'  =>  new ActiveDataProvider([
                'query' =>  $gp,
                'pagination'    =>  [
                    'pageSize'  =>  0
                ]
            ])
        ]);
    }

    public function actionUploadadditionalphoto(){
        if(\Yii::$app->request->isAjax){
            \Yii::$app->response->format = 'json';
            if(isset($_FILES['GoodsPhoto'])){
                $m = Good::findOne(['ID' => \Yii::$app->request->post("ItemId")]);

                $f = UploadHelper::__upload($_FILES['GoodsPhoto'], [
                    'filename'  =>  $m ? TranslitHelper::to($m->Name).'-'.rand(0, 1000000) : ''
                ]);
                if(!empty($f)) {
                    $m = new GoodsPhoto();
                    $m->ico = $f;
                    $m->itemid = \Yii::$app->request->post("ItemId");
                    if($m->save()){
                        return [
                            'link'  =>  $m->ico,
                            'id'    =>  $m->getPrimaryKey()
                        ];
                    }
                }
            }
            return 0;
        }else{
            return $this->run('site/error');
        }
    }

    public function actionRemoveadditionalphoto(){
        if(\Yii::$app->request->isAjax){
            \Yii::$app->response->format = 'json';
            $m = GoodsPhoto::findOne([
                'ID'    =>  \Yii::$app->request->post("additionalPhotoID")
            ]);

            return $m->delete() ? 1 : 0;
        }

        return $this->run('site/error');
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

    public function actionUploadgoodphoto(){
        if(\Yii::$app->request->isAjax){
            \Yii::$app->response->format = 'json';
            $m = Good::findOne(['ID' => \Yii::$app->request->post("ItemId")]);

            if($m){
                $f = UploadHelper::__upload($_FILES['GoodsPhoto'], [
                    'filename'  =>  TranslitHelper::to($m->Name).'-'.rand(0, 1000000)
                ]);
                if($f){
                    $m->ico = $f;
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

    /**
     * @author Nikolai Gilko <n.gilko@gmail.com>
     * @return SborkaItem                       -   модель товара в заказе
     * @throws MethodNotAllowedHttpException    -   если запрос не через ajax
     * @throws NotFoundHttpException            -   если не найден заказ, или товар
     *
     * Метод позволяет добавить товар в заказ
     */
    public function actionAdditemtoorder(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Этот метод работает только через ajax!");
        }

        \Yii::$app->response->format = 'json';

        $wantedCount = \Yii::$app->request->post("ItemsCount");

        $order = History::findOne(['id' => \Yii::$app->request->post("OrderID")]);

        if(!$order){
            throw new NotFoundHttpException("Такой заказ не найден!");
        }

        $good = Good::findOne(['ID' => \Yii::$app->request->post("itemID")]);

        if(!$good){
            throw new NotFoundHttpException("Такой товар не найден!");
        }

        $item = SborkaItem::findOne(['itemID' => $good->ID, 'orderID' => \Yii::$app->request->post("OrderID")]);

        if(!$item){
            $item = new SborkaItem();
            $item->itemID = $good->ID;
            $item->name = $good->Name;
            $item->realyCount = 0;
            $item->originalPrice = $order->isOpt() ? $good->PriceOut2 : $good->PriceOut1;
            $item->orderID = $order->id;
        }

        if(($good->count >= $wantedCount) || ($good->count < $wantedCount && \Yii::$app->request->post("IgnoreMaxCount") == "true")){
            $item->count += $wantedCount;
        }elseif($good->count > 0 && \Yii::$app->request->post("IgnoreMaxCount") == "false"){
            $item->count += $good->count;
        }else{
            return $good->count;
        }

        //TODO: логику пересчёта товара (заказа?) по ценовым правилам можно впилить здесь

        $good->count = $good->count - $item->addedCount;

        if($item->save(false)){ //TODO: сделать без false
            //TODO: Дима, когда напишешь триггер для автоматического отнимания из `goods`?
            $good->save(false);
        }

        return -1;
    }

    /**
     * @return mixed
     * @throws MethodNotAllowedHttpException
     * @deprecated
     */
    public function actionChangestate(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Этот метод работает только через ajax!");
        }

        return Good::changeState(\Yii::$app->request->post("GoodID"));
    }

    /**
     * @return bool|string
     * @throws MethodNotAllowedHttpException
     * @deprecated
     */
    public function actionChangecategorystate(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Этот метод работает только через ajax!");
        }

        return Category::change(\Yii::$app->request->post("category"), 'menu_show');
    }

    /**
     * @return bool|string
     * @throws MethodNotAllowedHttpException
     * @deprecated
     */
    public function actionChangecategorycanbuy(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Этот метод работает только через ajax!");
        }

        return Category::change(\Yii::$app->request->post("category"), 'canBuy');
    }

    /**
     * @return bool|string
     * @throws MethodNotAllowedHttpException
     * @deprecated
     */
    public function actionWorkwithtrash(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Этот метод работает только через ajax!");
        }

        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Этот метод работает только через ajax!");
        }

        return Good::changeTrashState(\Yii::$app->request->post("GoodID"));
    }

    /**
     * @return bool|string
     * @throws MethodNotAllowedHttpException
     * @deprecated
     */
    public function actionWorkwithcategorytrash(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Этот метод работает только через ajax!");
        }

        return Good::changeTrashState(\Yii::$app->request->post("CategoryID"));
    }

    public function actionChangecategoryvalue(){
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

    public function actionChangegoodvalue(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Этот метод работает только через ajax!");
        }

        $attribute = \Yii::$app->request->post("attribute");

        $good = Good::findOne(['id' => \Yii::$app->request->post("goodID")]);

        if(!$good){
            throw new NotFoundHttpException("Товар не найден!");
        }

        $good->$attribute = \Yii::$app->request->post("value");

        $good->save(false);
    }

    public function actionUpdatecategorysort(){
        if(\Yii::$app->request->isAjax){
            \Yii::$app->response->format = "json";
            $a = \Yii::$app->request->post("data");
            $b = \Yii::$app->request->post("category");
            $len = (strlen($b) + 3);

            $a = array_flip($a);

            $c = Category::find()->where(['like', 'Code', $b.'%', false]);
            $c->andWhere(['LENGTH(`Code`)' => $len]);
            $c->orderBy('listorder, ID ASC');
            $c = $c->all();
            $d = [];

            foreach($c as $cc){
                $d[] = $cc->listorder;
            }

            foreach($c as $cc){
                $cc->listorder = $a[$cc->ID];
                $cc->save(false);
            }

            return $d;
        }else{
            return $this->run('site/error');
        }
    }

}
