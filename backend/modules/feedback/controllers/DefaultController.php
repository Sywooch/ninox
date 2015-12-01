<?php

namespace backend\modules\feedback\controllers;
use backend\models\Review;
use backend\models\Question;
use backend\models\Problem;
use backend\controllers\SiteController as Controller;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii;
use common\models\Articles;
use common\models\ArticlesSearch;
use yii\web\NotFoundHttpException;
use yii\web\UnsupportedMediaTypeHttpException;
use yii\web\UploadedFile;
use common\helpers\UploadHelper;
use common\helpers\TranslitHelper;

class DefaultController extends Controller
{
    public function actionIndex(){
       //return $this->render('index', [
          //  'reviews' =>  Review::find()->orderBy('id ASC')->all()
          //   ]);
    }

    public function actionChangequestionstate(){
        if(!\Yii::$app->request->isAjax){
            throw new UnsupportedMediaTypeHttpException("Этот запрос возможен только через ajax!");
        }
        $question = Question::findOne(['id' => \Yii::$app->request->post("id")]) ;

        if(!$question){
            return $this->run('site/error');
        }
        $question->published = $question->published == 1 ? 0 : 1;

        $question->save();

        return $question->published;

    }
    public function actionChangereviewstate(){
        if(!\Yii::$app->request->isAjax){
            throw new UnsupportedMediaTypeHttpException("Этот запрос возможен только через ajax!");
        }
        $review = Review::findOne(['id' => \Yii::$app->request->post("id")]) ;

        if(!$review){
            return $this->run('site/error');
        }
        $review->published = $review->published == 1 ? 0 : 1;

        $review->save();

        return $review->published;

    }
    public function actionChangeproblemstate(){
        if(!\Yii::$app->request->isAjax){
            throw new UnsupportedMediaTypeHttpException("Этот запрос возможен только через ajax!");
        }
        $problem = Problem::findOne(['id' => \Yii::$app->request->post("id")]) ;

        if(!$problem){
            return $this->run('site/error');
        }
        $problem->read = $problem->read == 1 ? 0 : 1;

        $problem->save();

        return $problem->read;

    }

    public function actionReviews()
    {
        if(\Yii::$app->request->isAjax){
            try{
                return $this->run(\Yii::$app->request->post("action"));
            }catch (\ReflectionException $ex){
                return \Yii::$app->request->post();
            }
        }
        if(\Yii::$app->request->post()){
            $post = \Yii::$app->request->post();
            if(isset($post['Review'])){
                if(!empty($post['Review']['id'])){
                    $b = Review::findOne(['id' => $post['Review']['id']]);
                }else{
                    $b = new Review();
                }
                $b->load($post);
                $b->save();
            }
        }

        return $this->render('reviews', [
            'reviews'   =>  new ActiveDataProvider([
                'query' =>  Review::find(),
                'pagination' => [
                    'pageSize' => 50,
                ],
            ])
        ]);
    }
   /* public function actionUpload()
    {
        $model = new UploadForm();
        if (yii::$app->request->isPost) {
            $model->customerPhoto = UploadedFile::getInstance($model, 'customerPhoto');
            if ($model->upload()){
                return;
            }
        }
        return $this->render('upload', ['model' => $model]);
    }*/
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
                    $model->customerPhoto = $f;
                    if($model->save(false)){
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

    public function upload()
    {
        if ($this->validate()) {
            $this->customerPhoto->saveAs('img/' . $this->customerPhoto->shop . '.' . $this->customerPhoto->extension);
            return true;
        } else {
            return false;
        }
    }

    public function actionQuestions()
    {
        if (\Yii::$app->request->isAjax) {
            try {
                return $this->run(\Yii::$app->request->post("action"));
            } catch (\ReflectionException $ex) {
                return \Yii::$app->request->post();
            }
        }
        if(\Yii::$app->request->post()) {
            $post = \Yii::$app->request->post();
            if (isset($post['Question'])) {
                if (!empty($post['Question']['id'])) {
                    $b = Question::findOne(['id' => $post['Question']['id']]);
                } else {
                    $b = new Question();
                }
                $b->load($post);
                $b->save();
            }
        }

        return $this->render('questions', [
            'questions' => new ActiveDataProvider([
                'query' => Question::find(),
                'pagination' => [
                    'pageSize' => 50,
                ],
            ])
        ]);
    }

    public function actionProblems()
    {
        {
            if (\Yii::$app->request->isAjax) {
                try {
                    return $this->run(\Yii::$app->request->post("action"));
                } catch (\ReflectionException $ex) {
                    return \Yii::$app->request->post();
                }
            }
            if(\Yii::$app->request->post()) {
                $post = \Yii::$app->request->post();
                if (isset($post['Problem'])) {
                    if (!empty($post['Problem']['id'])) {
                        $b = Problem::findOne(['id' => $post['Problem']['id']]);
                    } else {
                        $b = new Problem();
                    }
                    $b->load($post);
                    $b->save();
                }
            }

            return $this->render('problems', [
                'problems' => new ActiveDataProvider([
                    'query' => Problem::find(),
                    'pagination' => [
                        'pageSize' => 50,
                    ],
                ])
            ]);
        }
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
            'reviews' =>  Review::find($id)->all()
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
        $review = $this->findreview($id);

        if ($review->load(Yii::$app->request->post()) && $review->save()) {
            return $this->redirect(['view', 'id' => $rule->id]);
        } else {
            return $this->render('update', [
                'review' => $review,
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
    protected function findreview($id){
        if (($review = Review::findOne($id)) !== null) {
            return $review;
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
