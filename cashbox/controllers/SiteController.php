<?php
namespace cashbox\controllers;

use backend\models\CashboxCustomerForm;
use backend\models\CashboxOrder;
use backend\models\Customer;
use backend\models\Good;
use backend\models\SborkaItem;
use common\models\Cashbox;
use common\models\Siteuser;
use ErrorException;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Url;
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

        //return $this->redirect(\Yii::$app->params['backend'].'/orders/printinvoice/'.$param);
    }

    public function init(){
        $domain = $_SERVER['SERVER_NAME'];

        $configuration = Cashbox::findOne(['domain' => $domain]);

        if(!$configuration){
            $configuration = new Cashbox([
                'ID'    =>  0
            ]);

            $configuration->autologin = [
                [
                    'username'  =>  'root',
                    'ip'        =>  '127.0.0.1'
                ]
            ];
        }

        if($configuration){
            \Yii::$app->params['configuration'] = $configuration;
        }

        return parent::init();
    }

    public function beforeAction($action){
        /*if(!\Yii::$app->user->isGuest){
            if(\Yii::$app->user->identity->superAdmin == 1){
                //\Yii::$app->params['moduleConfiguration'] = $this->renderPartial('_moduleConfiguration');
            }

            \Yii::$app->user->identity->lastActivity = date('Y-m-d H:i:s');
            \Yii::$app->user->identity->save();
            //echo \Yii::$app->user->identity->can('1') ? 'true' : 'false'; //если false - значит чувака нельзя пускать
        }*/

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        if(!empty(\Yii::$app->cashbox->order)){
            $order = \Yii::$app->cashbox->order;
        }else{
            $order = new CashboxOrder();
        }

        $customer = \Yii::$app->cashbox->customer;

        if(\Yii::$app->request->post("CashboxCustomerForm")){
            $cashboxCustomerForm = new CashboxCustomerForm();
            $cashboxCustomerForm->load(\Yii::$app->request->post());

            if($cashboxCustomerForm->save()){
                if(!$order->isNewRecord){
                    $order->customerID = $cashboxCustomerForm->id;

                    $order->save(false);
                }

                \Yii::$app->response->cookies->add(new Cookie([
                    'name'  =>  'cashboxCurrentCustomer',
                    'value' =>  $cashboxCustomerForm->id
                ]));

                $customer = $cashboxCustomerForm->id;
            }
        }

        if(!empty($order->customerID)){
            $customer = $order->customerID;
        }

        if($customer){
            $customer = Customer::findOne($customer);
        }

        $orderItems = new ActiveDataProvider([
            'query'     =>  \Yii::$app->cashbox->itemsQuery(),
            'pagination'    =>  [
                'pageSize'  =>  0
            ]
        ]);

        $orderToPay = $orderDiscountSize = $orderDiscountPercent = $orderSum = 0;

        $orderItemsIDs = [];

        foreach($orderItems->getModels() as $item){
            $orderItemsIDs[] = $item->itemID;
            $orderToPay += $item->price;
        }

        $goodsModels = [];

        foreach(Good::find()->where(['in', 'ID', $orderItemsIDs])->each() as $item){
            $goodsModels[$item->ID] = $item;
        }

        return $this->render('index', [
            'goodsModels'       =>  $goodsModels,
            'orderItems'        =>  $orderItems,
            'order'             =>  $order,
            'customer'          =>  $customer,
            'manager'           =>  Siteuser::getActiveUsers()[\Yii::$app->cashbox->responsibleUser]
        ]);
    }

    public function actionCompletesell(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Этот метод доступен только через ajax!");
        }

        return \Yii::$app->cashbox->sell(\Yii::$app->request->post("actualAmount"));
    }

    public function actionChangecashboxtype(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Этот метод доступен только через ajax!");
        }

        \Yii::$app->response->format = 'json';

        \Yii::$app->cashbox->changePriceType();

        if(!empty(\Yii::$app->cashbox->order)){
            \Yii::$app->cashbox->recalculate();

            return [
                'priceType' =>  \Yii::$app->cashbox->order->priceType,
                'orderSum'  =>  \Yii::$app->cashbox->sum,
                'orderToPay'=>  \Yii::$app->cashbox->toPay
            ];
        }

        return [
            'priceType' =>  \Yii::$app->cashbox->priceType
        ];
    }

    public function actionChangemanager(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        if(\Yii::$app->request->post("action") == 'showList'){
            return $this->renderAjax('_changeManager', [
                'managers'  =>  Siteuser::getActiveUsers()
            ]);
        }

        \Yii::$app->cashbox->changeManager(\Yii::$app->request->post("manager"));

        return \Yii::$app->cashbox->responsibleUser;
    }

    public function actionChangeitemcount(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        \Yii::$app->response->format = 'json';

        if(\Yii::$app->cashbox->changeCount(\Yii::$app->request->post("itemID"), \Yii::$app->request->post("count"))){
            return [
                'itemsCount'    =>  \Yii::$app->cashbox->itemsCount,
                'sum'           =>  \Yii::$app->cashbox->sum,
                'toPay'         =>  \Yii::$app->cashbox->toPay,
                'wholesaleSum'  =>  \Yii::$app->cashbox->wholesaleSum,
                'priceType'     =>  \Yii::$app->cashbox->priceType,
            ];
        }

        return false;
    }

    public function actionChangecustomer(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        \Yii::$app->response->format = 'json';

        \Yii::$app->cashbox->changeCustomer(\Yii::$app->request->post("customerID"));

        return true;
    }

    public function actionFindcustomer(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        \Yii::$app->response->format = 'json';

        $attribute  = \Yii::$app->request->get("attribute");
        $query      = \Yii::$app->request->get("query");

        $customer   = Customer::find()->select(['ID', 'Company', 'phone', 'cardNumber'])->where(['like', $attribute, $query]);

        return $customer->all();
    }

    public function actionChecks(){
        $dataProvider = new ActiveDataProvider([
            'query'     =>  CashboxOrder::find()->where(['postpone' => 1]),
            'sort'      =>  [
                'defaultOrder'  =>  ['createdTime' =>  SORT_DESC]
            ]
        ]);

        $customersIDs = [];
        $customers = [];

        foreach($dataProvider->getModels() as $sell){
            $customersIDs[] = $sell->customerID;
        }

        foreach(Customer::find()->where(['in', 'id', $customersIDs])->each() as $customer){
            $customers[$customer->ID] = $customer;
        }

        return $this->render('checks', [
            'checksItems'   =>  $dataProvider,
            'customers'     =>  $customers
        ]);
    }

    public function actionReturns(){
        $orders = CashboxOrder::find()->where(['return' => 1]);

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

        $cashboxOrder = CashboxOrder::findOne(['ID'    =>  \Yii::$app->request->post("orderID")]);

        if(!$cashboxOrder){
            throw new NotFoundHttpException("Такого заказа не существует!");
        }

        return $this->renderAjax('_orderPreview', [
            'goods' =>  new ActiveDataProvider([
                'query' =>  SborkaItem::find()->where(['orderID'   =>  $cashboxOrder->createdOrder]),
            ])
        ]);
    }

    public function actionSales(){
        $orders = CashboxOrder::find();

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

        $dataProvider = new ActiveDataProvider([
            'query'     =>  $orders->andWhere(['return' => 0]),
            'sort'      =>  [
                'defaultOrder'  =>  ['doneTime' =>  SORT_DESC]
            ]
        ]);

        $customersIDs = [];
        $customers = [];

        foreach($dataProvider->getModels() as $sell){
            $customersIDs[] = $sell->customerID;
        }

        foreach(Customer::find()->where(['in', 'id', $customersIDs])->each() as $customer){
            $customers[$customer->ID] = $customer;
        }

        return $this->render('sales', [
            'customers'     =>  $customers,
            'salesProvider' =>  $dataProvider
        ]);
    }

    public function actionReturnorder(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        \Yii::$app->cashbox->refund();

        return \Yii::$app->cashbox->order->id;
    }

    public function actionPostponecheck(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        if(!\Yii::$app->cashbox->postpone()){
            throw new ErrorException("Произошла ошибка при выполнении метода actionPostponeCheck");
        }

        return \Yii::$app->cashbox->order->id;
    }

    public function actionLoadpostpone(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        \Yii::$app->cashbox->loadPostpone(\Yii::$app->request->post("postponeOrderID"));

        return true;
    }

    public function actionAdditem(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        $itemID = \Yii::$app->request->post("itemID");

        $good = Good::find()->where(['or', 'ID = '.$itemID, 'BarCode1 = '.$itemID, 'Code = '.$itemID])->one();

        if(!$good){
            throw new NotFoundHttpException("Товар с идентификатором `".$itemID."` не найден!");
        }

        \Yii::$app->response->format = 'json';

        \Yii::$app->cashbox->put($good->ID);

        return [
            'toPay'         =>  \Yii::$app->cashbox->toPay,
            'sum'           =>  \Yii::$app->cashbox->sum,
            'itemsCount'    =>  \Yii::$app->cashbox->itemsCount,
            'wholesaleSum'  =>  \Yii::$app->cashbox->wholesaleSum,
            'priceType'     =>  \Yii::$app->cashbox->priceType
        ];
    }

    public function actionRemoveitem()
    {
        if (!\Yii::$app->request->isAjax) {
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        \Yii::$app->response->format = 'json';

        $itemID = \Yii::$app->request->post("itemID");

        if ($itemID != 'all' && !isset(\Yii::$app->cashbox->items[$itemID])) {
            throw new NotFoundHttpException("Такой товар не найден!");
        }

        if (\Yii::$app->cashbox->itemsCount > 0) {
            if ($itemID == 'all') {
                foreach (\Yii::$app->cashbox->items as $item) {
                    \Yii::$app->cashbox->remove($item->itemID);
                }
            } else {
                \Yii::$app->cashbox->remove($itemID);

                return [
                    'itemsCount' => \Yii::$app->cashbox->itemsCount,
                    'sum' => \Yii::$app->cashbox->sum,
                    'toPay' => \Yii::$app->cashbox->toPay,
                    'wholesaleSum' => \Yii::$app->cashbox->wholesaleSum,
                    'priceType' => \Yii::$app->cashbox->priceType,
                ];
            }
        }

        return true;
    }

    public function actionLogin()
    {
        $this->layout = 'login';

        if(\Yii::$app->request->isAjax){
            return \Yii::$app->user->isGuest ? '1' : '0';
        }


        /*if(!\Yii::$app->user->isGuest){
            if(!empty(\Yii::$app->user->identity->default_route)){
                return $this->redirect(\Yii::$app->user->identity->default_route);
            }

            return $this->redirect(Url::home());
        }*/

        $model = new LoginForm();

        if(!empty(\Yii::$app->params['configuration']) && !empty(\Yii::$app->params['configuration']->autologin)){
            foreach(\Yii::$app->params['configuration']->autologin as $user){
                if($user['ip'] == $_SERVER['REMOTE_ADDR']){
                    $model->username = $user['username'];

                    if(\Yii::$app->user->login($model->getUser(), 3600*24)){
                        return $this->redirect(Url::home());
                    }
                }
            }
        }

        if ($model->load(\Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(!empty(\Yii::$app->user->identity->default_route) ? \Yii::$app->user->identity->default_route : Url::home());
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
