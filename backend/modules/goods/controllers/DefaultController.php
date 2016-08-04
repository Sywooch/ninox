<?php

namespace backend\modules\goods\controllers;

use backend\components\S3Uploader;
use backend\controllers\SiteController as Controller;
use backend\models\Good;
use backend\models\History;
use backend\models\Category;
use backend\modules\goods\assets\GoodsModuleAsset;
use backend\modules\goods\models\GoodAttributesForm;
use backend\modules\goods\models\GoodExportForm;
use backend\modules\goods\models\GoodMainForm;
use backend\models\GoodSearch;
use backend\modules\goods\models\GoodVideoForm;
use common\helpers\TranslitHelper;
use common\helpers\UploadHelper;
use common\models\GoodOptions;
use common\models\GoodOptionsValue;
use common\models\GoodOptionsVariant;
use common\models\GoodTranslation;
use common\models\PriceListImport;
use sammaye\audittrail\AuditTrail;
use yii\base\ErrorException;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnsupportedMediaTypeHttpException;
use yii\widgets\ActiveForm;

class DefaultController extends Controller
{

    public function beforeAction($action)
    {
        GoodsModuleAsset::register($this->getView());

        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    public function actionIndex(){
        $searchParams = \Yii::$app->request->get();

        $currentCategory = new Category();

        if(isset($searchParams['category'])){
            $currentCategory = Category::findOne(['Code' => $searchParams['category']]);
        }

        $goodsSearch = new GoodSearch();

        $this->getView()->params['breadcrumbs'][] = [
            'label' =>  \Yii::t('backend', 'Категории'),
            'url'   =>  '/categories',
        ];

        $this->getView()->params['breadcrumbs'] = $this->buildBreadcrumbs($currentCategory);

        if(!empty($currentCategory)){
            $searchID = $currentCategory->ID;

            if(\Yii::$app->request->get('withSubcategories')){
                $subCategoriesIDs = [];

                $subCategories = Category::find()
                    ->select('ID')
                    ->where(['like', 'Code', $currentCategory->Code.'%', false]);


                foreach($subCategories->each() as $subCategory){
                    $subCategoriesIDs[] = $subCategory->ID;
                }

                if(!empty($subCategoriesIDs)){
                    $searchID = $subCategoriesIDs;
                }
            }
            
            $searchParams = array_merge($searchParams, ['category' => $searchID]);
        }

        $this->getView()->params['breadcrumbs'][] = [
            'label' =>  \Yii::t('backend', 'Товары')
        ];

        return $this->render('goods', [
            'goods'         => $goodsSearch->search($searchParams),
            'goodsCount'    => [
                'enabled'   =>  $goodsSearch->search(array_merge($searchParams, ['smartFilter' => 'enabled']), true)->count(),
                'disabled'  =>  $goodsSearch->search(array_merge($searchParams, ['smartFilter' => 'disabled']), true)->count()
            ],
            'nowCategory'   => $currentCategory,
        ]);
    }

    /**
     * Импорт прайслистов
     *
     * @return array|bool|string
     * @throws \yii\base\InvalidParamException
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionImport(){
        $priceList = PriceListImport::findOne(\Yii::$app->request->get('fileid'));

        if(\Yii::$app->request->isAjax){
            switch(\Yii::$app->request->post('action')){
                case 'renameFile':
                    $file = PriceListImport::findOne(\Yii::$app->request->post('id'));

                    if(!$file){
                        throw new NotFoundHttpException('Такой файл не найден!');
                    }

                    $file->name = \Yii::$app->request->post('value');
                    $file->save(false);
                    break;
            }
        }

        if($priceList){
            $data = $importInfo = $generatedModels = $columns = $keys = $attributes = [];

            $xls = \PHPExcel_IOFactory::load(\Yii::getAlias('@webroot').'/files/importedPrices/'.$priceList->file);

            $tableRows = $xls->getActiveSheet()->toArray();

            if(array_key_exists('withHeaders', $priceList->configuration)){
                $header = $tableRows[0];
                unset($tableRows[0]);
                sort($tableRows);
            }else{
                $header = [];
                $highestCol = $xls->getActiveSheet()->getHighestColumn();
                for($letter = 'A'; $letter <= $highestCol; $letter++){
                    $header[] = $letter;
                }
            }

            if(\Yii::$app->request->post('PriceListImportTable')){
                $columns = \Yii::$app->request->post('PriceListImportTable')['columns'];

                foreach($columns as $key => $subarray){
                    if(!empty($subarray['key'])){
                        $keys[$key] = $subarray['attribute'];
                    }

                    if(!empty($subarray['attribute'])){
                        $attributes[$key] = $subarray['attribute'];
                    }
                }

                $header = $attributes;
            }

            $i = 0;

            while(count($tableRows) != $i){
                $createdModel = new \stdClass();

                foreach($tableRows[$i] as $key => $value) {
                    if ($value == null) {
                        $value = '';
                    }

                    if(array_key_exists($key, $header)){
                        $param = $header[$key];

                        if(!is_int($param)){
                            if(in_array($param, ['GroupID', 'count'])){
                                switch(gettype($value)){
                                    case 'string':
                                    case 'double':
                                    case 'float':
                                        $value = (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT);
                                        break;
                                }
                            }elseif(in_array($param, ['BarCode2'])){
                                switch(gettype($value)){
                                    case 'double':
                                    case 'float':
                                        $value = (string) preg_replace('/\.0^/', '', filter_var($value, FILTER_SANITIZE_NUMBER_INT));
                                        break;
                                }
                            }
                        }

                        if(!empty($param)){
                            $createdModel->$param = $value;
                        }
                    }
                }

                $generatedModels[] = $createdModel;

                $i++;

                if($i == count($tableRows)){
                    break;
                }
            }

            $dataProvider = new ArrayDataProvider();
            $dataProvider->setModels($generatedModels);

            if(\Yii::$app->request->post('PriceListImportTable')){
                $query = Good::find();

                foreach($keys as $keyKey => $keyAttribute){
                    $keysValues = $keysModels = [];
                    $param = $header[$keyKey];

                    foreach($dataProvider->getModels() as $model){
                        if(isset($model->$param)){
                            $keysModels[$keyAttribute][$model->$param] = $model;
                            $keysValues[] = $model->$param;
                        }
                    }

                    if(!empty($keysValues)){
                        $query->andWhere(['in', $keyAttribute, $keysValues]);
                    }
                }

                $query->with('translations')->with('photos');

                $updated = $notUpdated = $added = 0;

                $notUpdatedGoods = $dataProvider->getModels();

                //Обновление уже существующих товаров
                if(!empty($query->where)){
                    foreach($query->each() as $good){
                        foreach($keys as $key){
                            if(
                                array_key_exists($key, $keysModels) &&
                                array_key_exists($good->$key, $keysModels[$key]) &&
                                !empty($keysModels[$key][$good->$key])
                            ){
                                $goodRow = $keysModels[$key][$good->$key];

                                foreach($header as $param){
                                    try{
                                        if(isset($goodRow->$param)){
                                            $good->$param = $goodRow->$param;
                                        }
                                    }catch (ErrorException $e){

                                    }
                                }

                                foreach($notUpdatedGoods as $row => $notUpdatedGood){
                                    if(isset($notUpdatedGood->$key) && $notUpdatedGood->$key == $good->$key){
                                        unset($notUpdatedGoods[$row]);
                                    }
                                }
                            }
                        }

                        if($good->count > 0){
                            $good->enabled = 1;
                        }

                        if($good->save(false)){
                            $updated++;
                        }else{
                            $notUpdated++;
                        }
                    }
                }

                foreach($notUpdatedGoods as $notUpdatedGood){
                    $good = new Good();

                    foreach($header as $param){
                        try{
                            $good->$param = $notUpdatedGood->$param;
                        }catch (ErrorException $e){

                        }
                    }

                    $good->save(false);
                }

                $importInfo = [
                    'updated'   =>  $updated,
                    'notUpdated'=>  $notUpdated,
                    'added'     =>  count($notUpdatedGoods),
                    'totalCount'=>  $i
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

    /**
     * @return string
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionLog(){
        if(!\Yii::$app->request->isAjax){
            throw new BadRequestHttpException("Данный метод возможен только через ajax!");
        }

        $dataProvider = new ActiveDataProvider([
            'query' =>  AuditTrail::find()->where([
                'model'  =>  Good::className()
            ])
        ]);

        $dataProvider->setSort([
            'default'   =>  [
                'id'    =>  SORT_DESC
            ]
        ]);

        return $this->render('log', [
            'dataProvider'  =>  $dataProvider
        ]);
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
            ->joinWith('translations')
            ->with('photos')
            ->where([GoodTranslation::tableName().'.language' => \Yii::$app->language])
            ->andFilterWhere(['or',
                ['like', GoodTranslation::tableName().'.name', $item],
                ['like', 'goods.Code', $item.'%', false],
                ['like', 'goods.BarCode1', $item],
                ['like', 'goods.BarCode2', $item]
            ])
            ->orderBy(['goods.BarCode2' => SORT_DESC])
            ->limit(10);

        $return = [];

        foreach($goods->each() as $good) {
            $tArray = [
                'name'      =>  $good->Name,
                'category'  =>  !empty($good->category) ? $good->category->Name : '(без категории)',
                'photo'     =>  $good->photo,
                'code'      =>  $good->Code,
                'ID'        =>  $good->ID,
                'disabled'  =>  $good->enabled == 0,
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
     */
    public function actionAdd(){
        $good = new Good();
        $category = \Yii::$app->request->get("category");

        if(isset($category)){
            $category = Category::findOne($category);

            if(!empty($category)){
                $good->GroupID = $category->ID;

                $this->getView()->params['breadcrumbs'][] = [
                    'label' =>  'Категории',
                    'url'   =>  Url::toRoute(['/categories'])
                ];

                if (count($category->parents) >= 1) {
                    $parents = array_reverse($category->parents);

                    foreach($parents as $parentCategory) {
                        if(!empty($parentCategory)){
                            $this->getView()->params['breadcrumbs'][] = [
                                'label' =>  $parentCategory->Name,
                                'url'   => Url::toRoute(['/categories', 'category' => $parentCategory->Code])
                            ];
                        }
                    }
                }

                $this->getView()->params['breadcrumbs'][] = [
                    'label' =>  $category->Name,
                    'url'   =>  Url::toRoute(['/categories', 'category' => $category->Code])
                ];
            }
        }

        $this->getView()->params['breadcrumbs'][] = [
            'label' =>  'Добавление товара',
        ];

        if($category instanceof Category == false){
            $category = new Category();
        }

        $goodMainForm = new GoodMainForm();
        $goodExportForm = new GoodExportForm();

        $goodMainForm->loadGood($good);

        if(\Yii::$app->request->post("GoodMainForm")){
            $goodMainForm->load(\Yii::$app->request->post());

            if($goodMainForm->save()) {
                $good = $goodMainForm->good;

                $goodMainForm = new GoodMainForm(['isSaved' => true]);
            }
        }

        $goodAttributesForm = new GoodAttributesForm();

        if($good){
            $goodAttributesForm->loadGood($good);
        }

        if(\Yii::$app->request->post("GoodOption") && !empty($good->ID)){
            $options = $deleteOptions = [];

            foreach(\Yii::$app->request->post("GoodOption") as $optionArray){
                if(!empty($optionArray['value'])){
                    $options[$optionArray['option']] = $optionArray['value'];
                }
            }

            foreach($good->options as $optionArray){
                if(!isset($options[$optionArray['optionID']])){
                    $deleteOptions[] = $optionArray['optionID'];
                }
            }

            foreach($options as $option => $value){
                $tOption = GoodOptionsValue::findOne(['good' => $good->ID, 'option' => $option]);

                if(!$tOption){
                    $tOption = new GoodOptionsValue([
                        'good'      =>  $good->ID,
                        'option'    =>  $option,
                        'value'     =>  $value
                    ]);
                }else{
                    $tOption->value = $value;
                }

                $tOption->save(false);

                unset($options[$option]);
            }

            GoodOptionsValue::deleteAll(['and', ['in', 'option', $deleteOptions], ['good' => $good->ID]]);

            $good->getOptions(true);
        }

        if(\Yii::$app->request->get("mode") != 'lot' && $goodMainForm->isSaved){
            $this->redirect('/goods/view/'.$good->ID);
        }

        $this->getView()->title = 'Добавление товара';

        return $this->render('edit', [
            'good'              =>  $good,
            'goodMainForm'      =>  $goodMainForm,
            'goodAttributesForm'=>  $goodAttributesForm,
            'goodExportForm'    =>  $goodExportForm,
            'nowCategory'       =>  $category,
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
     * @param Good|static $good
     * @param int $order
     *
     * @return bool
     */
    public function deletePhoto($good, $order){
        return $good->deletePhoto($order);
    }

    /**
     * @param mixed $file
     * @param Good|static $good
     *
     * @return mixed
     */
    public function addPhoto($file, $good){
        //$file = UploadHelper::__upload($file);

        $uploader = new S3Uploader();

        if(!empty($good->name)){
            $filename = substr(TranslitHelper::to($good->name), 0, 32);
        }else{
            $filename = \Yii::$app->security->generateRandomString(32);
        }

        $filename .= "-".\Yii::$app->security->generateRandomString(8);

        $src = imagecreatefromjpeg($file['tmp_name'][0]);
        list($width, $height) = getimagesize($file['tmp_name'][0]);
        $tmp = imagecreatetruecolor(250, 187);
        imagecopyresized($tmp, $src, 0, 0, 0, 0, 250, 187, $width, $height);
        imagejpeg($tmp, $file['tmp_name'][0].'-sm');
        $uploader->upload(
            [
                'name'      =>  [$file['name'][0]],
                'type'      =>  [$file['type'][0]],
                'tmp_name'  =>  [$file['tmp_name'][0].'-sm']
            ],
            [
                'name' => $uploader->setName($filename, $file),
                'directory' => 'img/catalog/sm/',
                'fullReturn' => true
            ]
        );

        return $good->addPhoto($uploader->upload($file, [
            'name' => $uploader->setName($filename, $file)
        ]));
    }

    /**
     * @param int[] $newOrder
     * @param Good|static $good
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

        if(!$good){
            throw new NotFoundHttpException("Товар с идентификатором {$param} не найден!");
        }

        $request = \Yii::$app->request;


        if($request->get("act") == "edit"){
            $goodMainForm = new GoodMainForm();
            $goodAttributesForm = new GoodAttributesForm();
            $goodExportForm = new GoodExportForm();

            $goodMainForm->loadGood($good);

            if($request->post("GoodMainForm") && $goodMainForm->load($request->post())){
                $goodMainForm->save();

                $good = Good::findOne($param);

                $goodMainForm->loadGood($good);
            }

            $goodAttributesForm->loadGood($good);
            $goodExportForm->loadGood($good);



            if($request->post("GoodOption")){
                $options = $deleteOptions = [];

                foreach($request->post("GoodOption") as $optionArray){
                    if(!empty($optionArray['value'])){
                        $options[$optionArray['option']] = $optionArray['value'];
                    }
                }

                foreach($good->options as $optionArray){
                    if(!isset($options[$optionArray['optionID']])){
                        $deleteOptions[] = $optionArray['optionID'];
                    }
                }

                foreach($options as $option => $value){
                    $tOption = GoodOptionsValue::findOne(['good' => $good->ID, 'option' => $option]);

                    if(!$tOption){
                        $tOption = new GoodOptionsValue([
                            'good'      =>  $good->ID,
                            'option'    =>  $option,
                            'value'     =>  $value
                        ]);
                    }else{
                        $tOption->value = $value;
                    }

                    $tOption->save(false);

                    unset($options[$option]);
                }

                GoodOptionsValue::deleteAll(['and', ['in', 'option', $deleteOptions], ['good' => $good->ID]]);

                $good->getOptions(true);
            }
        }

        $this->getView()->params['breadcrumbs'] = $this->buildBreadcrumbs($good->category, $good);

        $category = $good->category;

        if($category instanceof Category == false){
            $category = new Category();
        }

        if($request->get("act") == 'edit'){
            return $this->render('edit', [
                'good'              =>  $good,
                'nowCategory'       =>  $category,
                'goodMainForm'      =>  $goodMainForm,
                'goodAttributesForm'=>  $goodAttributesForm,
                'goodExportForm'    =>  $goodExportForm,
            ]);
        }

        return $this->render('view', [
            'good'              => $good,
            'nowCategory'       => $category,
        ]);
    }

    /**
     * Меняет bool значения товара
     *
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionToggle(){
        if(!\Yii::$app->request->isAjax){
            throw new BadRequestHttpException("Данный метод возможен только через ajax!");
        }

        $good = Good::findOne(\Yii::$app->request->post("goodID"));

        if(!$good){
            throw new NotFoundHttpException("Такой товар не найден!");
        }

        $attribute = \Yii::$app->request->post("attribute");

        if(!isset($good->$attribute)){
            throw new NotFoundHttpException("У товара {$good->ID} не найден аттрибут {$attribute}!");
        }

        $good->$attribute = $good->$attribute == 1 ? 0 : 1;

        \Yii::$app->response->format = 'json';

        if($good->validate([$attribute]) &&  $good->save(false)){
            return $good->$attribute;
        }

        return 0;
    }

    /**
     * Меняет не bool значения товара
     *
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionChangevalue(){
        if(!\Yii::$app->request->isAjax){
            throw new BadRequestHttpException("Данный метод возможен только через ajax!");
        }

        $good = Good::findOne(\Yii::$app->request->post("goodID"));

        if(!$good){
            throw new NotFoundHttpException("Такой товар не найден!");
        }

        $attribute = \Yii::$app->request->post("attribute");

        if(!isset($good->$attribute)){
            throw new NotFoundHttpException("У товара {$good->ID} не найден аттрибут {$attribute}!");
        }

        $good->$attribute = \Yii::$app->request->post("value");

        if($good->validate($attribute)){
            return $good->save(false);
        }

        return false;
    }

    /**
     * Делает хлебные крошки
     *
     * @param Category $category модель категории
     * @param Good|static $good Модель товара
     *
     * @return array Хлебные крошки
     * @throws \yii\base\InvalidParamException
     */
    public function buildBreadcrumbs($category, $good = null){
        $breadcrumbs = [];

        $breadcrumbs[] = [
            'label' =>  'Категории',
            'url'   =>  '/categories'
        ];

        if(is_object($category) && !$category->isNewRecord){
            if (count($category->parents) >= 1) {
                $parents = $category->parents;

                foreach($parents as $parentCategory) {
                    if(!empty($parentCategory)){
                        $breadcrumbs[] = [
                            'label' => $parentCategory->Name,
                            'url'   => Url::toRoute(array_merge(['/categories'], \Yii::$app->request->get(), ['category' => $parentCategory->Code]))
                        ];
                    }
                }
            }

            $breadcrumbs[] = [
                'label' =>  $category->Name,
                'url'   => Url::toRoute(array_merge(['/categories'], \Yii::$app->request->get(), ['category' => $category->Code]))
            ];
        }

        if(!empty($good)){
            $breadcrumbs[] = $good->name;
        }

        return $breadcrumbs;
    }

    /**
     * Ajax метод для работы с фильтрами
     *
     * @return array
     * @throws \yii\web\BadRequestHttpException
     */
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
            case 'newOption':
                $option = new GoodOptions([
                    'name'  =>  \Yii::$app->request->post("value")
                ]);

                $option->save(false);

                return ['id' => $option->id, 'name' => $option->name];
                break;
            case 'newAttributeOption':
                $option = new GoodOptionsVariant([
                    'option'    =>  \Yii::$app->request->post("option"),
                    'value'     =>  \Yii::$app->request->post("value")
                ]);

                $option->save(false);

                return ['id' => $option->id, 'name' => $option->value];
                break;
        }
    }

    /**
     * @return bool
     * @throws \yii\web\MethodNotAllowedHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionAddtoorder(){
        $request = \Yii::$app->request;

        if(!$request->isAjax){
            throw new MethodNotAllowedHttpException("Этот метод работает только через ajax!");
        }

        $order = History::findOne($request->post("orderID"));

        if(!$order){
            throw new NotFoundHttpException("Заказ с идентификатором {$request->post("orderID")} не найден!");
        }

        $good = Good::findOne($request->post("goodID"));

        if(!$good){
            throw new NotFoundHttpException("Товар с идентификатором {$request->post("goodID")} не найден!");
        }

        \Yii::$app->response->format = 'json';

        $addedCount = 0;

        if($good->count >= $request->post("itemsCount") || ($good->count < $request->post("itemsCount") && $request->post("ignoreMaxCount") == "true")){
            $addedCount = $request->post("itemsCount");
        }elseif($good->count > 0 && \Yii::$app->request->post("ignoreMaxCount") == "false"){
            $addedCount = $good->count;
        }

        if($addedCount != 0){
            $return = [
                'status'    =>  $good->addToOrder($order, $addedCount)
            ];
        }else{
            $return = [
                'status'    =>  'notEnough',
                'data'      =>  [
                    'have'  =>  $good->count
                ]
            ];
        }

        return $return;
    }

    public function actionChangegoodvalue(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Этот метод работает только через ajax!");
        }

        $goodID = \Yii::$app->request->post("goodID");

        $good = Good::findOne(['id' => $goodID]);

        if(!$good){
            throw new NotFoundHttpException("Товар с идентификатором {$goodID} не найден!");
        }

        $attribute = \Yii::$app->request->post("attribute");
        $value = \Yii::$app->request->post("value");

        if(empty(\Yii::$app->request->post("value")) || in_array($attribute, ['deleted', 'enabled'])){
            $value = ($good->$attribute == 1 ? 0 : 1);
        }

        $good->$attribute = $value;

        $good->save(false);

        return $good->$attribute;
    }

    /**
     * Валиация url прикрепляемого к товару видео
     * @return array
     * @throws UnsupportedMediaTypeHttpException
     */
    public function actionValidateVideoUrl(){
        if(!\Yii::$app->request->isAjax){
            throw new UnsupportedMediaTypeHttpException("Этот запрос возможен только через ajax!");
        }

        $videoForm = new GoodVideoForm();

        $videoForm->load(\Yii::$app->request->post());

        if(!empty(\Yii::$app->request->post("ajax"))){
            \Yii::$app->response->format = 'json';
            return ActiveForm::validate($videoForm);
        }
    }

    /**
     * Добавление видео к товару
     * @return bool
     * @throws UnsupportedMediaTypeHttpException
     */
    public function actionAddVideo(){
        if(!\Yii::$app->request->isAjax){
            throw new UnsupportedMediaTypeHttpException("Этот запрос возможен только через ajax!");
        }
        $videoForm = new GoodVideoForm();
        $videoForm->load(\Yii::$app->request->post());
        return $videoForm->save();
    }
}
