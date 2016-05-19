<?php
use yii\helpers\Html;

echo Html::tag('div', \Yii::t('shop', 'Мы сожалеем, но вы не можете оформить заказ, так как ваша корзина пуста!'), ['class' => 'content']);