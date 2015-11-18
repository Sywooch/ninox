<?php

namespace backend\modules\goods\controllers;

use common\components\UploadHelper;
use common\helpers\TranslitHelper;
use common\models\Category;
use common\models\CategorySearch;
use common\models\CategoryUk;
use common\models\Good;
use common\models\GoodSearch;
use common\models\GoodsPhoto;
use common\models\GoodUk;
use common\models\History;
use common\models\SborkaItem;
use common\models\UploadPhoto;
use sammaye\audittrail\AuditTrail;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use backend\controllers\SiteController as Controller;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class DefaultController extends Controller
{

    public function actionIndex(){
        $category = \Yii::$app->request->get("category");
        $categoryLength = $category != '' ? (strlen($category) + 3) : 3;
        $breadcrumbs = $goodsCount = [];
        $enabledGoods = $disabledGoods = 0;

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
        $filename = \Yii::$app->request->get("filename");

        if(!empty($filename)){
            $filename = \Yii::getAlias('@webroot').'/files/importedPrices/'.$filename;
            if(!file_exists($filename)){
                throw new NotFoundHttpException("Файл не найден!");
            }

            $data = [];

            $xls = \PHPExcel_IOFactory::load($filename);

            return $this->render('import_table', [
                'data'      =>  $data,
                'filename'  =>  \Yii::$app->request->get("filename"),
                'columns'   =>  $xls->getActiveSheet()->getHighestColumn(),
                'xls'       =>  $xls->getActiveSheet()->toArray()
            ]);
        }

        return $this->render('import_index');
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

    public function actionShowgood($param){
        if(!filter_var($param, FILTER_VALIDATE_INT)){
            return $this->run('site/error');
        }

        $good = Good::findOne(['ID' => $param]);
        $goodUK = GoodUk::findOne(['ID' => $param]);

        if(empty($good)){
            return $this->run('site/error');
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

    public function actionAdditemtoorder(){
        if(\Yii::$app->request->isAjax){
            \Yii::$app->response->format = 'json';
            // Сначала ищем такой заказ
            $h = History::findOne(['id' => \Yii::$app->request->post("OrderID")]);

            if($h){
                // Потом ищем чтобы в этом заказе не было такого товара
                $good = Good::findOne(['ID' => \Yii::$app->request->post("itemID")]);
                $m = SborkaItem::findOne(['itemID' => $good->ID, 'orderID' => \Yii::$app->request->post("OrderID")]);

                if($m){
                    // Если такой товар есть в заказе, то:
                    // Если есть на складе в полном объёме - просто суммируем колличество желаемое и максимальное
                    // Если есть на складе но частично - возвращаем окно типа "на складе всего 5 штук, добавить 5 или указаное вами колл-во?"
                    // Если нету - возвращаем окно типа "на складе это кончилось - всё равно добавить?"
                    $m->count += \Yii::$app->request->post("ItemsCount");
                }else{
                    // Если такой товар есть в полном объёме, и в заказе его ещё нет - добавляем
                    $m = new SborkaItem();
                    $m->itemID = $good->ID;
                    $m->name = $good->Name;
                    $m->count = \Yii::$app->request->post("ItemsCount");
                    $m->realyCount = 0;
                    $m->originalPrice = $h->isOpt() ? $good->PriceOut2 : $good->PriceOut1;
                    $m->orderID = \Yii::$app->request->post("OrderID");
                }

                $good->count = $good->count - \Yii::$app->request->post("ItemsCount");

                $mm = $m->save(false);

                if($mm){
                    $good->save(false);
                }

                return $mm;
            }
        }else{
            return $this->run('site/error');
        }
    }

    public function actionChangestate(){
        if(\Yii::$app->request->isAjax){
            return Good::changeState(\Yii::$app->request->post("GoodID"));
        }else{
            return $this->run('site/error');
        }
    }

    public function actionChangecategorystate(){
        if(\Yii::$app->request->isAjax){
            return Category::change(\Yii::$app->request->post("category"), 'menu_show');
        }else{
            return $this->run('site/error');
        }
    }

    public function actionChangecategorycanbuy(){
        if(\Yii::$app->request->isAjax){
            return Category::change(\Yii::$app->request->post("category"), 'canBuy');
        }else{
            return $this->run('site/error');
        }
    }

    public function actionWorkwithtrash(){
        if(\Yii::$app->request->isAjax){
            return Good::changeTrashState(\Yii::$app->request->post("GoodID"));
        }else{
            return $this->run('site/error');
        }
    }

    public function actionWorkwithcategorytrash()
    {
        if (\Yii::$app->request->isAjax) {
            return Good::changeTrashState(\Yii::$app->request->post("CategoryID"));
        } else {
            return $this->run('site/error');
        }
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

    public function actionSimplegoodedit(){
        \Yii::$app->response->format = 'json';
        return \Yii::$app->request->post();
    }

}
