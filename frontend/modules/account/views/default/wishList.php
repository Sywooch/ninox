<?php
use darkcs\infinitescroll\InfiniteScrollPager;
use frontend\helpers\PriceRuleHelper;
use yii\bootstrap\Html;
use yii\widgets\ListView;

$this->title = 'Список желаний';
$this->params['breadcrumbs'][] = $this->title;

$helper = new PriceRuleHelper();

$js = <<<'JS'
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

$this->registerJs($js);

echo Html::tag('div',
    $this->render('_account_menu').
    Html::tag('div',
        ListView::widget([
            'dataProvider'  =>  $items,
            'itemView'      =>  function($model) use (&$helper){
                $helper->recalc($model, ['except' => ['DocumentSum']]);

                return $this->render('../../../../views/site/_shop_item', [
                    'model' =>  $model
                ]);
            },
            'layout'        =>
                Html::tag('div', '{items}', ['class' => 'items-grid clear-fix cols-3']).
                Html::tag('div', '{pager}', ['class' => 'pagination-wrapper']),
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
        ]),
        [
            'class' =>  'user-data-content category'
        ]),
    [
        'class' =>  'content'
    ]);