<?php
namespace frontend\controllers;

use app\models\Pagetype;
use common\models\Banner;
use common\models\Cart;
use frontend\models\Category;
use frontend\models\Good;
use common\models\Question;
use common\models\Review;
use Yii;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

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

        $good = Good::findOne(['ID' => $id]);

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
        $cartItems = Cart::find([
            'cartCode'  =>  isset(\Yii::$app->request->cookies['cartCode']) ? \Yii::$app->request->cookies['cartCode'] : 0
        ]);
        if(sizeof($cartItems) >= 1){

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
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
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
            'captcha' => [
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
}
