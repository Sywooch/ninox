<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 12/9/2015
 * Time: 2:06 PM
 */
use yii\bootstrap\Html;
use yii\widgets\ListView;

$js = <<<'JS'
$("body").on('click', ".spoiler-title", function(){
    $(this).parent().toggleClass("showw").children(".spoiler-body").slideToggle("medium");
});
JS;

$this->registerJs($js);

?>
<div class="content">
    <?=Html::tag('div', \frontend\widgets\ListGroupMenu::widget([
        'items'    => [
            [
                'label' =>  'Личные данные',
                'href'  =>  '/account'
            ],
            [
                'label' =>  'Мои заказы',
                'href'  =>  '/account/orders'
            ],
            [
                'label' =>  'Моя скидка',
                'href'  =>  '/account/discount'
            ],
            [
                'label' =>  'Список желаний',
                'href'  =>  '/account/wish-list'
            ],
            [
                'label' =>  'Мои отзывы',
                'href'  =>  '/account/reviews'
            ],
            [
                'label' =>  'Возвраты',
                'href'  =>  '/account/123'
            ],
            [
                'label' =>  'Ярмарка мастеров',
                'href'  =>  '/account/mas'
            ],
        ]
    ]), ['class' => 'menu'])?>
    <div class="user-data-content">
        <div class="user-account box myriad">
            <i class="icon icon-box"></i> Мои заказы
        </div>
        <div class="orders">
            <?=ListView::widget([
                'dataProvider'  =>  $ordersDataProvider,
                'summary'       =>  false,
                'itemView'      =>  '_order',
            ])?>
        </div>
    </div>
</div>