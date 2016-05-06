<?php

/**
 * @var \yii\db\ActiveQuery $posts
 * @var \common\models\BlogCategory $category
 */

use yii\bootstrap\Html;

$this->title = !empty($category->title) ? $category->title : $category->name.' - блог сайта Krasota-Style';

echo Html::tag('h1', $category->name).
    \yii\widgets\ListView::widget([
        'dataProvider'  =>  $posts,
        'summary'       =>  false,
        'itemView'  =>  function($article){
            return Html::tag('div',
                Html::tag('div',
                    Html::img(\Yii::$app->params['frontend'].'/img/blog/articles/'.$article->ico),
                    [
                        'class' =>  'col-xs-3'
                    ]
                ).
                Html::tag('div',
                    Html::a(Html::tag('h2', $article->title), '/blog/'.$article->link).
                    $article->preview,
                    [
                        'class' => 'col-xs-9'
                    ]),
                [
                    'class' =>  'col-xs-12'
                ]
            );
        }
]);