<?php
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

$this->title = 'Баннеры';

$this->params['breadcrumbs'][] = $this->title;

$css = <<<'CSS'
.banners{
    text-align: center;
    vertical-align: middle;
    font-size: 20px;
    color: #444 !important;
    width: 1200px;
    background: #f3f3f3;
    margin-left: -30px;
}

.banners .menu{
    width: 100%;
    height: 45px;
    line-height: 45px;
    background: #ddd;
}

.banners .slider{
    height: 370px;
    width: 100%;
    display: block;
    background: #fff;
}

.banners .blocks{
    height: 100%;
    display: block;
    width: 1145px;
    margin: 0 auto;
    padding-top: 20px;
    padding-bottom: 20px;
}

.banners .blocks .block{
    display: block;
    float: left;
    margin-right: 30px;
    border-left: 1px solid rgb(236, 236, 236);
    box-shadow: 0px 1px 0px rgb(236, 236, 236);
    border-bottom: 1px solid rgb(222, 222, 222);
    border-right: 1px solid rgb(236, 236, 236);
    background: white none repeat scroll 0% 0%;
    border-radius: 4px;
}

.banners .blocks .column{
    width: 205px;
    float: left;
    margin-right: 30px;
}

.banners .blocks .block.s2x2{
    width: 440px;
    height: 440px;
    display: block;
}

.banners .blocks .block.s1x1{
    height: 205px;
    width: 205px;
}

.banners .blocks .block.s1x2{
    height: 440px;
    width: 205px;
}

.banners .blocks .column .block{
    margin-top: 30px;
    float: left;
}

.banners .blocks .column .block:first-child{
    margin-top: 0;
}

.banners .blocks *:last-child, .banners .blocks .last{
    margin-right: 0;
}

.banners > * > span{
    margin: auto;
    position: relative;
    padding: auto;
}
CSS;

$this->registerCss($css);

?>
<h1>Баннеры</h1>
<div class="btn-group">
    <?=''//\backend\widgets\AddBannerGroupWidget::widget([])?>
    <?=''//\backend\widgets\AddBannerWidget::widget([])?>

    <a href="/banners/stats" class="btn btn-default"><i class="glyphicon glyphicon-stats"></i>&nbsp;Статистика баннеров</a>
</div>
    <br><br>

<?=Html::tag('div',
    Html::tag('div', Html::tag('span', 'Меню'), ['class' => 'menu']).
    Html::a(Html::tag('span', 'Слайдер'), Url::to(['showbanners/slider_v3']), ['class' => 'slider']).
    Html::tag('div', Html::a(Html::tag('span', 'Блок 2х2'), Url::to(['showbanners/2x2']), ['class' => 'block s2x2']).
        Html::tag('div', Html::a(Html::tag('span', 'Блок 1х1'), Url::to(['showbanners/1x1.1']), ['class' => 'block s1x1']).
            Html::a(Html::tag('span', 'Блок 1х1'), Url::to(['showbanners/1x1.2']), ['class' => 'block s1x1']), ['class' => 'column']).
        Html::tag('div', Html::a(Html::tag('span', 'Блок 1х1'), Url::to(['showbanners/1x1.3']), ['class' => 'block s1x1']).
            Html::a(Html::tag('span', 'Блок 1х1'), Url::to(['showbanners/1x1.4']), ['class' => 'block s1x1']), ['class' => 'column']).
        Html::a(Html::tag('span', 'Блок 1х2'), Url::to(['showbanners/1x2']), ['class' => 'block s1x2 last']).
        Html::tag('div', '', ['class' => 'clearfix']),
        [
        'class' =>  'blocks'
    ]),
    [
        'class' =>  'banners'
    ]
)?>
<div class="clearfix" style="height: 30px;"></div>
