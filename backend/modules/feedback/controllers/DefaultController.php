<?php

namespace backend\modules\feedback\controllers;
use backend\models\Callback;
use backend\models\Review;
use backend\models\Question;
use backend\models\Problem;
use backend\models\Vote;
use backend\controllers\SiteController as Controller;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii;
use yii\web\NotFoundHttpException;
use yii\web\UnsupportedMediaTypeHttpException;
use common\helpers\UploadHelper;
use common\helpers\TranslitHelper;

class DefaultController extends Controller
{
    public function actionIndex(){
       return \Yii::$app->response->redirect('/feedback/reviews');
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

    public function actionChangecallbackstate(){
        if(!\Yii::$app->request->isAjax){
            throw new UnsupportedMediaTypeHttpException();
        }
        $callback = Callback::findOne(['id' => \Yii::$app->request->post("id")]);

        if(!$callback){
            return $this->run('site/error');
        }
        $callback->did_callback = $callback->did_callback == 1 ? 0 : 1;

        $callback->save();

        return $callback->did_callback;
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
                if(isset($_FILES['Review'])){
                    $b->customerPhoto = UploadHelper::__upload($_FILES['Review'], [
                        'filename'  =>  TranslitHelper::to($b->name).'-'.rand(0, 1000000),
                        'directory' => '../../backend/web/img/customers/reviews'
                    ]);

                    $b->customerPhoto = '/img/customers/reviews/'.$b->customerPhoto;
                }
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

    public function actionVote()
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
            if(isset($post['Vote'])){
                if(!empty($post['Vote']['id'])){
                    $b = Vote::findOne(['id' => $post['Vote']['id']]);
                }else{
                    $b = new Vote();
                }
                $b->load($post);
                $b->save();
            }
        }
        return $this->render('votes', [
            'votes'   =>  new ActiveDataProvider([
                'query' =>  Vote::find(),
                'pagination' => [
                    'pageSize' => 50,
                ],
            ])
        ]);
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
                if(isset($_FILES['Question'])){
                    $b->photo = UploadHelper::__upload($_FILES['Question'], [
                        'filename'  =>  TranslitHelper::to($b->name).'-'.rand(0, 1000000),
                        'directory' => '../../backend/web/img/customers/questions'
                    ]);

                    $b->photo = '/img/customers/questions/'.$b->photo;
                }
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

    public function actionCallback()
    {
        if (\Yii::$app->request->isAjax) {
            try {
                return $this->run(\Yii::$app->request->post("action"));
            } catch (\ReflectionException $ex) {
                return \Yii::$app->request->post();
            }
        }
        if (\Yii::$app->request->post()) {
            $post = \Yii::$app->request->post();
            if (isset($post['Callback'])) {
                if (!empty($post['Callback']['id'])) {
                    $b = Callback::findOne(['id' => $post['Callback']['id']]);
                } else {
                    $b = new Callback();
                }
                $b->load($post);
                $b->save();
            }
        }
        return $this->render('callback', [
            'callback' => new ActiveDataProvider([
                'query' => Callback::find(),
                'pagination' => [
                    'pageSize' => 50,
                ],
            ])
        ]);
    }

    public function actionWorkwithquestiontrash(){
        if(\Yii::$app->request->isAjax){
            return Question::changeTrashState(\Yii::$app->request->post("QuestionID"));
        }else{
            return $this->run('site/error');
        }
    }

    public function actionWorkwithreviewtrash(){
        if(\Yii::$app->request->isAjax){
            return Review::changeTrashState(\Yii::$app->request->post("ReviewID"));
        }else{
            return $this->run('site/error');
        }
    }

    public function actionWorkwithcallbacktrash(){
        if(\Yii::$app->request->isAjax){
            return Callback::changeTrashState(\Yii::$app->request->post("CallbackID"));
        }else{
            return $this->run('site/error');
        }
    }
}
