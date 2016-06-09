<?php
use yii\helpers\Html;

echo $model->type == 'html' ? $model->banner->value : Html::a(Html::img('//'.\Yii::$app->params['frontend'].'/'.$model->banner->value), $model->banner->link);

