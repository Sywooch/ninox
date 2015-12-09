<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 19.10.15
 * Time: 15:07
 */

$form = new \yii\bootstrap\ActiveForm();

$form->begin();

    echo $form->field($model, 'customerName'),
        $form->field($model, 'customerSurname'),
        $form->field($model, 'customerEmail'),
        $form->field($model, 'customerPhone'),
        $form->field($model, 'deliveryCity'),
        $form->field($model, 'deliveryType')->dropDownList(\common\models\DeliveryTypes::getDeliveryTypes()),
        $form->field($model, 'deliveryInfo');

echo \yii\helpers\Html::button('Оформить заказ', [
    'type'  =>  'submit'
]);

$form->end();