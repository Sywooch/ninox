<?php

namespace frontend\modules\blog\controllers;

use common\models\BlogArticle;
use common\models\BlogCategory;
use yii\data\ActiveDataProvider;
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
}
