<?php
namespace frontend\controllers;

use common\helpers\Formatter;
use common\models\DomainDeliveryPayment;
use common\models\GoodTranslation;
use common\models\UsersInterests;
use frontend\helpers\PriceRuleHelper;
use frontend\models\BannersCategory;
use frontend\models\CallbackForm;
use frontend\models\Cart;
use frontend\models\CommentForm;
use frontend\models\Customer;
use frontend\models\CustomerWishlist;
use frontend\models\History;
use frontend\models\ItemRate;
use frontend\models\OrderForm;
use frontend\models\PaymentConfirmForm;
use frontend\models\ReturnForm;
use frontend\models\ReviewForm;
use frontend\models\SborkaItem;
use frontend\models\SubscribeForm;
use frontend\models\UsersInterestsForm;
use Prophecy\Exception\Doubler\ClassNotFoundException;
use kartik\form\ActiveForm;
use yii;
use common\models\Domain;

use frontend\models\Category;
use frontend\models\Good;
use frontend\models\Question;
use frontend\models\Review;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;

use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
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

        $goodsDataProvider = new ActiveDataProvider([
            'query' =>  Good::find()
                ->where(['`goods`.`Deleted`' => '0'])
                ->andWhere('`goods`.`count` > 0')
                ->joinWith('translations')
                ->andWhere('`'.GoodTranslation::tableName().'`.`enabled` = \'1\'')
                ->andWhere('`'.GoodTranslation::tableName().'`.`language` = \''.\Yii::$app->language.'\''),
            'pagination'    =>  [
                'pageSize'  =>  (!empty(\Yii::$app->request->get("page")) && \Yii::$app->request->get("page") != 1) ? 5 : 4
            ],
            'sort'  =>  [
                'defaultOrder'   =>  [
                    'vkl_time'  =>  SORT_DESC
                ]
            ]
        ]);

        return $this->render('index', [
            'leftBanner'        =>  BannersCategory::findOne(['alias' => 'main_left_banner'])->banners[0],//Banner::getByAlias('main_left_banner', false),
            'rightBanner'       =>  BannersCategory::findOne(['alias' => 'main_right_banner'])->banners[0],//Banner::getByAlias('main_right_banner', false),
            'centralBanners'    =>  BannersCategory::findOne(['alias' => 'slider_v3'])->banners,
            'reviews'           =>  Review::getReviews(),
            'goodsDataProvider' =>  $goodsDataProvider,
            'questions'         =>  Question::getQuestions(),
        ]);
    }

    /**
     * @param $type
     * @return string
     */
    public function renderGoodsRow($type){
        $query = Good::find()
            ->where(['`goods`.`Deleted`' => 0])
            ->joinWith('translations')
            ->andWhere('`goods`.`count` > 0')
            ->andWhere('`'.GoodTranslation::tableName().'`.`language` = \''.\Yii::$app->language.'\'')
            ->andWhere('`'.GoodTranslation::tableName().'`.`enabled` = \'1\'');

        switch($type){
            case 'best':
            default:
                $query->leftJoin(SborkaItem::tableName(), '`sborka`.`itemID` = `goods`.`ID`')
                    ->groupBy('`sborka`.`itemID`')
                    ->orderBy('COUNT(`sborka`.`itemID`)');

                if($query->andWhere("added > '".(time() - 172800)."'")->count() > 0){
                    $query->andWhere("added > '".(time() - 172800)."'");
                }
                break;
            case 'sale':
                $query->andWhere('`goods`.`discountType` != \'0\'');
                break;
            case 'new':
                //default:
                $query->orderBy('`goods`.`vkl_time` DESC');
                break;
        }

        return $this->renderAjax('index/goods_row', [
            'dataProvider'  =>  new ActiveDataProvider([
                'query' =>  $query,
                'pagination'    =>  [
                    'pageSize'  =>  !empty(\Yii::$app->request->get("page")) && \Yii::$app->request->get("page") != 1 ? 5 : 4
                ],
            ]),
            /*'dataProvider'  =>  new ArrayDataProvider([
                'models'    =>  $query->limit(4)->all()
            ])*/
        ]);
    }

    /**
     * @param $link
     * @return int|mixed|string
     */

    public function actionShowtovar($link){
        if(!empty(\Yii::$app->request->post('CommentForm'))){
            $model = new CommentForm();
            $model->load(\Yii::$app->request->post());
            $model->save();
        }

        $id = preg_replace('/\D+/', '', preg_replace('/[^g(.)]+\D+/', '', $link));

        $good = Good::find()->where(['`goods`.`ID`' => $id])->with('reviews')->one();

        if(!$good){
            return \Yii::$app->runAction('site/error');
        }

        if($good->link.'-g'.$good->ID != $link){
            $this->redirect(Url::to(['/tovar/'.$good->link.'-g'.$good->ID, 'language' => \Yii::$app->language]), 301);
        }

        $this->saveGoodInViewed($good);

        $this->getBreadcrumbsLinks($good);
        $this->getCategoryPhoneNumber($good);

        self::getLanguagesLinks($good);

        (new PriceRuleHelper())->recalc($good, true);

        return $this->render('_shop_item_card', [
            'good'  =>  $good
        ]);
    }

    /**
     * @param $good Good
     * @return bool
     */
    public function saveGoodInViewed($good){
        $session = \Yii::$app->session;
        $storedItems = [];

        if(!$session->isActive){
            $session->open();
        }

        if(isset($session['viewedGoods'])){
            $storedItems = $session['viewedGoods'];
        }

        foreach($storedItems as $key => $item){
            if($item == $good->ID){
                unset($storedItems[$key]);
            }
        }

        array_unshift($storedItems, $good->ID);

        $session['viewedGoods'] = $storedItems;

        return true;
    }

    /**
     * @param string $url
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionRenderpage($url){
        $category = Category::findByLink($url);

        if(empty($category)){
            throw new NotFoundHttpException("Страница не найдена!");
        }

        self::getLanguagesLinks($category);

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

        $this->getBreadcrumbsLinks($category);
        $this->getCategoryPhoneNumber($category);


        $pageParams = \Yii::$app->request->get();
        unset($pageParams['_pjax']);
        unset($pageParams['offset']);

        return $this->render($view, [
            'category'          =>  $category,
            'showText'          =>  true,
            'items'             =>  new ActiveDataProvider([
                'query'         =>  $category->getItems(),
                'pagination'    =>  [
                    'pageSize'          =>  empty($category->filters) ? '20' : '15',
                    'forcePageParam'    =>  false,
                    'pageSizeParam'     =>  false,
                    'params'            =>  $pageParams
                ]
            ])
        ]);
    }

    public function renderStaticPage($pageInfo){
        return $this->render($pageInfo->viewFile, empty($pageInfo->viewOptions) ? [] : $pageInfo->viewOptions);
    }

    /**
     * Собирает линки для хлебных крошек в зависимости от
     * переданного объекта категории или товара
     *
     * @param $object
     * @throws InvalidConfigException
     */

    public function getBreadcrumbsLinks($object){
        $class = get_parent_class($object);
        $this->getView()->params['breadcrumbs'] = [];
        $temp = [];
        switch($class){
            case 'common\models\Category':
                $category = $object;
                $temp = [
                    'label' =>  $object->name
                ];
                break;
            case 'common\models\Good':
                $category = Category::findOne($object->GroupID);
                $temp = [
                    [
                        'url'   =>  Url::to([$category->link, 'language' => \Yii::$app->language]),
                        'label' =>  $category->name
                    ],
                    [
                        'label' =>  $object->name
                    ]
                ];
                break;
            default:
                throw new InvalidParamException("Класс {$class} не предназначен для генерации хлебных крошек!");
                break;
        }

        if(strlen($category->Code) != 3){
            foreach($category->parents as $parent){
                $this->getView()->params['breadcrumbs'][] = [
                    'url'   =>  Url::to(['/'.$parent->link, 'language' => \Yii::$app->language]),
                    'label' =>  $parent->name
                ];
            }
        }

        $this->getView()->params['breadcrumbs'] = array_merge($this->getView()->params['breadcrumbs'], $temp);
    }

    public function getCategoryPhoneNumber($object = null){
        if($object !== null){
            $class = get_parent_class($object);

            try{
                switch($class::className()){
                    case 'common\models\Category':
                        $category = $object;
                        break;
                    case 'common\models\Good':
                        $category = Category::findOne($object->GroupID);
                        break;
                    case '':
                        \Yii::$app->params['categoryPhoneNumber'] = '(044) 578 20 16';
                        return;
                        break;
                    default:
                        throw new InvalidParamException("Класс {$class} не предназначен для генерации хлебных крошек!");
                        break;
                }

                \Yii::trace('place 1');

                if(strlen($category->Code) != 3){
                    \Yii::trace('place 2');

                    foreach($category->parents as $parent){
                        \Yii::trace('finded parent');

                        if(!empty($parent->phoneNumber)){
                            \Yii::trace($parent->phoneNumber);

                            \Yii::$app->params['categoryPhoneNumber'] = $parent->phoneNumber;
                            break;
                        }
                    }
                }else{
                    \Yii::$app->params['categoryPhoneNumber'] = $category->phoneNumber;
                }
            }catch (\Error $e){

            }
        }
    }

    /**
     * Собирает ссылки для перехода на другие языковые версии сайта
     * в зависимости от переданного объекта категории или товара
     *
     * @param $object
     * @throws InvalidConfigException
     */

    public static function getLanguagesLinks($object = null){
        $class = get_parent_class($object);
        Yii::$app->getView()->params['languageLinks'] = [];
        $temp = [];
        $get = \Yii::$app->request->get();
        unset($get['_pjax']);
        unset($get['url']);

        switch($class){
            case 'common\models\Category':
                foreach($object->translations as $translation){
                    if($translation->enabled){
                        $temp[substr($translation->language, 0, 2)] = $translation->link;//.($get ? '?'.urldecode(http_build_query($get)): ''); TODO: раскомментировать, если нужно будет сохранять гет-параметры
                    }
                }
                break;
            case 'common\models\Good':
                foreach($object->translations as $translation){
                    if($translation->enabled){
                        $temp[substr($translation->language, 0, 2)] = 'tovar/'.(empty($translation->link) ? $object->link : $translation->link).'-g'.$object->ID;//.($get ? '?'.urldecode(http_build_query($get)): ''); TODO: раскомментировать, если нужно будет сохранять гет-параметры
                    }
                }
                break;
            case '':
                $appLanguage = Yii::$app->language;
                foreach(Yii::$app->urlManager->languages as $code => $language){
                    $isWildcard = substr($language, -2) === '-*';
                    if($language === $appLanguage ||
                        // Also check for wildcard language
                        $isWildcard && substr($appLanguage, 0, 2) === substr($language, 0, 2)){
                        continue;   // Exclude the current language
                    }
                    if($isWildcard || is_int($code)){
                        $code = substr($language, 0, 2);
                    }

                    $temp[$code] = '/'.\Yii::$app->request->url;
                }
                break;
            default:
                throw new InvalidParamException("Класс {$class} не предназначен для генерации ссылок перехода на другие языковые версии сайта!");
                break;
        }

        Yii::$app->getView()->params['languageLinks'] = $temp;
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

        if((\Yii::$app->request->post("OrderForm") && $order->load(\Yii::$app->request->post())) || \Yii::$app->request->post("orderType") == 1){
            if($order->validate() || \Yii::$app->request->post("orderType") == 1){
                $order->create();

                \Yii::$app->email->orderEmail(History::findOne($order->createdOrder));

                return $this->render('order_success', [
                    'model' =>  $order
                ]);
            }
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
                    'retail'      =>  $good->retailPrice == $good->wholesalePrice ?
                        Formatter::getFormattedPrice($good->retailPrice) :
                        \Yii::t('shop', 'розн. {price}',
                            ['price' => Formatter::getFormattedPrice($good->retailPrice)]
                        ),
                    'wholesale'   =>  $good->retailPrice == $good->wholesalePrice ?
                        Formatter::getFormattedPrice($good->wholesalePrice) :
                        \Yii::t('shop', 'опт. {price}',
                            ['price' => Formatter::getFormattedPrice($good->wholesalePrice)]
                        ),
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
                'class'         =>  'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'transparent'   =>  true,
            ],
            'captchacallbackmodal' => [
                'class'         =>  'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'transparent'   =>  true,
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
        $model->load(\Yii::$app->request->post());

        if(\Yii::$app->request->isAjax && \Yii::$app->request->post("ajax") == 'loginForm'){
            \Yii::$app->response->format = 'json';

            return ActiveForm::validate($model);
        }elseif ($model->validate() && $model->login()){
            if(\Yii::$app->cart->itemsCount > 0){
                Cart::updateAll(['customerID'   =>  \Yii::$app->user->identity->ID], ['cartCode' => \Yii::$app->cart->cartCode]);
            }

            return $this->redirect(\Yii::$app->request->referrer);
        }else if(\Yii::$app->request->isAjax){
            return $this->renderAjax('login', [
                'model' =>  $model
            ]);
        }

        return $this->redirect('/#loginModal');
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
            'ru-RU' => '[аеиоуыэюяьъй]',
            'uk-UA' => '[аеиіоуєюяїьй]'
        );
        $pattern = $vowels[\Yii::$app->language];
        $pattern = '/'.$pattern.'+?'.$pattern.'$|'.$pattern.'$/';
        $return = $r1 = $r2 = '';
        $string = mb_strtolower($string, 'UTF-8');
        //$string = preg_replace('/\w/', '', $string); allow latin symbols
        $string = preg_replace('/[\[{(.,;\'*")}\]]/', ' ', $string);
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
            ->select("`goods`.*, (MATCH(`item_translations`.`name`) AGAINST('{$name}' IN BOOLEAN MODE)) AS `relevant`")
            ->leftJoin('category_translations', '`goods`.`GroupID` = `category_translations`.`ID`')
            ->joinWith(['translations'])
            ->where("MATCH(`item_translations`.`name`) AGAINST('{$name}' IN BOOLEAN MODE)")
            ->orWhere(['like', '`goods`.`Code`', $suggestion])
            ->orWhere(['like', '`goods`.`BarCode2`', $suggestion])
            ->orWhere(['like', '`category_translations`.`name`', $suggestion])
            ->andWhere('`goods`.`deleted` = 0 AND (`goods`.`PriceOut1` != 0 AND `goods`.`PriceOut2` != 0)')
            ->andWhere(['`item_translations`.`language`' => \Yii::$app->language])
            ->andWhere(['`category_translations`.`language`' => \Yii::$app->language])
            ->orderBy('IF (`goods`.`count` <= \'0\' AND `goods`.`isUnlimited` = \'0\', \'FIELD(`goods`.`count` DESC)\', \'FIELD()\'), `relevant` DESC'); //TODO: Пофиксить поиск, бо це глина

        if(\Yii::$app->request->isAjax && !\Yii::$app->request->get('page')){
            \Yii::$app->response->format = 'json';

            $goods = [];

            foreach($goodsQuery->limit(10)->each() as $good){
                $goodInfo = [
                    'ID'        =>  $good->ID,
                    'code'      =>  $good->Code,
                    'price'     =>  $good->wholesalePrice,
                    'price2'    =>  $good->retailPrice,
                    'link'      =>  $good->link,
                    'name'      =>  $good->name,
                    'photo'     =>  $good->photo
                ];

                if(!empty($good->category)){
                    $goodInfo['category'] = $good->category->name;
                }

                if(!empty($good->BarCode2)){
                    $goodInfo['vendorCode'] = $good->BarCode2;
                }

                $goods[] = $goodInfo;
            }

            return $goods;
        }

        $pageParams = \Yii::$app->request->get();
        unset($pageParams['_pjax']);
        unset($pageParams['offset']);

        return $this->render('searchResults', [
            'goods' =>  new ActiveDataProvider([
                'query' =>  $goodsQuery,
                'pagination'    =>  [
                    'pageSize'          =>  '20',
                    'forcePageParam'    =>  false,
                    'pageSizeParam'     =>  false,
                    'params'            =>  $pageParams
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

        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionSubscribe(){
        if(!\Yii::$app->request->isAjax){
            throw new BadRequestHttpException("Данный метод доступен только через ajax!");
        }

        $model = new SubscribeForm();

        \Yii::$app->response->format = 'json';

        if($model->load(\Yii::$app->request->post()) && $model->validate() && $model->subscribe()){
            return true;
        }

        return false;
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
        $model->load(Yii::$app->request->post());

        if(\Yii::$app->request->isAjax && \Yii::$app->request->post("ajax") == 'registrationForm'){
            \Yii::$app->response->format = 'json';

            return ActiveForm::validate($model, [
                'name', 'surname', 'email', 'password', 'phone'
            ]);
        }

        if ($user = $model->signup()) {
            if(Yii::$app->getUser()->login($user)) return $this->goHome();
        }

        return $this->redirect('/#registrationModal');
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

    public function saveReturnForm(){
        $model = new ReturnForm();

        $model->load(\Yii::$app->request->post());

        if(!$model->save()){
            return $model->getErrors();
        }

        return true;
    }

    public function savePaymentConfirmForm(){
        $model = new PaymentConfirmForm();

        $model->load(\Yii::$app->request->post());

        if(!$model->save()){
            return $model->getErrors();
        }

        return true;
    }


    public function saveCallbackForm(){
        $model = new CallbackForm();

        $model->load(\Yii::$app->request->post());

        if(!$model->save()){
            return $model->getErrors();
        }

        return true;
    }

    public function saveUsersInterestsForm(){
        $model = new UsersInterestsForm();

        $model->load(\Yii::$app->request->post());

        if(!$model->save()){
            return $model->getErrors();
        }

        return true;
    }

    public function saveReviewForm(){
        $model = new ReviewForm();

        $model->load(\Yii::$app->request->post());

        if(!$model->save()){
            return $model->getErrors();
        }

        return true;
    }

    public function beforeAction($action){
        $domainName = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : \Yii::$app->request->getServerName();
        $domainInfo = Domain::findOne(['name' => $domainName]);

        \Yii::$app->params['domainInfo'] = empty($domainInfo) ? \Yii::$app->params['domainInfo'] : $domainInfo;

        if(\Yii::$app->request->post("ReturnForm")){
            $this->saveReturnForm();
        }

        if(\Yii::$app->request->post("CallbackForm")){
            $this->saveCallbackForm();
        }

        if(\Yii::$app->request->post("PaymentConfirmForm")){
            $this->savePaymentConfirmForm();
        }

        if(\Yii::$app->request->post("UsersInterestsForm")){
            $this->saveUsersInterestsForm();
        }

        if(\Yii::$app->request->post("ReviewForm")){
            $this->saveReviewForm();
        }

        self::getLanguagesLinks();
        self::getCategoryPhoneNumber();

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

