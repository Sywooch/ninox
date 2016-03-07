<?php

namespace backend\modules\goods\controllers;

use backend\models\GoodPhoto;
use backend\modules\goods\models\GoodAttributesForm;
use backend\modules\goods\models\GoodExportForm;
use backend\modules\goods\models\GoodMainForm;
use common\helpers\UploadHelper;
use common\helpers\TranslitHelper;
use common\models\Category;
use common\models\CategorySearch;
use common\models\CategoryUk;
use backend\models\Good;
use common\models\GoodOptionsVariant;
use common\models\GoodSearch;
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
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
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
                            'url'   =>  Url::toRoute(['/categories', 'category' => $parentCategory->Code, 'smartfilter' => \Yii::$app->request->get("smartfilter")])
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

    /**
     * Импорт прайслистов
     *
     * @return array|bool|string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionImport(){
        $priceList = PriceListImport::findOne(\Yii::$app->request->get("fileid"));

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

        if($priceList){
            $data = $importInfo = [];

            $xls = \PHPExcel_IOFactory::load(\Yii::getAlias('@webroot').'/files/importedPrices/'.$priceList->file);

            $models = $xls->getActiveSheet()->toArray();

            $dataProvider = new ArrayDataProvider();

            if(isset($priceList->configuration['withHeaders'])){
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

                $priceList->imported = 1;
                $priceList->importedDate = date('Y-m-d H:i:s');

                $priceList->save(false);
            }

            return $this->render('import_table', [
                'data'          =>  $data,
                'columns'       =>  $xls->getActiveSheet()->getHighestColumn(),
                'filename'      =>  $priceList->name,
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

            $priceList = new PriceListImport([
                'file'  =>  $file['filename'],
                'format'=>  $file['mime'],
                'name'  =>  $file['original_filename']
            ]);

            if($priceList->save()){
                return [
                    'id'    =>  $priceList->id,
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

    /**
     * @deprecated
     * @return array
     * @throws \yii\web\MethodNotAllowedHttpException
     */
    public function actionSearchgoods(){
        return $this->actionSearch();
    }

    /**
     * Ищет товары
     *
     * @return array массив товаров для TypeAhead виджета
     * @throws \yii\web\MethodNotAllowedHttpException
     */
    public function actionSearch(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Этот метод работает только через ajax!");
        }

        \Yii::$app->response->format = "json";

        $item = \Yii::$app->request->get("string");

        $goods = Good::find()
            ->where(['like', 'Name', $item])
            ->orWhere(['like', 'Code', $item.'%', false])
            ->orWhere(['like', 'BarCode1', $item])
            ->orWhere(['like', 'BarCode2', $item])
            ->limit(10);

        $return = [];

        foreach($goods->each() as $good) {
            $tArray = [
                'name'      =>  $good->Name,
                'category'  =>  !empty($good->category) ? $good->category->Name : '(без категории)',
                'photo'     =>  $good->ico,
                'code'      =>  $good->Code,
                'ID'        =>  $good->ID,
                'disabled'  =>  $good->show_img == 0,
                'ended'     =>  $good->count <= 0,
                'sale'      =>  $good->discountType != 0
            ];

            if(!empty(trim($good->BarCode2))){
                $tArray['vendorCode'] = $good->BarCode2;
            }

            $return[] = $tArray;
        }

        return $return;
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
                'url'   =>  Url::toRoute(['/categories'])
            ];

            if (sizeof($p) >= 1) {
                $p = array_reverse($p);

                foreach ($p as $c) {
                    if ($c != '') {
                        $breadcrumbs[] = [
                            'label' => $c->Name,
                            'url' => Url::toRoute(['/categories', 'category' => $c->Code])
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
     * Операции с фотографиями для товаров
     *
     * @return bool|mixed|null
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionPhoto(){
        if(!\Yii::$app->request->isAjax){
            throw new BadRequestHttpException("Данный метод доступен только через ajax!");
        }

        $result = null;

        $good = Good::findOne(\Yii::$app->request->post("key"));

        if(!$good){
            throw new NotFoundHttpException("Такой товар не найден!");
        }

        switch(\Yii::$app->request->get('act')){
            case 'upload':
                $result = $this->addPhoto($_FILES['goodPhoto'], $good);
                break;
            case 'delete':
                $result = $this->deletePhoto($good, \Yii::$app->request->post("order"));
                break;
            case 'reorder':
                $result =  $this->reorderPhotos(\Yii::$app->request->post("items"), $good);
                break;
        }

        \Yii::$app->response->format = 'json';

        return $result;
    }

    /**
     * @param Good $good
     * @param int $order
     *
     * @return bool
     */
    public function deletePhoto($good, $order){
        return $good->deletePhoto($order);
    }

    /**
     * @param mixed $file
     * @param Good $good
     *
     * @return mixed
     */
    public function addPhoto($file, $good){
        $file = UploadHelper::__upload($file);

        return $good->addPhoto($file);
    }

    /**
     * @param int[] $newOrder
     * @param Good $good
     *
     * @return bool
     */
    public function reorderPhotos($newOrder, $good){
        $photos = [];

        foreach($good->photos as $photo){
            $photos[$photo->order] = $photo;
        }

        foreach($newOrder as $newPosition => $oldPosition){
            $newPosition++;

            $photos[$oldPosition]->order = $newPosition;
            $photos[$oldPosition]->save(false);
        }

        return true;
    }

    /**
     * @param $param
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView($param){
        $good = Good::findOne($param);
        $request = \Yii::$app->request;

        if(!$good){
            throw new NotFoundHttpException("Товар с ID ".$param." не найден!");
        }

        //Начало хлебных крошек
        $category = Category::findOne($good->GroupID);
        $parents = Category::getParentCategories($category->Code);

        $this->getView()->params['breadcrumbs'][] = [
            'label' =>  'Категории',
            'url'   =>  '/categories'
        ];

        if (sizeof($parents) >= 1) {
            $parents = array_reverse($parents);

            foreach ($parents as $parentCategory) {
                if ($parentCategory != '') {
                    $this->getView()->params['breadcrumbs'][] = [
                        'label' => $parentCategory->Name,
                        'url'   => Url::toRoute(['/categories', 'category' => $parentCategory->Code])
                    ];
                }
            }
        }

        $this->getView()->params['breadcrumbs'][] = [
            'label' =>  $category->Name,
            'url'   => Url::toRoute(['/categories', 'category' => $category->Code])
        ];

        $this->getView()->params['breadcrumbs'][] = $good->Name;
        //Конец хлебных крошек

        $goodMainForm = new GoodMainForm();
        $goodAttributesForm = new GoodAttributesForm();
        $goodExportForm = new GoodExportForm();

        $goodMainForm->loadGood($good);
        $goodAttributesForm->loadGood($good);
        $goodExportForm->loadGood($good);

        if($request->get("act") == "edit"){
            if($request->post("GoodMainForm") && $goodMainForm->load($request->post())){
                $goodMainForm->save();
            }

            return $this->render('edit', [
                'good'              =>  $good,
                //'goodUk'          =>  $goodUK,
                'goodMainForm'      =>  $goodMainForm,
                'goodAttributesForm'=>  $goodAttributesForm,
                'goodExportForm'    =>  $goodExportForm,
                'nowCategory'       =>  $category,
                'uploadPhoto'       =>  new UploadPhoto(),
                'additionalPhotos'  =>  new ActiveDataProvider([
                    'query' =>  GoodPhoto::find()->where(['ItemId' => $good->ID]),
                    'pagination'    =>  [
                        'pageSize'  =>  0
                    ]
                ])
            ]);
        }

        return $this->render('view', [
            'good'       => $good,
            'goodUk'     => new GoodUk(),
            'nowCategory' => $good->category,
            'uploadPhoto'  =>  new UploadPhoto(),
            'additionalPhotos'  =>  new ArrayDataProvider([
                'models'    =>  $good->photos
            ])
        ]);
    }

    public function actionFilters(){
        if(!\Yii::$app->request->isAjax){
            throw new BadRequestHttpException();
        }

        \Yii::$app->response->format = 'json';

        switch(\Yii::$app->request->get("act")){
            case 'getattributes':
                $variants = [];

                foreach(GoodOptionsVariant::getList(\Yii::$app->request->post("depdrop_parents")) as $id => $value){
                    $variants[] = [
                        'id'    =>  $id,
                        'name'  =>  $value
                    ];
                }

                return ['output' => $variants, 'selected' => \Yii::$app->request->post("selected")];
                break;
            //case ''
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
    public function actionWorkwithtrash(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Этот метод работает только через ajax!");
        }

        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Этот метод работает только через ajax!");
        }

        return Good::changeTrashState(\Yii::$app->request->post("GoodID"));
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
}
