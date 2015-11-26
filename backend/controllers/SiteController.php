<?php
namespace backend\controllers;

use common\models\Service;
use frontend\models\Customer;
use frontend\models\Good;
use sammaye\audittrail\AuditTrail;
use Yii;
use yii\base\ErrorException;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use backend\models\LoginForm;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

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


    public function beforeAction($action){

        if(isset(\Yii::$app->user->identity) && !\Yii::$app->user->isGuest){
            if(\Yii::$app->user->identity->superAdmin == 1){
                //\Yii::$app->params['moduleConfiguration'] = $this->renderPartial('_moduleConfiguration');
            }

            \Yii::$app->user->identity->lastActivity = date('Y-m-d H:i:s');
            \Yii::$app->user->identity->save();
            //echo \Yii::$app->user->identity->can('1') ? 'true' : 'false'; //если false - значит чувака нельзя пускать
        }

        return parent::beforeAction($action);
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

    public function actionAddcontroller(){
        if(\Yii::$app->user->identity->superAdmin != 1){
            throw new NotFoundHttpException();
        }

        if(!\Yii::$app->request->isAjax){
            throw new BadRequestHttpException("Этот метод работает только через ajax!");
        }

        $controller = \common\models\Controller::findOne(['controller'  =>  \Yii::$app->request->post("controller")]);

        if(!$controller){
            $controller = new \common\models\Controller();
            $controller->controller = \Yii::$app->request->post("controller");
            return $controller->save() ? 1 : 0;
        }

        return 1;
    }

    public function actionAddaction(){
        if(\Yii::$app->user->identity->superAdmin != 1){
            throw new NotFoundHttpException();
        }

        if(!\Yii::$app->request->isAjax){
            throw new BadRequestHttpException("Этот метод работает только через ajax!");
        }

        $controller = \common\models\Controller::findOne(['controller'  =>  \Yii::$app->request->post("controller")]);

        if(!$controller){
            throw new NotFoundHttpException("Такой контроллер не найден!");
        }

        $action = \common\models\ControllerAction::findOne(['controllerID'  =>  $controller->id, 'action'   =>  \Yii::$app->request->post("action")]);

        if(!$action){
            $action = new \common\models\ControllerAction();
            $action->attributes = \Yii::$app->request->post("ControllerAction");
            $action->controllerID = $controller->id;
            return $action->save() ? 1 : 0;
        }

        return 1;
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
