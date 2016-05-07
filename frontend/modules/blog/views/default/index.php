<?php

/*  @var $this yii\web\View
 *  @var $lastNews \yii\data\ActiveDataProvider
 *  @var $article \common\models\BlogArticle
 */

use yii\bootstrap\Html;

$i = 0;

$this->title = 'Блог - новости мира моды и рукоделия | Krasota-Style';

$bannerItems = [];

foreach($banners as $article){
    $bannerItems[] = Html::tag('div', Html::img(\Yii::$app->params['frontend'].'/img/blog/articles/'.$article->ico, ['style' => 'margin: 0 auto']).
        Html::a(Html::tag('h2', $article->title, ['style' => 'text-align: center;']), '/blog/'.$article->link));
}
?>
<div class="site-index">
    <?=Html::tag('div',
        Html::tag('div', Html::tag('h1', 'Krasota-Style'), ['class' => 'col-xs-4']).
        Html::tag('div', '', ['class' => 'col-xs-8']),
        [
            'class' =>  'row'
        ]
    )?>
    <?=Html::tag('div', \evgeniyrru\yii2slick\Slick::widget([
        'items' =>  $bannerItems
    ]))?>
    <?=\yii\widgets\ListView::widget([
        'dataProvider'  =>  $lastNews,
        'summary'       =>  false,
        'itemView'      =>  function($article, $key, $index){
            return Html::tag('div', Html::a($article->title, '/blog/'.$article->link), ['class' => 'col-xs-'.($index < 2 ? '12' : '4')]);
        },
        'layout'    =>  '{items}'.Html::a(\Yii::t('blog', 'Читать все статьи').' ->', '/blog/lastnews')
    ])?>
</div>
