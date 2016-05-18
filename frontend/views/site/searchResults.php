<?php
use bobroid\y2sp\ScrollPager;
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

$helper = new PriceRuleHelper();

echo Html::tag('div', Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]).
    Html::tag('h2', \Yii::t('shop', 'Результаты поиска по запросу "{request}":', [
        'request' => \Yii::$app->request->get("string")
    ])).
    ListView::widget([
        'dataProvider'  =>  $goods,
        'summary'       =>  Html::tag('div', \Yii::t('shop', 'Найдено {totalCount} товаров'), ['class' => 'summary']),
        'itemView'      =>  function($model, $param2, $param3, $widget) use (&$helper){
            $helper->recalc($model, true);

            return $this->render('_shop_item', [
                'model' =>  $model
            ]);
        },
        'layout' => '{summary}'.
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
                        ]
                    ),
                    [
                        'class' =>  'ias-trigger'
                    ]
                ),
            'spinnerTemplate'   =>
                Html::tag('div',
                    Html::tag('div', '',
                        [
                            'class' => 'load-more icon-loader'
                        ]
                    ),
                    [
                        'class' =>  'ias-loader'
                    ]
                ),
            'item'              =>  '.hovered',
            'noneLeftText'      =>  '',
            'paginationClass'   =>  'pagination',
            'paginationSelector'=>  'pagi',
            'triggerOffset'     =>  \Yii::$app->request->get('offset'),
            'eventOnPageChange' =>  new JsExpression('
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
]), [
    'class' =>  'category search'
]);