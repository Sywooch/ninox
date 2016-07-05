<?php
namespace cashbox\controllers;

use cashbox\models\CustomerForm;
use cashbox\models\CashboxOrder;
use backend\models\Customer;
use backend\models\Good;
use backend\models\SborkaItem;
use cashbox\models\CashboxItem;
use common\models\Cashbox;
use cashbox\models\Siteuser;
use common\models\Pricerule;
use common\models\Promocode;
use common\models\SubDomain;
use common\models\SubDomainAccess;
use ErrorException;
use Yii;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use backend\models\LoginForm;
use yii\filters\VerbFilter;
use yii\web\Cookie;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @type \cashbox\components\CashboxNoCache
     */
    protected $cashbox;

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

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


    public function actionPrintinvoice($param){
        return $this->redirect(\Yii::$app->params['backend'].'/printer/invoice/'.$param.'?secret=secretKeyForPrinter');
    }

    public function init(){
        $this->cashbox = \Yii::$app->cashbox;

        $configuration = false;

        $domain = preg_replace('/\.'.$_SERVER['SERVER_NAME'].'/', '', $_SERVER['HTTP_HOST']);
        $domain = SubDomain::find()->where(['name' => $domain])->andWhere('cashboxId != 0')->one();

        if($domain){
            if($domain->autologin){
                foreach($domain->autologinParams as $autologinParam){
                    if($autologinParam['ip'] == \Yii::$app->request->getUserIP()){
                        \Yii::$app->params['autologin'] = is_array($autologinParam['user']) ? $autologinParam['user'] : [$autologinParam['user']];
                    }
                }
            }

            $allowedUsers = SubDomainAccess::findAll(['subDomainId' => $domain->id]);

            if($allowedUsers){
                foreach($allowedUsers as $user){
                    \Yii::$app->params['allowedUsers'][] = $user->userId;
                }
            }

            $configuration = Cashbox::findOne($domain->cashboxId);
        }

        if(!$configuration){
            $configuration = Cashbox::findOne(['default' => 1]);
        }

        \Yii::$app->params['configuration'] = $configuration;

        return parent::init();
    }

    public function beforeAction($action){
        if(!\Yii::$app->user->isGuest){
            if(\Yii::$app->user->identity->superAdmin == 1){
                //\Yii::$app->params['moduleConfiguration'] = $this->renderPartial('_moduleConfiguration');
            }

            \Yii::$app->user->identity->lastActivity = date('Y-m-d H:i:s');
            \Yii::$app->user->identity->save(false);
            //echo \Yii::$app->user->identity->can('1') ? 'true' : 'false'; //если false - значит чувака нельзя пускать
        }

        return parent::beforeAction($action);
    }

    /**
     * @return string
     * @throws \yii\base\InvalidParamException
     * @throws \yii\base\InvalidCallException
     */
    public function actionIndex(){
        $order = $this->cashbox->order;

        if(\Yii::$app->request->post('CustomerForm')){
            $customerForm = new CustomerForm();
            $customerForm->load(\Yii::$app->request->post());

            if($customerForm->save()){
                \Yii::$app->response->cookies->add(new Cookie([
                    'name'  =>  'cashboxCurrentCustomer',
                    'value' =>  $customerForm->id
                ]));

                if($order){
                    $order->customerID = $customerForm->id;
                    $order->save(false);
                }
            }
        }

        $orderItems = new ActiveDataProvider([
            'query'     =>  $this->cashbox->cashboxItemsQuery(),
            'pagination'    =>  [
                'pageSize'  =>  0
            ],
            'sort'      =>  [
                'defaultOrder'  =>  [
                    'added'         =>  SORT_ASC
                ]
            ]
        ]);

        return $this->render('index', [
            'orderItems'        =>  $orderItems,
            'order'             =>  $order,
            'customer'          =>  $order->customer,
            'sum'               =>  $this->cashbox->sum,
            'discountSize'      =>  $this->cashbox->discountSize,
            'itemsCount'        =>  $order->itemsCount,
            'wholesaleSum'      =>  $this->cashbox->wholesaleSum,
            'retailSum'         =>  $this->cashbox->retailSum,
            'toPay'             =>  $this->cashbox->toPay,
            'priceType'         =>  $this->cashbox->priceType,
            'manager'           =>  $this->cashbox->getManager()
        ]);
    }

    /**
     * @return bool|string
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\db\StaleObjectException
     * @throws \yii\base\InvalidCallException
     * @throws \yii\base\InvalidParamException
     * @throws \Exception
     * @throws MethodNotAllowedHttpException
     * @throws NotFoundHttpException
     */
    public function actionCompletesell(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException('Этот метод доступен только через ajax!');
        }

        $amount = \Yii::$app->request->post('actualAmount');

        if(empty($amount)){
            throw new InvalidParamException("Сумма сделки равна {$amount}. По-моему, это не похоже на число");
        }

        return $this->cashbox->sell($amount);
    }

    /**
     *
     * @throws \Exception
     */
    public function actionClearOrder(){
        return $this->cashbox->clear();
    }

    /**
     * @return array
     * @throws \yii\base\InvalidCallException
     * @throws MethodNotAllowedHttpException
     */
    public function actionChangecashboxtype(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException('Этот метод доступен только через ajax!');
        }

        \Yii::$app->response->format = 'json';

        $this->cashbox->changePriceType();


        return $this->cashbox->getSummary();
    }

    /**
     * @return int|string
     * @throws \yii\base\InvalidCallException
     * @throws MethodNotAllowedHttpException
     * @throws NotFoundHttpException
     */
    public function actionChangemanager(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException('Данный метод возможен только через ajax!');
        }

        if(\Yii::$app->request->post('action') == 'showList'){
            return $this->renderAjax('_changeManager', [
                'managers'  =>  Siteuser::getActiveUsers(),
                'cashbox'   =>  \Yii::$app->cashbox
            ]);
        }

        $managerID = \Yii::$app->request->post('manager');

        $siteuser = Siteuser::findOne($managerID);

        if(!$siteuser && $managerID != 0){
            throw new NotFoundHttpException("Менеджер с идентификатором {$managerID} не найден!");
        }

        $this->cashbox->setManager($managerID);

        return $this->cashbox->responsibleUser;
    }

    /**
     * @return array|bool
     * @throws MethodNotAllowedHttpException
     */
    public function actionChangeitemcount(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException('Данный метод возможен только через ajax!');
        }

        \Yii::$app->response->format = 'json';

        if($this->cashbox->changeCount(\Yii::$app->request->post('itemID'), \Yii::$app->request->post('count'))){
            return $this->cashbox->getSummary();
        }

        return false;
    }

    /**
     * @return bool
     * @throws MethodNotAllowedHttpException
     * @throws NotFoundHttpException
     */
    public function actionChangecustomer(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException('Данный метод возможен только через ajax!');
        }

        \Yii::$app->response->format = 'json';

        $customerID = \Yii::$app->request->post('customerID');

        $customer = Customer::findOne($customerID);

        if(!$customer){
            throw new NotFoundHttpException("Клиент с идентификатором {$customerID} не найден!");
        }

        $this->cashbox->setCustomer($customerID);

        return true;
    }

    public function actionFindcustomer(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException('Данный метод возможен только через ajax!');
        }

        \Yii::$app->response->format = 'json';

        return Customer::find()
            ->select(['ID', 'Company', 'phone', 'cardNumber'])
            ->where(['like', \Yii::$app->request->get('attribute'), \Yii::$app->request->get('query')])
            ->limit(10)
            ->all();
    }

    public function actionChecks(){
        return $this->render('checks', [
            'checksItems'   =>  new ActiveDataProvider([
                'query'     =>  CashboxOrder::find()
                    ->andWhere(['postpone' => 1, 'source' => \Yii::$app->params['configuration']->ID])
                    ->with('customer')
                    ->with('manager'),
                'sort'      =>  [
                    'defaultOrder'  =>  [
                        'createdTime' =>  SORT_DESC]
                    
                ]
            ])
        ]);
    }

    public function actionReturns(){
        $orders = CashboxOrder::find()
            ->andWhere(['return' => 1, 'source' => \Yii::$app->params['configuration']->ID])
            ->with('customer')
            ->with('manager');

        $date = time() - (date('H') * 3600 + date('i') * 60 + date('s'));

        switch(\Yii::$app->request->get('smartfilter')){
            case 'yesterday':
                $orders->andWhere('doneTime >= \''.\Yii::$app->formatter->asDatetime($date - 86400, 'php:Y-m-d H:i:s')."'");
                $orders->andWhere('doneTime < \''.\Yii::$app->formatter->asDatetime($date, 'php:Y-m-d H:i:s')."'");
                break;
            case 'week':
                $orders->andWhere('doneTime >= \''.\Yii::$app->formatter->asDatetime(($date - (date("N") - 1) * 86400), 'php:Y-m-d H:i:s')."'");
                break;
            case 'month':
                $orders->andWhere('doneTime >= \''.\Yii::$app->formatter->asDatetime(($date - (date("j") - 1) * 86400), 'php:Y-m-d H:i:s')."'");
                break;
            case 'today':
            default:
                $orders->andWhere('doneTime >= \''.\Yii::$app->formatter->asDatetime($date, 'php:Y-m-d H:i:s')."'");
                break;
        }

        return $this->render('returns', [
            'returns'   =>  new ActiveDataProvider([
                'query'     =>  $orders
            ])
        ]);
    }

    public function actionGetsaledetails(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        if(empty(\Yii::$app->request->post("orderID"))){
            throw new BadRequestHttpException("пустой orderID!");
        }

        $cashboxOrder = CashboxOrder::findOne(['ID'    =>  \Yii::$app->request->post("orderID")]);

        if(!$cashboxOrder){
            throw new NotFoundHttpException("Такого заказа не существует!");
        }

        return $this->renderAjax('_orderPreview', [
            'goods' =>  new ActiveDataProvider([
                'query' =>  SborkaItem::find()->where(['orderID'   =>  $cashboxOrder->createdOrderID]),
            ])
        ]);
    }

    /**
     * @return bool
     * @throws \yii\db\StaleObjectException
     * @throws \yii\base\InvalidCallException
     * @throws \Exception
     * @throws BadRequestHttpException
     * @throws MethodNotAllowedHttpException
     * @throws NotFoundHttpException
     */
    public function actionLoadorder(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException('Данный метод возможен только через ajax!');
        }

        $orderID = \Yii::$app->request->post('orderID');

        $order = CashboxOrder::findOne($orderID);

        if(!$order){
            throw new NotFoundHttpException("Заказ с идентификатором {$orderID} не найден!");
        }

        if(!empty($order->createdOrder)){
            foreach($order->createdOrder->items as $assemblyItem){
                $cashboxItem = CashboxItem::findOne(['itemID' => $assemblyItem->itemID, 'orderID' => $order->id]);

                if(!$cashboxItem){
                    $cashboxItem = new CashboxItem();
                }

                $cashboxItem->loadAssemblyItem($assemblyItem, $order->id);

                $cashboxItem->save(false);
            }
        }

        $this->cashbox->loadOrder($order, \Yii::$app->request->post('dropOrder', false));

        return true;
    }

    public function actionSales(){
        $orders = CashboxOrder::find()->with('manager')->with('customer')->with('createdOrder');

        $date = time() - (date('H') * 3600 + date('i') * 60 + date('s'));

        switch(\Yii::$app->request->get('smartfilter')){
            case 'yesterday':
                $orders->andWhere('doneTime >= \''.\Yii::$app->formatter->asDatetime($date - 86400, 'php:Y-m-d H:i:s')."'");
                $orders->andWhere('doneTime < \''.\Yii::$app->formatter->asDatetime($date, 'php:Y-m-d H:i:s')."'");
                break;
            case 'week':
                $orders->andWhere('doneTime >= \''.\Yii::$app->formatter->asDatetime(($date - (date("N") - 1) * 86400), 'php:Y-m-d H:i:s')."'");
                break;
            case 'month':
                $orders->andWhere('doneTime >= \''.\Yii::$app->formatter->asDatetime(($date - (date("j") - 1) * 86400), 'php:Y-m-d H:i:s')."'");
                break;
            case 'range':
                $dateFrom = \Yii::$app->request->get('dateFrom');
                $dateTo = \Yii::$app->request->get('dateTo');
                $orders
                    ->andWhere('doneTime <= \''.\Yii::$app->formatter->asDatetime($dateTo, 'php:Y-m-d H:i:s').'\'')
                    ->andWhere('doneTime >= \''.\Yii::$app->formatter->asDatetime($dateFrom, 'php:Y-m-d H:i:s').'\'');
                break;
            case 'today':
            default:
                $orders->andWhere('doneTime >= \''.\Yii::$app->formatter->asDatetime($date, 'php:Y-m-d H:i:s')."'");
                break;
        }


        return $this->render('sales', [
            'salesProvider' =>  new ActiveDataProvider([
                'query'     =>  $orders->andWhere(['return' => 0])->andWhere(['source' => \Yii::$app->params['configuration']->ID]),
                'sort'      =>  [
                    'defaultOrder'  =>  [
                        'doneTime' =>  SORT_DESC
                    ]
                ]
            ])
        ]);
    }

    /**
     * @return string
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\db\StaleObjectException
     * @throws \yii\base\InvalidCallException
     * @throws \Exception
     * @throws MethodNotAllowedHttpException
     */
    public function actionReturnorder(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException('Данный метод возможен только через ajax!');
        }

        if(!empty(\Yii::$app->request->post('orderID'))){
            \Yii::$app->cashbox->loadOrder(\Yii::$app->request->post('orderID'));
        }

        if(empty($this->cashbox->order->items)){
           throw new MethodNotAllowedHttpException('Нельзя оформить возврат, когда товаров нет!');
        }

        \Yii::$app->response->format = 'json';

        return $this->cashbox->refund()->id;
    }

    /**
     * @return mixed
     * @throws \yii\db\StaleObjectException
     * @throws \yii\base\InvalidCallException
     * @throws \Exception
     * @throws \yii\web\NotFoundHttpException
     * @throws ErrorException
     * @throws MethodNotAllowedHttpException
     */
    public function actionPostponecheck(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException('Данный метод возможен только через ajax!');
        }

        $order = $this->cashbox->order;

        if(!$order){
            throw new NotFoundHttpException('Нечего откладывать');
        }

        if(!$this->cashbox->postpone()){
            throw new ErrorException('Произошла ошибка при выполнении метода actionPostponeCheck');
        }

        return $order->id;
    }

    /**
     * @return bool
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\db\StaleObjectException
     * @throws \yii\base\InvalidCallException
     * @throws \Exception
     * @throws MethodNotAllowedHttpException
     */
    public function actionLoadpostpone(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException('Данный метод возможен только через ajax!');
        }

        $this->cashbox->loadPostpone(\Yii::$app->request->post('postponeOrderID'));

        return true;
    }

    /**
     * @return array
     * @throws \yii\base\InvalidCallException
     * @throws ErrorException
     * @throws MethodNotAllowedHttpException
     * @throws NotFoundHttpException
     */
    public function actionAdditem(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException('Данный метод возможен только через ajax!');
        }

        $itemID = \Yii::$app->request->post('itemID');

        $promoCode = Promocode::findOne(['code' => $itemID]);

        if($promoCode && $this->cashbox->order){
            $this->cashbox->promoCode = $this->cashbox->order->promoCode = $promoCode->code;
            $this->cashbox->order->save(false);

            $this->cashbox->addDiscount(Pricerule::findOne($promoCode->rule));

            \Yii::$app->response->format = 'json';

            return $this->cashbox->getSummary();
        }

        $good = Good::find()
            ->where(['or', "`BarCode2` = '{$itemID}'", "`BarCode1` = '{$itemID}'", "`Code` = '{$itemID}'", "`ID` = '{$itemID}'"])
            ->orderBy('`Barcode2` DESC')
            ->one();

        if(!$good){
            throw new NotFoundHttpException("Товар с идентификатором '{$itemID}' не найден!");
        }

        \Yii::$app->response->format = 'json';

        $this->cashbox->put($good->ID);

        return $this->cashbox->getSummary();
    }

    /**
     * @return array|bool
     * @throws \yii\db\StaleObjectException
     * @throws MethodNotAllowedHttpException
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionRemoveitem()
    {
        if (!\Yii::$app->request->isAjax) {
            throw new MethodNotAllowedHttpException('Данный метод возможен только через ajax!');
        }

        \Yii::$app->response->format = 'json';

        $itemID = \Yii::$app->request->post('itemID');

        if($itemID != 'all'){
            $good = Good::findOne($itemID);

            if(!$good ){
                throw new NotFoundHttpException("Товар с идентификатором {$itemID} не найден в магазине!");
            }else if(empty($this->cashbox->getItem($itemID))) {
                throw new NotFoundHttpException("Товар с идентификатором {$itemID} не найден в заказе с идентификатором {$this->cashbox->order->id}!");
            }
        }

        if ($this->cashbox->order->itemsCount > 0) {
            if ($itemID == 'all' && !empty($this->cashbox->order)) {
                foreach($this->cashbox->order->items as $item){
                    $item->delete();
                }
            } else {
                $this->cashbox->getItem($itemID)->delete();

                return $this->cashbox->getSummary();
            }
        }

        return true;
    }

    /**
     * @return string|\yii\web\Response
     * @throws \yii\base\InvalidParamException
     */
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

            if(array_key_exists('userID', \Yii::$app->request->post('LoginForm')) && in_array(\Yii::$app->request->post('LoginForm')['userID'], $model->autoLoginUsers)){
                $model->autoLoginUsers = [\Yii::$app->request->post('LoginForm')['userID']];
            }

            if(count($model->autoLoginUsers) == 1){
                $user = Siteuser::findOne($model->autoLoginUsers['0']);

                if($user){
                    $model->username = $user->username;

                    if(\Yii::$app->user->login($model->getUser(), 3600*24)){
                        return $this->goBack();
                    }
                }
            }
        }

        if ($model->load(\Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(!empty(\Yii::$app->user->identity->default_route) ? \Yii::$app->user->identity->default_route : Url::home());
        }else{
            $users = [];

            if($hasAutoLogin){
                $users = Siteuser::find()->andWhere(['in', 'id', $model->autoLoginUsers])->all();

                if(!$users){
                    $users = [];
                }

                return $this->render('login', [
                    'model' =>  $model,
                    'users' =>  $users
                ]);
            }

            return $this->render('login', [
                'model' => $model,
                'users' =>  $users
            ]);
        }
    }

    /**
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Url::remember(\Yii::$app->request->referrer, 'previous');

        Yii::$app->user->logout();

        return $this->refresh();
    }
}
