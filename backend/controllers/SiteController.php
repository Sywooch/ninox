<?php
namespace backend\controllers;

use common\models\Chat;
use common\models\ChatMessage;
use common\models\ControllerAction;
use common\models\Service;
use common\models\Shop;
use common\models\Siteuser;
use common\models\SubDomain;
use frontend\models\Customer;
use frontend\models\Good;
use sammaye\audittrail\AuditTrail;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use backend\models\LoginForm;
use yii\filters\VerbFilter;
use yii\web\MethodNotAllowedHttpException;
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

    public function actionLoadchat(){
        if(!\Yii::$app->request->isAjax){
            throw new BadRequestHttpException("Этот запрос возможен только через AJAX!");
        }

        $chat = Chat::findOne(['id' =>  \Yii::$app->request->post("chatID")]);

        if(!$chat){
            return false;
        }

        //Сделать проверку на то, что пользователь есть в этом чате

        return $this->renderAjax('../../widgets/views/_chat_window', [
            'chatData'              =>  $chat,
            'messagesDataProvider'  =>  new ActiveDataProvider([
                'query' =>  ChatMessage::find()->where(['chat'  =>  $chat->id])->orderBy('timestamp ASC')
            ])
        ]);
    }

    public function beforeAction($action){

        if(!\Yii::$app->user->isGuest){
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

    public function init(){
        $configuration = false;

        $domain = preg_replace('/\.'.$_SERVER['SERVER_NAME'].'/', '', $_SERVER['HTTP_HOST']);
        $domain = SubDomain::find()->where(['name' => $domain])->andWhere('storeId != 0')->one();


        if($domain){
            if($domain->autologin){
                foreach($domain->autologinParams as $autologinParam){
                    if($autologinParam['ip'] == \Yii::$app->request->getUserIP()){
                        \Yii::$app->params['autologin'] = is_array($autologinParam['user']) ? $autologinParam['user'] : [$autologinParam['user']];
                    }
                }
            }

            $configuration = Shop::findOne($domain->storeId);
        }

        if(!$configuration){
            $configuration = Shop::findOne(['default' => 1]);
        }

        \Yii::$app->params['configuration'] = $configuration;

        return parent::init();
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

        $action = ControllerAction::findOne(['controllerID'  =>  $controller->id, 'action'   =>  \Yii::$app->request->post("action")]);

        if(!$action){
            $action = new ControllerAction();
            $action->attributes = \Yii::$app->request->post("ControllerAction");
            $action->controllerID = $controller->id;
            return $action->save() ? 1 : 0;
        }

        return 1;
    }

    public function actionUpdatecurrency(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод доступен только через ajax!");
        }

        \Yii::$app->response->format = 'json';

        $m = Service::findOne(['key' => \Yii::$app->request->post("Service")['key']]);
        $m->load(\Yii::$app->request->post());
        if(!$m->save(false)){
            throw new \ErrorException("Возникла ошибка при сохранении параметра ".$m->key);
        }

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

    public function actionLogin(){
        $this->layout = 'login';

        if(\Yii::$app->request->isAjax && empty(\Yii::$app->request->post('LoginForm'))){
            foreach(\Yii::$app->log->targets as $target){
                $target->enabled = false;
            }

            return \Yii::$app->user->isGuest ? '1' : '0';
        }

        if (!\Yii::$app->user->isGuest) {
            return $this->goBack();
        }

        $model = new LoginForm();

        $hasAutoLogin = !empty(\Yii::$app->params['autologin']);

        if(!empty(\Yii::$app->params['autologin'])){
            $model->autoLoginUsers = \Yii::$app->params['autologin'];

            if(isset(\Yii::$app->request->post("LoginForm")['userID']) && in_array(\Yii::$app->request->post("LoginForm")['userID'], $model->autoLoginUsers)){
                $model->autoLoginUsers = [\Yii::$app->request->post("LoginForm")['userID']];
            }

            if(sizeof($model->autoLoginUsers) == 1){
                $user = Siteuser::findOne($model->autoLoginUsers['0']);

                if($user){
                    $model->username = $user->username;

                    if(\Yii::$app->user->login($model->getUser(), 3600*24)){
                        return Yii::$app->getUser()->getReturnUrl() == '/logout' ? $this->redirect(!empty(\Yii::$app->user->identity->default_route) ? \Yii::$app->user->identity->default_route : Url::home()) : $this->goBack();
                    }
                }
            }
        }

        if ($model->load(\Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(!empty(\Yii::$app->user->identity->default_route) ? \Yii::$app->user->identity->default_route : Url::home());
        }else{
            if($hasAutoLogin){
                return $this->render('login', [
                    'model' => $model,
                    'users' => Siteuser::find()->where(['in', 'id', $model->autoLoginUsers])->all()
                ]);
            }

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
