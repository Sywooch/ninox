<?php
namespace frontend\controllers;

use common\models\DomainsDeliveryPayments;
use frontend\models\Cart;
use frontend\models\Customer;
use frontend\models\OrderForm;
use Yii;
use common\models\Domain;
use common\models\Pagetype;

use frontend\models\Banner;
use frontend\models\Category;
use frontend\models\Good;
use frontend\models\Question;
use frontend\models\Review;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;

use yii\base\ErrorException;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Cookie;

/**
 * Site controller
 */
class SiteController extends Controller
{

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'leftBanner'        =>  Banner::getByAlias('main_left_banner', false),
            'rightBanner'       =>  Banner::getByAlias('main_right_banner', false),
            'centralBanners'    =>  Banner::getByAlias('slider_v3'),
            'reviews'           =>  Review::getReviews(),
            'questions'         =>  Question::getQuestions(),
        ]);
    }

    public function actionShowtovar($link){
        $id = preg_replace('/[^g(.)]+\D+/', '', $link);
        $id = preg_replace('/\D+/', '', $id);

        $good = Good::findOne(['`goods`.`ID`' => $id]);

        if(!$good){
            return \Yii::$app->runAction('site/error');
        }

        $category = Category::findOne(['ID' => $good->GroupID]);

        $mainCategory = null;

        if(strlen($category->Code) != 3){
            foreach($category->getParents() as $parent){
                if(empty($mainCategory)){
                    $mainCategory = $parent;
                }

                \Yii::$app->params['breadcrumbs'][] = [
                    'url'   =>  '/'.$parent->link,
                    'label' =>  $parent->Name
                ];
            }
        }

        if(empty($mainCategory)){
            $mainCategory = $category;
        }

        \Yii::$app->params['breadcrumbs'][] = [
            'url'   =>  '/'.$category->link,
            'label' =>  $category->Name
        ];

        return $this->render('goodCard', [
            'mainCategory'  =>  $mainCategory,
            'good'          =>  $good,
            'category'      =>  $category
        ]);
    }

    public function actionRenderpage($url){
        $category = Category::findOne(['link' => $url]);

        if(empty($category)){
            return $this->runAction('error');
        }

        $pageType = Pagetype::findOne(['id' => $category->pageType]);

        if($category->pageType == 0 || $category->pageType == -1 || $category->pageType == 13){
            return $this->run('site/rendercategory', [
                'category'  =>  $category,
                'view'      =>  $pageType->page
            ]);
        }else{
            //Переделать потом под все типы страниц
            return $this->run('site/rendercategory', [
                'category'  =>  $category,
                'view'      =>  $pageType->page
            ]);
        }

    }

    public function actionOrder(){
        $customerPhone = '';

        if(\Yii::$app->user->isGuest){
            if(!empty(\Yii::$app->request->post("phone"))){
                $customerPhone = preg_replace('/\D+/', '', \Yii::$app->request->post("phone"));

                if(\Yii::$app->request->cookies->getValue("customerPhone") != $customerPhone){
                    \Yii::trace('phone was changed: from '.\Yii::$app->request->cookies->get("customerPhone").' to '.$customerPhone);
                    \Yii::$app->response->cookies->add(new Cookie([
                        'name'      =>  'customerPhone',
                        'value'     =>  $customerPhone
                    ]));
                }
            }elseif(!empty(\Yii::$app->request->cookies->getValue("customerPhone"))){
                $customerPhone = \Yii::$app->request->cookies->getValue("customerPhone");
            }
        }else{
            $customerPhone = \Yii::$app->user->identity->phone;
        }

        if(empty($customerPhone)){
            return \Yii::$app->response->redirect('/#modalCart');
        }

        if(\Yii::$app->cart->itemsCount < 1){
            return $this->render('emptyCart');
        }else if(\Yii::$app->cart->cartSumm < \Yii::$app->params['domainInfo']['minimalOrderSum']){
            return $this->render('buyMore');
        }

        $order = new OrderForm();

        if(\Yii::$app->user->isGuest){
            $customer = Customer::findOne(['phone' => $customerPhone]);
        }else{
            $customer = \Yii::$app->user->identity;
        }

        if(!$customer){
            throw new ErrorException("Клиент не найден!");
        }

        Cart::updateAll(['customerID' => $customer->ID], '`cartCode` = \''.\Yii::$app->cart->cartCode.'\'');

        if(\Yii::$app->request->post("OrderForm")){
            $order->load(\Yii::$app->request->post());
        }

        $order->loadCustomer($customer);

        if(\Yii::$app->request->post("OrderForm")){
            if($order->validate() && $order->create()){
                return $this->render('order_success', [
                    'model' =>  $order
                ]);
            }
        }

        $this->layout = 'order';

	    $domainConfiguration = DomainsDeliveryPayments::getConfigArray();

	    echo '<pre>';
	    var_dump($domainConfiguration);
		echo '</pre>';
        return $this->render('order2', [
            'model'             =>  $order,
			'domainConfiguration'      =>  $domainConfiguration
        ]);
    }

    public function actionModifycart(){
        \Yii::$app->response->format = 'json';
	    $itemID = \Yii::$app->request->post("itemID");
        $count = \Yii::$app->request->post("count");
		$item = $count == 0 ? \Yii::$app->cart->remove($itemID) : \Yii::$app->cart->put($itemID, $count);
	    $goods = [];
	    foreach(\Yii::$app->cart->goods as $good){
			if($good->priceModified){
				$goods[] = [
					'priceRuleID'  =>  $good->priceRuleID,
					'priceModified'  =>  $good->priceModified,
					'price'  =>  $good->retail_price,
				];
			}
	    }
	    return [
		    'cartSumm'      =>  \Yii::$app->cart->cartSumm,
		    'cartRealSumm'  =>  \Yii::$app->cart->cartRealSumm,
		    'inCart'        =>  $count == 0 ? 0 : $item->count,
		    'goods'         =>  $goods,
	    ];
    }

	public function actionGetcart(){
		return $this->renderAjax('cart', [
			'dataProvider'	=>	new ActiveDataProvider([
				'query'         =>  \Yii::$app->cart->goodsQuery(),
				'pagination'    =>  [
					'pageSize'  =>  0
				]
			])
		]);
	}

    public function actionSuccess($order = []){
        if(!empty($order)){
            return $this->render('order_success', [
                'order' =>  $order
            ]);
        }else{
            return $this->render('emptycart');
        }
    }

    public function actionRendersomepage($category = null, $view = null){

    }

    public function actionRendercategory($category = null, $view = null){
        $view != null ? $view : 'category';

        if(empty($category)){
            return $this->run('site/error');
        }

        if(strlen($category->Code) != 3){
            foreach($category->getParents() as $parent){
                \Yii::$app->params['breadcrumbs'][] = [
                    'url'   =>  '/'.$parent->link,
                    'label' =>  $parent->Name
                ];
            }
        }

        return $this->render($view, [
            'category'  =>  $category,
            'showText'  =>  true,
            'goods'     =>  new ActiveDataProvider([
                'query'         =>  $category->goods(),
                'pagination'    =>  [
                    'pageSize'  =>  '15'
                ]
            ])
        ]);
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'captcharegistermodal' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if(\Yii::$app->cart->itemsCount > 0){
                Cart::updateAll(['customerID'   =>  \Yii::$app->user->identity->ID], ['cartCode' => \Yii::$app->cart->cartCode]);
            }

            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }



    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionRegister()
    {
        if(!\Yii::$app->user->isGuest){
            return $this->goBack(Url::previous());
        }

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

	public function beforeAction($action){
		$domainInfo = Domain::findOne(['name' => \Yii::$app->request->getServerName()]);
		\Yii::$app->params['domainInfo'] = empty($domainInfo) ? \Yii::$app->params['domainInfo'] : $domainInfo;

        return parent::beforeAction($action);
	}
}
