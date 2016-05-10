<?php
/**
 * @var \common\models\BlogArticle $article
 */


use yii\bootstrap\Html;

$this->title = $article->title.' - блог сайта Krasota-Style';


echo Html::img(\Yii::$app->params['frontend'].'/img/blog/articles/'.$article->ico).
    Html::tag('h1', $article->title).
    $article->content;