<?php

use bobroid\y2sp\ScrollPager;
use frontend\helpers\PriceRuleHelper;
use frontend\widgets\Breadcrumbs;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

$this->title = $category->metaTitle;

$totalCount = $items->getTotalCount();

$this->registerMetaTag(['name' => 'description', 'content' => $category->getMetaDescription($totalCount)], 'description');
$this->registerMetaTag(['name' => 'keywords', 'content' => $category->metaKeywords], 'keywords');

if(urldecode(Url::canonical()) != \Yii::$app->request->absoluteUrl){
    $this->registerLinkTag(['rel' => 'canonical', 'href' => urldecode(Url::canonical())]);
}

$pageSize = $items->pagination->getPageSize();
$page = empty(\Yii::$app->request->get('page')) ? 1 : \Yii::$app->request->get('page');
if($pageSize < 1){
    $pageCount = $totalCount > 0 ? 1 : 0;
}else{
    $totalCount = $totalCount < 0 ? 0 : (int)$totalCount;
    $pageCount = (int)(($totalCount + $pageSize - 1) / $pageSize);
}

if($page > 1){
    $this->registerLinkTag(['rel' => 'prev', 'href' => urldecode($items->pagination->createUrl($page - 2, null, true))]);
}
if($page < $pageCount){
    $this->registerLinkTag(['rel' => 'next', 'href' => urldecode($items->pagination->createUrl($page, null, true))]);
}

if(!$totalCount || in_array(\Yii::$app->request->get('order'), ['asc', 'desc', 'novivki'])){
    $this->registerMetaTag(['name' => 'robots', 'content' => 'noindex, follow'], 'robots');
}

if($page > 1){
    $this->registerMetaTag(['name' => 'yandex', 'content' => 'noindex, follow'], 'yandex');
}

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
    ($totalCount ?
        $this->render('_category/_category_filters', [
            'filters'   =>  $category->filters,
            'min'       =>  $category->minPrice,
            'max'       =>  $category->maxPrice,
            'from'      =>  \Yii::$app->request->get('minPrice') ? \Yii::$app->request->get('minPrice') : $category->minPrice,
            'to'        =>  \Yii::$app->request->get('maxPrice') ? \Yii::$app->request->get('maxPrice') : $category->maxPrice,
        ]) : ''
    ).
    Html::tag('div',
        Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]).
        Html::tag('div',
            Html::tag('h1', $category->metaName).
            Html::tag('span',
                \Yii::t('shop',
                    '{n, number} {n, plural, one{товар} few{товара} many{товаров} other{товар}} в категории',
                    ['n' => $totalCount]
                ),
                ['class' => 'category-items-count']),
            ['class' => 'category-label']
        ).
        Html::ul($category->subCategories, [
            'item'      =>  function($item){
                return Html::tag('li', Html::a($item->name, Url::to(['/'.$item->link, 'language' => \Yii::$app->language])));
            },
            'class'     =>  'sub-categories'
        ]).
        \kartik\select2\Select2::widget([
            'data'          => [
                'date'      =>  \Yii::t('shop', 'По рейтингу'),
                'asc'       =>  \Yii::t('shop', 'По возрастанию цены'),
                'desc'      =>  \Yii::t('shop', 'По убыванию цены'),
                'novinki'   =>  \Yii::t('shop', 'По новизне'),
            ],
            'name'          =>  'Sorting',
            'hideSearch'    =>  true,
            'value'         =>  \Yii::$app->request->get('order'),
            'language'      =>  \Yii::$app->language,
            'pluginOptions' =>   [
                'width' =>  '250px',
            ],
            'pluginEvents'  =>  [
                'change'    =>  'function(data){
                    window.history.replaceState({}, document.title, buildLinkFromParams(\'order\', data.target.value));
                    $.pjax.reload({container: \'#pjax-category\'});
                }'
            ],
        ]).
        ListView::widget([
            'dataProvider'  =>  $items,
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
                'triggerTemplate'   =>
                    Html::tag('div',
                        Html::tag('div', \Yii::t('shop', 'Ещё 15 товаров'),
                        [
                            'class' => 'load-more'
                        ]),
                    [
                        'class' =>  'ias-trigger'
                    ]),
                'spinnerTemplate'   =>
                    Html::tag('div',
                        Html::tag('div', '',
                            [
                                'class' => 'load-more icon-loader'
                            ]),
                        [
                            'class' =>  'ias-loader'
                        ]),
                'item'              =>  '.hovered',
                'noneLeftText'      =>  '',
                'paginationClass'   =>  'pagination',
                'paginationSelector'=>  'pagi',
                'triggerOffset'     =>  \Yii::$app->request->get('offset'),
                'eventOnPageChange' =>  new \Yii\web\JsExpression('
                    function(offset){
                        if(params[\'offset\']){
                            offset > params[\'offset\'][0] ? params[\'offset\'][0] = offset : \'\';
                        }else{
                            params[\'offset\'] = [];
					        params[\'offset\'].push(offset);
                        }
                        $(\'.list-view .pagination .active ~ li:not(.next)\').each(
                            function(){
                                if(offset > 1){
                                    $(this).addClass(\'active\');
                                    offset--;
                                }
                            }
                        )
                        window.history.replaceState({}, document.title, buildLinkFromParams(false, false));
                        return false;
                    }
                ')
            ]
        ]).
        Html::tag('div',
            ($page > 1 ? '' : htmlspecialchars_decode($category->description)).
            Html::tag('div',
                Html::tag('p',
                    $category->name.
                    ' '.
                    \Yii::t('shop',
                        'с доставкой в Киев, Харьков, Одессу, Львов, Днепропетровск, Донецк, Винницу, Луганск,
                                Луцк, Житомир, Запорожье, Ивано-Франковск, Николаев, Полтаву, Ровно, Сумы, Тернополь,
                                Ужгород, Херсон, Хмельницкий, Черкассы, Чернигов, Черновцы.')
                ),
                ['class' => 'seo-city']),
            ['class' => 'category-description']),
        ['class' => 'content']),
    ['class' => 'category clear-fix']
);

\yii\widgets\Pjax::end();