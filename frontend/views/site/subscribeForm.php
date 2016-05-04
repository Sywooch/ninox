<?php
use frontend\models\SubscribeForm;
use kartik\form\ActiveForm;
use yii\bootstrap\Html;

$model = new SubscribeForm();

echo Html::tag('span', \Yii::t('shop', 'Подпишитесь на рассылку, не пропустите скидки')),
    Html::beginTag('div', ['class' => 'subscribe-email input-style-main']);

    $form = ActiveForm::begin([
        'id'        =>  'subscribeForm',
    ]);

    echo $form->field($model, 'email')->label(false),
    Html::button(\Yii::t('shop', 'Подписаться'), [
        'type'  =>  'submit',
        'class' =>  'blue-button small-button ',
    ]);

    ActiveForm::end();
    echo Html::endTag('div');