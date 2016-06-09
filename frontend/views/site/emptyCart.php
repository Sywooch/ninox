<?php
use yii\helpers\Html;

$this->title = 'Корзина пуста';
$this->params['breadcrumbs'][] = $this->title;

echo Html::tag('div', \Yii::t('shop', 'Мы сожалеем, но вы не можете оформить заказ, так как Ваша корзина пуста!'), ['class' => 'content']);