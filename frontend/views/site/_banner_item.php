<?php
use yii\helpers\Html;

echo $model->type == 'html' ? $model->banner : Html::a(Html::img('//krasota-style.com.ua/'.$model->banner), $model->link);