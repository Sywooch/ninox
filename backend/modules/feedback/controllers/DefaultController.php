<?php

namespace backend\modules\feedback\controllers;

use backend\controllers\SiteController as Controller;
use backend\models\Question;
use backend\models\Review;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;

class DefaultController extends Controller
{
    public function actionIndex(){
        return $this->render('index', [
            'reviews' =>  Review::find()->orderBy('id ASC')->all()
             ]);
    }

    public function actionReviews(){
        return $this->render('reviews', [
            'reviews' =>  Review::find()->orderBy('id ASC')->all()
            ]);
    }

    public function actionQuestions(){
        return $this->render('questions', [
            'questions' =>  Question::find()->orderBy('id ASC')->all()
            ]);
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
     * Lists all Review rules.
     * @return mixed
     */


    /**
     * Displays a single Review rule.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'rule' => $this->findrule($id),
        ]);
    }

    /**
     * Creates a new Review rule.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $rule = new Review();

        if ($rule->load(Yii::$app->request->post()) && $rule->save()) {
            return $this->redirect(['view', 'id' => $rule->id]);
        } else {
            return $this->render('create', [
                'rule' => $rule,
            ]);
        }
    }

    /**
     * Updates an existing Review rule.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $rule = $this->findrule($id);

        if ($rule->load(Yii::$app->request->post()) && $rule->save()) {
            return $this->redirect(['view', 'id' => $rule->id]);
        } else {
            return $this->render('update', [
                'rule' => $rule,
            ]);
        }
    }

    /**
     * Deletes an existing Review rule.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findrule($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Review rule based on its primary key value.
     * If the rule is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Review the loaded rule
     * @throws NotFoundHttpException if the rule cannot be found
     */
    protected function findrule($id){
        if (($rule = Review::findOne($id)) !== null) {
            return $rule;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPubordel(){
        if(\Yii::$app->request->isAjax){
            return Review::changeState(\Yii::$app->request->post( "colID", "PubOrDel" ));
        }else{
            return $this->run('site/error');
        }
    }
}
