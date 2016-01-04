<?php
use yii\helpers\Html;

echo $model->type == 'html' ? $model->banner_ru : Html::a(Html::img('//krasota-style.com.ua/'.$model->banner_ru), $model->link_ru);