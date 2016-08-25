<?php

namespace frontend\modules\blog\controllers;

use common\models\BlogArticle;
use common\models\BlogCategory;
use frontend\modules\blog\models\Redirect;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `blog` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'lastNews'  =>  new ActiveDataProvider([
                'query' =>  BlogArticle::find()->orderBy('date DESC'),
                'pagination'    =>  [
                    'pageSize'  =>  '5'
                ]
            ]),
            'banners'   =>  BlogArticle::find()->orderBy('date DESC')->offset(5)->limit(5)->all()
        ]);
    }

    public function actionLastnews(){
        $this->layout = 'articleLayout';

        return $this->render('category', [
            'category'  =>  new BlogCategory([
                'name'  =>  'Последние новости'
            ]),
            'posts'     =>  new ActiveDataProvider([
                'query' =>  BlogArticle::find()->orderBy('date DESC')
            ])
        ]);
    }

    public function actionRoute($url){
        $category = BlogCategory::findByLink($url);

        if(!$category){
            $article = BlogArticle::findByLink($url);

            if(!$article){
                throw new NotFoundHttpException("Статья по ссылке {$url} не найдена!");
            }

            return $this->renderPost($article);
        }

        return $this->renderCategory($category);
    }

    /**
     * @param $category BlogCategory
     * @return string
     * @throws NotFoundHttpException
     */
    public function renderCategory($category){
        if(!$category){
            throw new NotFoundHttpException("Категория не найдена!");
        }

        $this->layout = 'articleLayout';

        return $this->render('category', [
            'category'  =>  $category,
            'posts'     =>  new ActiveDataProvider([
                'query' =>  $category->getPosts()
            ])
        ]);
    }

    /**
     * @param BlogArticle $article
     * @return string
     * @throws NotFoundHttpException
     */
    public function renderPost($article){
        if(!$article){
            throw new NotFoundHttpException("Статья не найдена!");
        }

        $this->layout = 'articleLayout';

        return $this->render('article', [
            'article'   =>  $article
        ]);
    }

    public function beforeAction($action){
        $url = Yii::$app->request->getPathInfo();
        if(!empty($url) && $url != rtrim($url, '/')){
            $params =  Yii::$app->request->get();
            unset($params['url']);
            Yii::$app->response->redirect(Url::to(array_merge(['/'.rtrim($url, '/')], $params)), 301);
            Yii::$app->end();
        }

        $arrayParams = Yii::$app->request->get();

        if(!empty($arrayParams['url'])){
            $url = '';
            $redirect = Redirect::findOne(['source' => urlencode(preg_replace('/.html|.php|\d{4}\/\d{2}\/\d{2}\//', '', $arrayParams['url']))]);
            if(!empty($redirect)){
                $controller = Yii::$app->controller;
                unset($arrayParams['page']);
                unset($arrayParams['url']);
                $params = array_merge(["{$controller->module->id}/{$redirect->target}"], $arrayParams);
                $url = urldecode(Yii::$app->urlManager->createUrl($params));
                Yii::$app->response->redirect($url, 301);
                Yii::$app->end();
            }elseif(preg_match('/.html|.php|\d{4}\/\d{2}\/\d{2}\/|\/\d+(\n|$)/', $arrayParams['url'])){
                $controller = Yii::$app->controller;
                $url = preg_replace('/.html|.php|\d{4}\/\d{2}\/\d{2}\/|\/\d+(\n|$)/', '', $arrayParams['url']);
                unset($arrayParams['page']);
                unset($arrayParams['url']);
                $params = array_merge(["{$controller->module->id}/{$url}"], $arrayParams);
                $url = urldecode(Yii::$app->urlManager->createUrl($params));
                Yii::$app->response->redirect($url, 301);
                Yii::$app->end();
            }elseif(preg_match('/id-\d+/', $arrayParams['url'], $catID)){
                $catID = preg_replace('/\D+/', '', $catID[0]);
                $cat = \common\models\Category::findOne(['ID' => $catID]);
                if(!empty($cat)){
                    unset($arrayParams['page']);
                    unset($arrayParams['url']);
                    $params = array_merge(["{$cat->link}"], $arrayParams);
                    $url = urldecode(Yii::$app->urlManager->createUrl($params));
                    Yii::$app->response->redirect($url, 301);
                    Yii::$app->end();
                }
            }
        }

        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }
}
