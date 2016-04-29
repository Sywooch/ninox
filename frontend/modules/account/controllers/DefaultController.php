<?php

namespace frontend\modules\account\controllers;

use common\models\GoodsComment;
use frontend\models\CustomerWishlist;
use frontend\models\Good;
use frontend\models\SborkaItem;
use frontend\models\History;
use frontend\modules\account\models\ChangePasswordForm;
use kartik\form\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'roles' =>  ['?'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $customerOrders = ArrayHelper::getColumn(History::find()
            ->select('id')
            ->where(['customerID' => \Yii::$app->user->identity->ID])
            ->orderBy('added DESC')
            ->asArray()
            ->all(), 'id');

        return $this->render('index',
            [
                'buyedItems'    =>  new ActiveDataProvider([
                    'query'         =>  SborkaItem::find()->where(['in', 'orderID', $customerOrders])->limit(6),
                    'pagination'    =>  [
                        'pageSize'  =>  6
                    ]
                ])
            ]);
    }

    public function actionDiscount(){
        return $this->render('discount');
    }

    public function actionWishList(){
        $wishlistItems = ArrayHelper::getColumn(CustomerWishlist::find()
        ->select('itemID')
        ->where(['customerID' => \Yii::$app->user->identity->ID])
        ->asArray()
        ->all(), 'itemID');

        return $this->render('wishList', [
            'items' =>  new ActiveDataProvider([
                'query' =>  Good::find()->where(['in', 'ID', $wishlistItems])
            ])
        ]);
    }

    public function actionReviews(){
        return $this->render('reviews', [
            'reviews' =>  new ActiveDataProvider([
                'query' =>  GoodsComment::find()->where(['customerID' => \Yii::$app->user->identity->ID])
            ])
        ]);
    }

    public function actionReturns(){
        return $this->render('returns');
    }
    
    public function actionYarmarkaMasterov(){
        throw new NotFoundHttpException("Ярмарка мастеров не работает");
        return $this->render('yarmarkaMasterov');
    }

    public function actionPasswordChange(){
        if(!\Yii::$app->request->isAjax){
            throw new BadRequestHttpException("Данный метод доступен только через ajax!");
        }

        $model = new ChangePasswordForm();
        $model->load(\Yii::$app->request->post());

        \Yii::$app->response->format = 'json';

        if(\Yii::$app->request->post("ajax") == 'changePasswordForm'){
            return ActiveForm::validate($model);
        }

        if($model->validate()){
            \Yii::$app->user->identity->setPassword($model->newPassword);
            return \Yii::$app->user->identity->save(false);
        }

        return false;
    }

    public function actionBetterlistclosed(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Этот запрос возможен только через Ajax!");
        }

        \Yii::$app->user->identity->giveFeedbackClosed = date('Y-m-d H:i:s');
        \Yii::$app->user->identity->save(false);
    }

    public function actionOrders()
    {
        $ordersDataProvider = new ActiveDataProvider([
            'query' => History::find()->where(['customerID' => \Yii::$app->user->identity->ID]),
            'sort'  =>  [
                'defaultOrder'  =>  [
                    'id'    =>  SORT_DESC
                ]
            ]
        ]);

        return $this->render('orders', [
            'ordersDataProvider'    =>  $ordersDataProvider
        ]);
    }
}
