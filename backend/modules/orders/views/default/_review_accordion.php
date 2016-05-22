<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 22.05.16
 * Time: 15:36
 */
use yii\helpers\Html;
use yii\helpers\Url;

$form = \kartik\form\ActiveForm::begin([]);

echo $form->field($model, 'customerComment', ['inputOptions' => ['style' => 'border: 1px solid #eceef2; height: 114px; resize: none'],'options' => ['class' => 'col-xs-10']])->textarea()->label(false),
    Html::tag('div',
        Html::button('Сохранить', [
            'class' =>  'btn btn-default',
            'type'  =>  'submit'
        ]),
        [
            'class' =>  'col-xs-2'
        ]);

$form->end();
