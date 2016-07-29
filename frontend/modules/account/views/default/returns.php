<?php
use yii\bootstrap\Html;


echo Html::tag('div',
    $this->render('_account_menu'),
    [
        'class' =>  'content'
    ]);