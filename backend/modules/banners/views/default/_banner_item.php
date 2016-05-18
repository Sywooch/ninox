<?php
use yii\bootstrap\Html;

$formatter = \Yii::$app->formatter;

$dateFrom = ($model->dateFrom != '0000-00-00 00:00:00' ? $formatter->asDatetime($model->dateFrom, 'php:d.m.Y H:i') : '-');
$dateTo = ($model->dateTo != '0000-00-00 00:00:00' ? $formatter->asDatetime($model->dateTo, 'php:d.m.Y H:i') : '-');

$a = '<div class="row">
    <div class="col-xs-4">
        <img src="'.\Yii::$app->params['cdn-link'].'/'.($model->type == $model::TYPE_HTML ? 'template/img/banner_html.jpg' : $model->banner->value).'" class="img-thumbnail" style="max-height: 200px;">
    </div>
    <div class="col-xs-8">'.''.'
        <br>
        <br>
        <div class="btn-group">
            <button class="btn btn-default" disabled>Редактировать</button>
            <button class="btn btn-default deleteBanner">'.($model->deleted == '1' ? 'Восстановить' : 'Удалить').'</button>
            <button class="btn btn-default changeBannerState">'.($model->banner->state == '1' ? 'Выключить' : 'Включить').'</button>
        </div>
    </div>
</div>
<div class="clearfix"></div>';

//echo $a;
echo Html::tag('div', Html::tag('div', Html::img(\Yii::$app->params['cdn-link'].'/'.($model->type == $model::TYPE_IMAGE ? $model->banner->value : 'template/img/banner_html.jpg'), [
'class' =>  'img-thumbnail',
    'style' =>  'max-height: 200px;',
]), [
    'class' =>  'col-xs-4'
]).
Html::tag('div', (
$model->deleted ? Html::tag('h3', 'Удалён') : (
$model->banner->state == 1 ? Html::tag('h3', "Активен с {$dateFrom} по {$dateTo}") : Html::tag('h3', 'Неактивен')
)
), [
    'class' =>  'col-xs-8'
]). Html::tag('br').Html::tag('br'), ['class' => 'row']).
Html::tag('div', '', ['class' => 'clearfix']);