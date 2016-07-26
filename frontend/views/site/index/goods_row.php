<?php
use darkcs\infinitescroll\InfiniteScrollPager;
use frontend\helpers\PriceRuleHelper;
use yii\bootstrap\Html;

\rmrevin\yii\fontawesome\cdn\AssetBundle::register($this);

$helper = new PriceRuleHelper();

echo \yii\widgets\ListView::widget([
    'dataProvider'	=>	$dataProvider,
    'itemView'	    =>	function($model) use (&$helper){
        $helper->recalc($model, ['except' => ['DocumentSum']]);

        return $this->render('../_shop_item', [
            'model'     =>  $model,
            'btnClass'  =>  'mini-button',
            'innerSub'  =>  false
        ]);
    },
    'options'       =>  [
        'class' =>  'list-view listView-selector-'.
            (empty(\Yii::$app->request->get('type')) ? 'new' : \Yii::$app->request->get('type'))
    ],
    'itemOptions'	=>	[
        'class'	=>	'hovered'
    ],
    'layout'        =>  Html::tag('div', '{items}'.
            Html::tag('div',
                Html::tag('span', \Yii::t('shop', 'СМОТРЕТЬ ВСЕ ТОВАРЫ')),
                [
                    'class' => 'ias-trigger'
                ]
            ),
            [
                'class' => 'items-grid clear-fix'
            ]
        ).
        '{pager}',
    'pager'         =>  [
        'class'                 =>  InfiniteScrollPager::className(),
        'options'           =>  [
            'class' =>  'pagination hidden'
        ],
        'paginationSelector'    =>  '.pagination',
        'itemSelector'          =>  '.hovered',
        'autoStart'             =>  false,
        'containerSelector'     =>  '.items-grid',
        'nextSelector'          =>  '.pagination .next a:first',
        'alwaysHidePagination'  =>  true,
        'pluginOptions'         =>  [
            'loadingText'   =>  '',
        ],
    ],
    'summary'       =>  false
]);