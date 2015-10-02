<?php

namespace app\modules\feedback\controllers;

use app\models\Review;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;

class DefaultController extends Controller
{
    public function actionIndex($p1 = '', $p2 = '')
    {
        if($p1 == "" && $p2 == ""){
            return $this->runAction('actionindex');
        }else{
            if($p2 != ""){
                return $this->runAction($p1, [
                    'param' =>  $p2
                ]);
            }else{
                return $this->runAction($p1);
            }
        }
    }


    public function actionReviews(){
        return $this->render('reviews', [
            'dataProvider'  =>  new ActiveDataProvider([
                'query' =>  Review::find()
                    ->where(['=', 'deleted', 0])
            ])
        ]);
    }

    public function actionQuestions(){
        return $this->render('questions');
    }

    public function actionRequestcall(){
        return $this->render('requestcall');
    }

    public function actionLament(){
        return $this->render('lament');
    }

    public function actionVote(){
        return $this->render('vote');
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Review models.
     * @return mixed
     */
    public function actionActionindex(){
        $dataProvider = new ActiveDataProvider([
            'query' => Review::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Review model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Review model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Review();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Review model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Review model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Review model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Review the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id){
        if (($model = Review::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPubordel(){
        if(\Yii::$app->request->isAjax){
            return Review::changeState(\Yii::$app->request->post( "colID", "PubOrDel" ));
        }else{
            return $this->render('/../../admin/views/default/error.php', [
                'name'  =>  '404',
                'message'   => 'Такой страницы не существует'
            ]);
        }
    }
}
