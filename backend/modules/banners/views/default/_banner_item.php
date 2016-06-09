<?php
use yii\bootstrap\Html;

$formatter = \Yii::$app->formatter;

$dateFrom = ($model->dateFrom != '0000-00-00 00:00:00' ? $formatter->asDatetime($model->dateFrom, 'php:d.m.Y H:i') : '-');
$dateTo = ($model->dateTo != '0000-00-00 00:00:00' ? $formatter->asDatetime($model->dateTo, 'php:d.m.Y H:i') : '-');

echo Html::tag('div', Html::tag('div', Html::img(\Yii::$app->params['frontend'].'/'.($model->type == $model::TYPE_IMAGE ? $model->banner->value : 'template/img/banner_html.jpg'), [
'class' =>  'img-thumbnail',
    'style' =>  'max-height: 200px;',
]), [
    'class' =>  'col-xs-4'
]).
Html::tag('div',
    Html::tag('h3', $model->deleted ? 'Удалён' : ($model->active == 1 ? "Активен с {$dateFrom} по {$dateTo}" : 'Неактивен')), [
    'class' =>  'col-xs-8'
]). Html::tag('br').Html::tag('br'), ['class' => 'row']).
Html::tag('div', '', ['class' => 'clearfix']);