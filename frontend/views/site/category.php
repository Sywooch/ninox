<?php

use bobroid\y2sp\ScrollPager;
use frontend\helpers\PriceRuleHelper;
use frontend\widgets\Breadcrumbs;
use yii\bootstrap\Html;
use yii\widgets\ListView;
    
$this->title = $category->Name;

$helper = new PriceRuleHelper();

$js = <<<'JS'
    $('body').on('change', '.filter-rows input[type=checkbox]', function(){
        updateFilter(this);
    });

JS;

$this->registerJS($js);

\yii\widgets\Pjax::begin([
    'id'            =>  'pjax-category',
    'linkSelector'  =>  '.sub-categories li > a, .breadcrumb li > a',
    'timeout'       =>  '5000'
]);

echo Html::tag('div',
    $this->render('_category/_category_filters', [
        'filters'   =>  $category->filters,
        'min'       =>  $category->minPrice,
        'max'       =>  $category->maxPrice,
        'from'      =>  \Yii::$app->request->get('minPrice') ? \Yii::$app->request->get('minPrice') : $category->minPrice,
        'to'        =>  \Yii::$app->request->get('maxPrice') ? \Yii::$app->request->get('maxPrice') : $category->maxPrice,
    ]).
    Html::tag('div',
        Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]).
        Html::tag('div',
            Html::tag('h1', $category->Name).
            Html::tag('span',
                \Yii::t('shop',
                    '{n, number} {n, plural, one{товар} few{товара} many{товаров} other{товар}} в категории',
                    ['n' => $goods->getTotalCount()]
                ),
                ['class' => 'category-items-count']),
            ['class' => 'category-label']
        ).
        Html::ul($category->subCategories, [
            'item'      =>  function($item){
                return Html::tag('li', Html::a($item->Name, '/'.$item->link));
            },
            'class'     =>  'sub-categories'
        ]).
        ListView::widget([
            'dataProvider'  =>  $goods,
            'itemView'      =>  function($model) use (&$helper){
                $helper->recalc($model, true);

                return $this->render('_shop_item', [
                    'model' =>  $model
                ]);
            },
            'layout'        =>
                Html::tag('div', '{items}', ['class' => 'items-grid clear-fix']).
                Html::tag('div', '{pager}', ['class' => 'pagination-wrapper']),
            'itemOptions'   =>  [
                'class'     =>  'hovered'
            ],
            'pager' =>  [
                'class'             =>  ScrollPager::className(),
                'container'         =>  '.list-view',
                'item'              =>  '.hovered',
                'paginationClass'   =>  'pagination',
                'paginationSelector'=>  'pagi',
            ]
        ]).
        Html::tag('div',
            htmlspecialchars_decode($category->text2).
            Html::tag('div',
                Html::tag('p',
                    $category->Name.
                    ' '.
                    \Yii::t('shop',
                        'с доставкой в Киев, Харьков, Одессу, Львов, Днепропетровск, Донецк, Винницу, Луганск,
                                Луцк, Житомир, Запорожье, Ивано-Франковск, Николаев, Полтаву, Ровно, Сумы, Тернополь,
                                Ужгород, Херсон, Хмельницкий, Черкассы, Чернигов, Черновцы.')
                ),
                ['class' => 'seo-city']),
            ['class' => 'category-description']),
        ['class' => 'content']),
    ['class' => $category->viewFile.' clear-fix']
);

\yii\widgets\Pjax::end();