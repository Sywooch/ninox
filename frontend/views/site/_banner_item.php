<?php
use yii\helpers\Html;

echo $model->type == 'html' ? $model->banner->value : Html::a(Html::img('http://krasota-style.com.ua/'.$model->banner->value), $model->banner->link);

