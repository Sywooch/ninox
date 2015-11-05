<?php
namespace backend\controllers;

use common\models\Service;
use frontend\models\Customer;
use frontend\models\Good;
use sammaye\audittrail\AuditTrail;
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

    public function actionUpdatecurrency(){
        if(!\Yii::$app->request->isAjax){
            return;
        }

        \Yii::$app->response->format = 'json';

        $post = \Yii::$app->request->post("Service");
        $m = Service::findOne(['key' => $post['key']]);
        $m->load(\Yii::$app->request->post("Service"));
        $m->value = \Yii::$app->request->post("Service[value]");
        $m->save();

        return \Yii::$app->request->post();
    }

    public function actionRevertchanges(){
        if(\Yii::$app->request->isAjax){
            $m = AuditTrail::findOne(['id' => \Yii::$app->request->post("itemid")]);

            if(!$m){
                return $this->runAction('error');
            }

            switch($m->model){
                case 'common\models\Good':
                    $model = Good::findOne(['id' => $m->model_id]);
                    break;
                case 'common\models\Customer':
                    $model = Customer::findOne(['id' => $m->model_id]);
                    break;
            }

            $field = $m->field;

            $model->$field = $m->old_value;
            $model->save(false);
        }else{
            return $this->runAction('error');
        }
    }


    public function actionLogin()
    {
        $this->layout = 'login';

        if(\Yii::$app->request->isAjax){
            return \Yii::$app->user->isGuest ? '1' : '0';
        }

        if(!\Yii::$app->user->isGuest){
            if(!empty(\Yii::$app->user->identity->default_route)){
                return $this->redirect(\Yii::$app->user->identity->default_route);
            }

            return $this->redirect(Url::home());
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
