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
     * @type \cashbox\components\Cashbox
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
            \Yii::$app->user->identity->save();
            //echo \Yii::$app->user->identity->can('1') ? 'true' : 'false'; //если false - значит чувака нельзя пускать
        }

        return parent::beforeAction($action);
    }

    public function actionIndex(){
        if(!empty($this->cashbox->cashboxOrder)){
            $order = $this->cashbox->cashboxOrder;
        }else{
            $order = new CashboxOrder();
        }

        $customer = $this->cashbox->customer;

        if(\Yii::$app->request->post("CustomerForm")){
            $customerForm = new CustomerForm();
            $customerForm->load(\Yii::$app->request->post());

            if($customerForm->save()){
                if(!$order->isNewRecord){
                    $order->customerID = $customerForm->id;

                    $order->save(false);
                }

                \Yii::$app->response->cookies->add(new Cookie([
                    'name'  =>  'cashboxCurrentCustomer',
                    'value' =>  $customerForm->id
                ]));

                $customer = $customerForm->id;
            }
        }

        if(!empty($order->customerID)){
            $customer = $order->customerID;
        }

        if($customer){
            $customer = Customer::findOne($customer);
        }

        $orderItems = new ActiveDataProvider([
            'query'     =>  $this->cashbox->cashboxItemsQuery(),
            'pagination'    =>  [
                'pageSize'  =>  0
            ]
        ]);

        $orderItems->setSort([
            'defaultOrder'  =>  [
                'added' =>  SORT_ASC
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
            'manager'           =>  Siteuser::getActiveUsers()[$this->cashbox->responsibleUser]
        ]);
    }

    public function actionCompletesell(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Этот метод доступен только через ajax!");
        }

        return $this->cashbox->sell(\Yii::$app->request->post("actualAmount"));
    }

    public function actionChangecashboxtype(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Этот метод доступен только через ajax!");
        }

        \Yii::$app->response->format = 'json';

        $this->cashbox->changePriceType();

        if(!empty($this->cashbox->cashboxOrder)){
            $this->cashbox->recalculate();

            return $this->cashbox->getSummary();
        }

        return $this->cashbox->getSummary();
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

        $this->cashbox->changeManager(\Yii::$app->request->post("manager"));

        return $this->cashbox->responsibleUser;
    }

    public function actionChangeitemcount(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        \Yii::$app->response->format = 'json';

        if($this->cashbox->changeCount(\Yii::$app->request->post("itemID"), \Yii::$app->request->post("count"))){
            return $this->cashbox->getSummary();
        }

        return false;
    }

    public function actionChangecustomer(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        \Yii::$app->response->format = 'json';

        $this->cashbox->changeCustomer(\Yii::$app->request->post("customerID"));

        return true;
    }

    public function actionFindcustomer(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        \Yii::$app->response->format = 'json';

        $attribute  = \Yii::$app->request->get("attribute");
        $query      = \Yii::$app->request->get("query");

        $customer   = Customer::find()->select(['ID', 'Company', 'phone', 'cardNumber'])->where(['like', $attribute, $query])->limit(10);

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

        if(empty(\Yii::$app->request->post("orderID"))){
            throw new BadRequestHttpException("пустой orderID!");
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

    public function actionLoadorder(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("");
        }

        if(empty(\Yii::$app->request->post("orderID"))){
            throw new BadRequestHttpException("");
        }

        $order = CashboxOrder::findOne(\Yii::$app->request->post("orderID"));

        if(!$order){
            throw new NotFoundHttpException();
        }

        if(!empty($order->createdOrder)){
            foreach(SborkaItem::find()->where(['orderID' => $order->createdOrder])->each() as $assemblyItem){
                $cashboxItem = CashboxItem::findOne(['itemID' => $assemblyItem->itemID, 'orderID' => $order->id]);

                if(!$cashboxItem){
                    $cashboxItem = new CashboxItem();
                }

                $cashboxItem->loadAssemblyItem($assemblyItem, $order->id);

                $cashboxItem->save(false);
            }
        }

        $this->cashbox->loadOrder(\Yii::$app->request->post("orderID"), \Yii::$app->request->post("dropOrder", false));

        return true;
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
            case 'range':
                $dateFrom = \Yii::$app->request->get("dateFrom");
                $dateTo = \Yii::$app->request->get("dateTo");
                $orders
                    ->andWhere('doneTime <= \''.\Yii::$app->formatter->asDatetime($dateTo, 'php:Y-m-d H:i:s').'\'')
                    ->andWhere('doneTime >= \''.\Yii::$app->formatter->asDatetime($dateFrom, 'php:Y-m-d H:i:s').'\'');
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

        $oldOrder = $this->cashbox->cashboxOrder->id;

        if(\Yii::$app->request->post("orderID")){
            $this->cashbox->loadOrder(\Yii::$app->request->post("orderID"));
        }

        $orderID = $this->cashbox->refund()->id;

        if(!empty($oldOrder) && $orderID != $oldOrder){
            $this->cashbox->loadOrder($oldOrder);

            $orderID = $this->cashbox->cashboxOrder->id;
        }

        return $orderID;
    }

    public function actionPostponecheck(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        $order = $this->cashbox->cashboxOrder;

        if(!$this->cashbox->postpone()){
            throw new ErrorException("Произошла ошибка при выполнении метода actionPostponeCheck");
        }

        return $order->id;
    }

    public function actionLoadpostpone(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        $this->cashbox->loadPostpone(\Yii::$app->request->post("postponeOrderID"));

        return true;
    }

    public function actionAdditem(){
        if(!\Yii::$app->request->isAjax){
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        $itemID = \Yii::$app->request->post("itemID");

        $promoCode = Promocode::findOne(['code' => $itemID]);

        if($promoCode && $this->cashbox->cashboxOrder){
            $this->cashbox->promoCode = $this->cashbox->cashboxOrder->promoCode = $promoCode->code;
            $this->cashbox->cashboxOrder->save(false);

            $this->cashbox->addDiscount(Pricerule::findOne($promoCode->rule));

            \Yii::$app->response->format = 'json';

            return $this->cashbox->getSummary();
        }

        $good = Good::find()
            ->where(['or', "`BarCode2` = '{$itemID}'", "`BarCode1` = '{$itemID}'", "`Code` = '{$itemID}'", "`ID` = '{$itemID}'"])
            ->orderBy('`Barcode2` DESC')
            ->one();

        if(!$good){
            throw new NotFoundHttpException("Товар с идентификатором `".$itemID."` не найден!");
        }

        \Yii::$app->response->format = 'json';

        $this->cashbox->put($good->ID);

        return $this->cashbox->getSummary();
    }

    public function actionRemoveitem()
    {
        if (!\Yii::$app->request->isAjax) {
            throw new MethodNotAllowedHttpException("Данный метод возможен только через ajax!");
        }

        \Yii::$app->response->format = 'json';

        $itemID = \Yii::$app->request->post("itemID");

        if ($itemID != 'all' && !isset($this->cashbox->items[$itemID])) {
            throw new NotFoundHttpException("Такой товар не найден!");
        }

        if ($this->cashbox->itemsCount > 0) {
            if ($itemID == 'all') {
                $this->cashbox->clear();
            } else {
                $this->cashbox->remove($itemID);

                return $this->cashbox->getSummary();
            }
        }

        return true;
    }

    public function actionLogin(){
        $this->layout = 'login';

        if(\Yii::$app->request->isAjax && empty(\Yii::$app->request->post("LoginForm"))){
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

    public function actionLogout()
    {
        Url::remember(\Yii::$app->request->referrer, 'previous');

        Yii::$app->user->logout();

        return $this->refresh();
    }
}
