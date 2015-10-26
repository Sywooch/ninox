<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
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

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->run('orders/default/index');
    }

    public function actionLogin()
    {
        $this->layout = 'login';

        if(\Yii::$app->request->isAjax){
            return \Yii::$app->user->isGuest ? '1' : '0';
        }

        if(!\Yii::$app->user->isGuest){
            return $this->redirect(\Yii::$app->user->identity->default_route);
        }

        $model = new LoginForm();

        if ($model->load(\Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(\Yii::$app->user->identity->default_route);
            //return !$this->redirect($this->goBack() = '/login' ? \Yii::$app->user->identity->default_route : $this->goBack());
        }else{
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Url::remember(\Yii::$app->request->referrer, 'previous');

        Yii::$app->user->logout();

        return $this->refresh();
    }
}
