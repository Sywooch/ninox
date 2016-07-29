<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 12/9/2015
 * Time: 2:06 PM
 */
use yii\bootstrap\Html;
use yii\widgets\ListView;

$this->title = 'Мои заказы';
$this->params['breadcrumbs'][] = $this->title;

$js = <<<'JS'
$("body").on('click', ".spoiler-title", function(){
    $(this).parent().toggleClass("showw").children(".spoiler-body").slideToggle("medium");
});
JS;

$this->registerJs($js);

echo Html::tag('div',
    $this->render('_account_menu').
    Html::tag('div',
        Html::tag('div',
            Html::tag('i', '', ['class' => 'icon icon-box']).' '.\Yii::t('shop', 'Мои заказы'),
            [
                'class' =>  'user-account box myriad'
            ]
        ).
        Html::tag('div',
            ListView::widget([
                'dataProvider'  =>  $ordersDataProvider,
                'summary'       =>  false,
                'itemView'      =>  '_order',
            ]),
            [
                'class' =>  'orders'
            ]
        ),
        [
            'class' =>  'user-data-content'
        ]
    ),
    [
        'class' =>  'content'
    ]
);