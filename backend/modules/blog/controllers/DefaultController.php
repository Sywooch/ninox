<?php

namespace app\modules\blog\controllers;

use Yii;
use app\models\Articles;
use app\models\ArticlesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\UploadHelper;
use app\helpers\TranslitHelper;

/**
 * DefaultController implements the CRUD actions for Articles model.
 */
class DefaultController extends Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className()
            ],
        ];
    }

    /**
     * Lists all Articles models.
     * @return mixed
     */
    public function actionIndex($p1 = '', $p2 = '')
    {
        if($p1 == "" && $p2 == ""){
            return $this->runAction('actionindex');
        }else{
            if($p2 != ""){
                return $this->runAction($p1, [
                    'id' =>  $p2
                ]);
            }else{
                return $this->runAction($p1);
            }
        }
    }

    public function actionActionindex(){
        $searchModel = new ArticlesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Articles model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id = 1)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Articles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Articles();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(LinkController::getForAdmin('view').$model->id);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Articles model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id = 1)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(LinkController::getForAdmin('view').$model->id);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionUploadphoto(){
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = 'json';
            $model = Articles::findOne(['id' => Yii::$app->request->post('ItemId')]);

            if($model){
                $f = UploadHelper::__upload($_FILES['ArticlesPhoto'], [
                    'filename'  =>  TranslitHelper::to($model->title).'-'.rand(0, 1000000),
                    'directory' => 'img/blog/articles'
                ]);
                if($f){
                    $model->ico = $f;
                    if($model->save(false)){ //TODO: потом поровнять так, чтобы было норм, с валидацией, ёпта
                        return [
                            'link'  =>  $f
                        ];
                    }
                }
            }


            return [
                'state' =>  0
            ];
        }else
            throw new NotFoundHttpException;
    }

    public function actionUploadnewphoto(){
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = 'json';

            $f = UploadHelper::__upload($_FILES['ArticlesPhoto'], [
                'filename'  =>  TranslitHelper::to(Yii::$app->request->post('title')).'-'.rand(0, 1000000),
                'directory' => 'img/blog/articles'
            ]);

            if($f){
                return [
                    'filelink'  =>  $f
                ];
            }

            return [
                'state' =>  0
            ];
        }else
            throw new NotFoundHttpException;
    }

    public function actionUploadbodyphoto(){
            Yii::$app->response->format = 'json';

            $f = UploadHelper::__upload($_FILES['file'], [
                'filename'  =>  TranslitHelper::to(Yii::$app->request->post('title')).'-'.rand(0, 1000000),
                'directory' => 'img/blog/body'
            ]);

            if($f){
                return [
                    'filelink'  =>  $f
                ];
            }

            return [
                'state' =>  0
            ];
    }

    /**
     * Updates an existing Articles model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDisplay($id)
    {
        Articles::changeStateDisplay($id);

        return $this->redirect(LinkController::getForAdmin('view').$id);
    }

    /**
     * Finds the Articles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Articles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Articles::findOne($id)) !== null) {
            return $model;
        }else
            throw new NotFoundHttpException;
    }
}
