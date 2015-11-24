<?php

namespace backend\modules\feedback\controllers;

use common\models\Review;
use Yii;
use yii\data\ActiveDataProvider;
use backend\controllers\SiteController as Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{
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

    public function actionPubordel(){
        if(\Yii::$app->request->isAjax){
            return Review::changeState(\Yii::$app->request->post( "colID", "PubOrDel" ));
        }else{
            return $this->run('site/error');
        }
    }

    public function actionIndex(){
        $dataProvider = new ActiveDataProvider([
            'query' => Review::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
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
}
