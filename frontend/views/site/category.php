<?php

use darkcs\infinitescroll\InfiniteScrollPager;
use frontend\helpers\PriceRuleHelper;
use frontend\widgets\Breadcrumbs;
use yii\helpers\Html;
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

/*if($page > 1){
    $this->registerLinkTag(['rel' => 'prev', 'href' => urldecode($items->pagination->createUrl($page - 2, null, true))]);
}
if($page < $pageCount){
    $this->registerLinkTag(['rel' => 'next', 'href' => urldecode($items->pagination->createUrl($page, null, true))]);
}*/

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

    $('body').on('#pjax-category pjax:complete', function(){
        params = getFilterParams();
    });

    $('body').on(hasTouch ? 'touchend' : 'click', '.load-more', function(e){
        if(hasTouch && isTouchMoved(e)){ return false; }
        e.preventDefault();
        $('.load-more').addClass('icon-loader').attr('disabled');
        $('.grid-view').infinitescroll('start').scroll();
    });

    $('body').on('.items-grid infinitescroll:afterRetrieve', function(){
        $('.grid-view').infinitescroll('stop');
        if(params['offset']){
            params['offset'][0]++;
        }else{
            params['offset'] = [];
            params['offset'].push(2);
        }
        var offset = params['offset'][0];
        var add = false;
        $($('.list-view .pagination li:not(.next):not(.prev)').get().reverse()).each(
            function(){
                if(offset > 1 && add){
                    $(this).addClass('active');
                    offset--;
                }else if($(this).hasClass('active')){
                    add = true;
                }
            }
        )
        window.history.replaceState({}, document.title, buildLinkFromParams(false, false));
    });

    if(params['offset']){
        var offset = params['offset'][0];
        var add = false;
        $($('.list-view .pagination li:not(.next):not(.prev)').get().reverse()).each(
            function(){
                if(offset > 1 && add){
                    $(this).addClass('active');
                    offset--;
                }else if($(this).hasClass('active')){
                    add = true;
                }
            }
        )
    }

JS;

$this->registerJS($js);

$quickViewModal = new \bobroid\remodal\Remodal([
    'cancelButton'		=>	false,
    'confirmButton'		=>	false,
    'closeButton'		=>	true,
    'addRandomToID'		=>	false,
    'id'				=>	'quickView',
    'content'           =>  Html::tag('div', '', ['class' => 'item-navigation icon-circle-left'])
        .Html::tag('div', '', ['class' => 'item item-main clear-fix'])
        .Html::tag('div', '', ['class' => 'item-navigation icon-circle-right']),
    'options'			=>  [
        'id'        =>  'modal-quick-view',
        'class'     =>  'quick-view-modal',
        'hashTracking'  =>  false
    ],
]);

$pjax = \yii\widgets\Pjax::begin([
    'id'            =>  'pjax-category',
    'linkSelector'  =>  '.sub-categories li > a, .breadcrumb li > a',
    'timeout'       =>  '5000'
]);

echo Html::tag('div',
    (!empty($category->filters) ? ($totalCount ?
        $this->render('_category/_category_filters', [
            'category'   =>  $category,
            'filters'   =>  $category->filters,
            'min'       =>  $category->minPrice,
            'max'       =>  $category->maxPrice,
            'from'      =>  \Yii::$app->request->get('minPrice') ? \Yii::$app->request->get('minPrice') : $category->minPrice,
            'to'        =>  \Yii::$app->request->get('maxPrice') ? \Yii::$app->request->get('maxPrice') : $category->maxPrice,
        ]) : ''
    ) : '').
    Html::tag('div',
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
        ($category->Code == 'AABAAD' ?
            Html::tag('div',
                Html::tag('div', '%',
                    [
                        'class'     =>      'banner-percent'
                    ]
                ).
                Html::tag('span', 'Акция! Скидка -20% на весь раздел Детская бижутерия при покупке товара на сумму от 1500 грн.',
                    [
                        'class'     =>      'banner-discount-property'
                    ]
                ).
                Html::tag('span', 'Только 4 дня (до 12.06). Дешевле уже не будет!',
                    [
                        'class'     =>      'banner-discount-time'
                    ]
                ),
                [
                    'class'     =>      'banner-discount'
                ]
            ) : ''
        ).
        Html::ul($category->subCategories,
            [
                'item'      =>  function($item) use (&$helper){
                    $helper->recalc($item, ['except' => ['DocumentSum']]);
                    return Html::tag('li',
                        Html::a($item->name.
                            ($item->discountType > 0 && $item->discountSize > 0 ?
                                Html::tag('div', '-'.$item->discountSize.'%', ['class' => 'category-action-label'])
                                : ''
                            ),
                            Url::to(['/'.$item->link])
                        ),
                        ($item->discountType > 0 && $item->discountSize > 0 ?
                            ['class' => 'category-action'] : []
                        )
                    );
                },
                'class'     =>  'sub-categories'
            ]
        ).
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
                $helper->recalc($model, ['except' => ['DocumentSum']]);

                return $this->render('_shop_item', [
                    'model' =>  $model
                ]);
            },
            'layout'        =>
                Html::tag('div', '{items}', ['class' => 'items-grid clear-fix']).
                Html::tag('div', ($items->pagination->params['page'] < $pageCount ?
                    Html::tag('div',
                        Html::tag('span',
                            \Yii::t('shop', 'Ещё {n} товаров', ['n' => empty($category->filters) ? 20 : 15])
                        ),
                        [
                            'class' => 'load-more'
                        ]
                    ) : ''
                ).'{pager}', ['class' => 'pagination-wrapper']),
            'itemOptions'   =>  [
                'class'     =>  'hovered'
            ],
            'pager' =>  [
                'class' => InfiniteScrollPager::className(),
                'paginationSelector'    =>  '.pagination-wrapper',
                'itemSelector'          =>  '.hovered',
                'pjaxContainer'         =>  $pjax->id,
                'autoStart'             =>  false,
                'containerSelector'     =>  '.items-grid',
                'nextSelector'          =>  '.pagination .next a:first',
                'alwaysHidePagination'  =>  false,
                'pluginOptions'         =>  [
                    'loadingText'   =>  '',
                ],
                'registerLinkTags'      =>  true,
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
    ['class' => 'category clear-fix cols-'.(empty($category->filters) ? 4 : 3)]
);

\yii\widgets\Pjax::end();

echo $quickViewModal->renderModal();