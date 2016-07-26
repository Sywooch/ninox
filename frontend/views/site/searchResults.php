<?php
use bobroid\y2sp\ScrollPager;
use darkcs\infinitescroll\InfiniteScrollPager;
use frontend\helpers\PriceRuleHelper;
use frontend\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ListView;

$this->title = \Yii::t('shop', 'Результаты поиска по запросу "{request}"', [
    'request'   =>  \Yii::$app->request->get("string")
]);

$this->params['breadcrumbs'][] = [
    'label' =>  \Yii::t('shop', 'Результаты поиска')
];

$this->registerMetaTag(['name' => 'robots', 'content' => 'noindex, follow'], 'robots');

$totalCount = $items->getTotalCount();
$pageSize = $items->pagination->getPageSize();
$page = empty(\Yii::$app->request->get('page')) ? 1 : \Yii::$app->request->get('page');
if($pageSize < 1){
    $pageCount = $totalCount > 0 ? 1 : 0;
}else{
    $totalCount = $totalCount < 0 ? 0 : (int)$totalCount;
    $pageCount = (int)(($totalCount + $pageSize - 1) / $pageSize);
}

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

echo Html::tag('div', Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]).
    Html::tag('h2', \Yii::t('shop', 'Результаты поиска по запросу "{request}":', [
        'request' => \Yii::$app->request->get("string")
    ])).
    ListView::widget([
        'dataProvider'  =>  $items,
        'summary'       =>  Html::tag('div', \Yii::t('shop', 'Найдено {totalCount} товаров'), ['class' => 'summary']),
        'itemView'      =>  function($model) use (&$helper){
            $helper->recalc($model, ['except' => ['DocumentSum']]);

            return $this->render('_shop_item', [
                'model' =>  $model
            ]);
        },
        'layout' => '{summary}'.
            Html::tag('div', '{items}', ['class' => 'items-grid clear-fix']).
            Html::tag('div', ($items->pagination->params['page'] < $pageCount ?
                    Html::tag('div',
                        Html::tag('span',
                            \Yii::t('shop', 'Ещё {n} товаров', ['n' => 20])
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
            'autoStart'             =>  false,
            'containerSelector'     =>  '.items-grid',
            'nextSelector'          =>  '.pagination .next a:first',
            'alwaysHidePagination'  =>  false,
            'pluginOptions'         =>  [
                'loadingText'   =>  '',
            ],
        ]
]), ['class' => 'category clear-fix cols-4']).
    $quickViewModal->renderModal();