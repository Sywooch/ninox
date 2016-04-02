<?php
namespace frontend\controllers;

use common\helpers\Formatter;
use common\models\DomainDeliveryPayment;
use frontend\helpers\PriceRuleHelper;
use frontend\models\BannersCategory;
use frontend\models\Cart;
use frontend\models\Customer;
use frontend\models\CustomerWishlist;
use frontend\models\History;
use frontend\models\ItemRate;
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
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

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
        if(\Yii::$app->request->isAjax){
            switch(\Yii::$app->request->get("act")){
                case 'goodsRow':
                    return $this->renderGoodsRow(\Yii::$app->request->get("type"));
                    break;
            }
        }

        return $this->render('index', [
            'leftBanner'        =>  BannersCategory::findOne(['alias' => 'main_left_banner'])->banners[0],//Banner::getByAlias('main_left_banner', false),
            'rightBanner'       =>  BannersCategory::findOne(['alias' => 'main_right_banner'])->banners[0],//Banner::getByAlias('main_right_banner', false),
            'centralBanners'    =>  BannersCategory::findOne(['alias' => 'slider_v3'])->banners,
            'reviews'           =>  Review::getReviews(),
            'goodsDataProvider' =>  new ArrayDataProvider([
                'models' =>  Good::find()->where(['Deleted' => 0])->orderBy('vkl_time DESC')->limit('4')->all(),
                'pagination'    =>  [
                    'pageSize'  =>  4
                ]
            ]),
            'questions'         =>  Question::getQuestions(),
        ]);
    }

    public function renderGoodsRow($type){
        $query = Good::find()->where(['Deleted' => 0]);

        switch($type){
            case 'sale':
                $query->andWhere('discountType != 0');
                break;
            case 'new':
            default:
                $query->orderBy('vkl_time DESC');
                break;
        }

        return $this->renderAjax('index/goods_row', [
            'dataProvider'  =>  new ArrayDataProvider([
                'models'    =>  $query->limit(4)->all()
            ])
        ]);
    }

    public function actionShowtovar($link){
        $id = preg_replace('/\D+/', '', preg_replace('/[^g(.)]+\D+/', '', $link));

        $good = Good::findOne(['`goods`.`ID`' => $id]);

        if(!$good){
            return \Yii::$app->runAction('site/error');
        }

        $category = Category::findOne($good->GroupID);

        $mainCategory = null;

        if(strlen($good->categoryCode) != 3){
            foreach($good->category->getParents() as $parent){
                if(empty($mainCategory)){
                    $mainCategory = $parent;
                }

                $this->getView()->params['breadcrumbs'][] = [
                    'url'   =>  '/'.$parent->link,
                    'label' =>  $parent->Name
                ];
            }
        }

        if(empty($mainCategory)){
            $mainCategory = $category;
        }

        $this->getView()->params['breadcrumbs'][] = [
            'url'   =>  '/'.$category->link,
            'label' =>  $category->Name
        ];

        (new PriceRuleHelper())->recalc($good, true);

        return $this->render('_shop_item_card', [
            'mainCategory'  =>  $mainCategory,
            'good'          =>  $good,
            'category'      =>  $category,
        ]);
    }

    /**
     * @param string $url
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionRenderpage($url){
        $category = Category::findOne(['link' => $url]);

        if(empty($category)){
            throw new NotFoundHttpException("Страница не найдена!");
        }

        \Yii::trace($category->viewFile);

        switch($category->viewFile){
            case '0':
            case '-1':
            case '13':
            case 'category':
                return $this->renderCategory($category);
                break;
            default:
                return $this->renderStaticPage($category);
                break;
        }
    }

    /**
     * Рендерит категорию
     *
     * @param Category $category
     *
     * @return string
     */
    public function renderCategory($category){
        switch($category->viewFile){
            case -1:
            case 13:
            case 0:
            case 'category':
            default:
                $view = 'category';
                break;
        }

        if(strlen($category->Code) != 3){
            foreach($category->parents as $parent){
                $this->getView()->params['breadcrumbs'][] = [
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

    public function renderStaticPage($pageInfo){
        return $this->render($pageInfo->viewFile, empty($pageInfo->viewOptions) ? [] : $pageInfo->viewOptions);
    }

    public function actionOrder(){
        $customerPhone = '';

        if(\Yii::$app->user->isGuest){
            if(!empty(\Yii::$app->request->post("phone"))){
                $customerPhone = preg_replace('/\D+/', '', \Yii::$app->request->post("phone"));

                if(\Yii::$app->request->cookies->getValue("customerPhone") != $customerPhone){
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
        }else if(\Yii::$app->cart->cartRealSumm < \Yii::$app->params['domainInfo']['minimalOrderSum']){
            return $this->render('buyMore');
        }

        $order = new OrderForm();

        if(\Yii::$app->user->isGuest){
            $customer = Customer::findOne(['phone' => $customerPhone]);
        }else{
            $customer = \Yii::$app->user->identity;
        }

        if($customer){
	        Cart::updateAll(['customerID' => $customer->ID], '`cartCode` = \''.\Yii::$app->cart->cartCode.'\'');
	        $order->loadCustomer($customer);
        }


        if(\Yii::$app->request->post("OrderForm") && $order->load(\Yii::$app->request->post()) && $order->validate() && $order->create()){
            $email = \Yii::$app->email->orderEmail(History::findOne($order->createdOrder));

            \Yii::trace($email);

            return $this->render('order_success', [
                'model' =>  $order
            ]);
        }

        $this->layout = 'order';

	    $domainConfiguration = DomainDeliveryPayment::getConfigArray();

        return $this->render('order2', [
            'model'                 =>  $order,
			'domainConfiguration'   =>  $domainConfiguration,
	        'customer'              =>  $customer
        ]);
    }

    public function actionGetcart(){
        if(!\Yii::$app->request->isAjax){
            throw new BadRequestHttpException();
        }

        return $this->renderAjax('_cart_items');
    }

    public function actionModifycart(){
        \Yii::$app->response->format = 'json';
	    $itemID = \Yii::$app->request->post("itemID");
        $count = \Yii::$app->request->post("count");
	    $wholesaleBefore = \Yii::$app->cart->wholesale;
		$item = $count == 0 ? \Yii::$app->cart->remove($itemID) : \Yii::$app->cart->put($itemID, $count);
	    $items = [];
	    foreach(\Yii::$app->cart->goods as $good){
			if($good->priceModified || $good->ID == $itemID || $wholesaleBefore != \Yii::$app->cart->wholesale){
                $discount = 0;
                switch($good->discountType){
                    case 1:
                        $discount = '-'.Formatter::getFormattedPrice($good->discountSize);
                        break;
                    case 2:
                        $discount = '-'.$good->discountSize.'%';
                        break;
                    default:
                        break;
                }
				$items[$good->ID] = [
					'retail'      =>  Formatter::getFormattedPrice($good->retailPrice),
					'wholesale'   =>  Formatter::getFormattedPrice($good->wholesalePrice),
					'discount'    =>  $discount,
					'amount'      =>  Formatter::getFormattedPrice((\Yii::$app->cart->wholesale ? $good->wholesalePrice : $good->retailPrice) * $good->inCart),
				];
			}
	    }
	    return [
		    'discount'      =>  Formatter::getFormattedPrice(\Yii::$app->cart->cartSumWithoutDiscount - \Yii::$app->cart->cartSumm),
			'real'          =>  Formatter::getFormattedPrice(\Yii::$app->cart->cartSumWithoutDiscount),
	        'cart'          =>  Formatter::getFormattedPrice(\Yii::$app->cart->cartSumm),
		    'remind'        =>  Formatter::getFormattedPrice(\Yii::$app->params['domainInfo']['wholesaleThreshold'] - \Yii::$app->cart->cartWholesaleRealSumm),
		    'button'        =>  \Yii::$app->cart->cartRealSumm < \Yii::$app->params['domainInfo']['minimalOrderSum'] || \Yii::$app->cart->itemsCount < 1,
		    'wholesale'     =>  \Yii::$app->cart->wholesale,
		    'count'         =>  \Yii::$app->cart->itemsCount,
		    'count-ext'     =>  \Yii::t('shop', '{n, plural, =0{# товаров} =1{# товар} few{#
									товара}	many{# товаров} other{# товар}}', [
                                    'n'	=>	\Yii::$app->cart->itemsCount
                                ]),
		    'items'         =>  $items,
	    ];
    }

    public function actionSetitemrate(){
        \Yii::$app->response->format = 'json';
        $itemID = \Yii::$app->request->post("itemID");
        $rate = \Yii::$app->request->post("rate");
        if($itemID && $rate){
            $itemRate = ItemRate::findOne([
                'itemID'        =>  $itemID,
                'ip'            =>  sprintf('%u', ip2long(\Yii::$app->request->getUserIP())),
                'customerID'    =>  \Yii::$app->user->isGuest ? 0 : \Yii::$app->user->id
            ]);
            if(!$itemRate){
                $itemRate = new ItemRate([
                    'itemID'        =>  $itemID,
                    'ip'            =>  sprintf('%u', ip2long(\Yii::$app->request->getUserIP())),
                    'customerID'    =>  \Yii::$app->user->isGuest ? 0 : \Yii::$app->user->id,
                ]);
            }

            $itemRate->rate = $rate;
            $itemRate->date = date('Y-m-d H:i:s');

            $itemRate->save(false);
            return $itemRate->average;
        }
    }

    public function actionAddtowishlist(){
        \Yii::$app->response->format = 'json';
        $itemID = \Yii::$app->request->post("itemID");
        if($itemID && !\Yii::$app->user->isGuest){
            $wish = CustomerWishlist::findOne([
                'itemID'        =>  $itemID,
                'customerID'    =>  \Yii::$app->user->id
            ]);
            if(!$wish){
                $wish = new CustomerWishlist([
                    'itemID'        =>  $itemID,
                    'customerID'    =>  \Yii::$app->user->id,
                ]);
            }

            $wish->price = Good::findOne($itemID)->wholesalePrice;
            $wish->date = date('Y-m-d H:i:s');

            $wish->save(false);
            return true;
        }else{
            return false;
        }
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
            return $this->goBack();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if(\Yii::$app->cart->itemsCount > 0){
                Cart::updateAll(['customerID'   =>  \Yii::$app->user->identity->ID], ['cartCode' => \Yii::$app->cart->cartCode]);
            }

            return $this->goBack();
        } else {
            if(\Yii::$app->request->isAjax){
                return $this->renderAjax('login', [
                    'model' =>  $model
                ]);
            }else{
                return $this->render('login', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Формирует и возвращает набор слов для поиска
     *
     * @param       $string
     * @internal   	param $getSearchStatement
     * @return      array|bool
     * @author     	Дмитрий Панченко <alanwolf88@gmail.com>
     * @version   	1.0
     */
    function getSearchStatement($string){
        $vowels = array(
            'ru_RU' => '[аеиоуыэюяьъй]',
            'uk_UA' => '[аеиіоуєюяїьй]'
        );
        $pattern = $vowels[\Yii::$app->language];
        $pattern = '/'.$pattern.'+?'.$pattern.'$|'.$pattern.'$/';
        $return = $r1 = $r2 = '';
        $string = mb_strtolower($string, 'UTF-8');
        //$string = preg_replace('/\w/', '', $string); allow latin symbols
        $string = preg_replace('/[.,;\'*"]/', ' ', $string);
        $words = explode(' ', $string);
        foreach($words as $word){
            $word = trim($word, ' ');
            if(mb_strlen($word, 'UTF-8') > 3 || (filter_var($word, FILTER_VALIDATE_INT) && mb_strlen($word, 'UTF-8') > 2)){
                $r1 .= '+'.$word.' ';
                $word = preg_replace($pattern, '', $word);
                $r2 .= '+'.$word.'* ';
            }
        }
        $return = (($r1 != '') ? '>('.$r1.')' : '').' '.(($r2 != '') ? '<('.$r2.')' : '');
        return $return;
    }

    public function actionSearch(){
        $suggestion = \Yii::$app->request->get("string");

        $name = $this->getSearchStatement($suggestion);

        $goodsQuery = Good::find()
            ->select("`goods`.*, (MATCH(`goods`.`Name`) AGAINST('{$name}' IN BOOLEAN MODE)) AS `relevant`")
            ->leftJoin('goodsgroups', '`goods`.`GroupID` = `goodsgroups`.`ID`')
            ->where("MATCH(`goods`.`Name`) AGAINST('{$name}' IN BOOLEAN MODE)")
            ->orWhere(['like', '`goods`.`Code`', $suggestion])
            ->orWhere(['like', '`goods`.`BarCode2`', $suggestion])
            ->orWhere(['like', '`goodsgroups`.`Name`', $suggestion])
            ->andWhere('`goods`.`show_img` = 1 AND `goods`.`deleted` = 0 AND (`goods`.`PriceOut1` != 0 AND `goods`.`PriceOut2` != 0)')
            ->orderBy('IF (`goods`.`count` <= \'0\' AND `goods`.`isUnlimited` = \'0\', \'FIELD(`goods`.`count` DESC)\', \'FIELD()\'), `relevant` DESC');

        if(\Yii::$app->request->isAjax){
            \Yii::$app->response->format = 'json';

            $goods = [];

            foreach($goodsQuery->limit(10)->each() as $good){
                $goodInfo = [
                    'ID'        =>  $good->ID,
                    'code'      =>  $good->Code,
                    'price'     =>  $good->wholesalePrice,
                    'price2'    =>  $good->retailPrice,
                    'link'      =>  $good->link,
                    'name'      =>  $good->Name,
                    'photo'     =>  $good->ico
                ];

                if(!empty($good->category)){
                    $goodInfo['category'] = $good->category->Name;
                }

                if(!empty($good->BarCode2)){
                    $goodInfo['vendorCode'] = $good->BarCode2;
                }

                $goods[] = $goodInfo;
            }

            return $goods;
        }

        return $this->render('searchResults', [
            'goods' =>  new ActiveDataProvider([
                'query' =>  $goodsQuery,
                'pagination'    =>  [
                    'pageSize'  =>  '15'
                ]
            ])
        ]);
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

    public function actionPay()
    {
        $model=new PayForm;
        //some form code was here...
        $this->render('vozvra', [
            'model' => $model,
        ]);

        return $this->render('view', [
            'model' => $model,
        ]);
    }
}

