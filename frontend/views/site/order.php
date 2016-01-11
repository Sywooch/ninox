<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 19.10.15
 * Time: 15:07
 */

$form = new \yii\bootstrap\ActiveForm();

if(!empty($model->getErrors())){
    echo '<pre>';
    print_r($model->getErrors());
    echo '</pre>';
}

$form->begin();
    echo $form->field($model, 'customerName'),
        $form->field($model, 'customerSurname'),
        $form->field($model, 'customerEmail'),
        $form->field($model, 'deliveryCity'),
        $form->field($model, 'deliveryRegion'),
        $form->field($model, 'deliveryType')->dropDownList(\common\models\DeliveryType::getDeliveryTypes()),
        $form->field($model, 'deliveryInfo'),
        $form->field($model, 'paymentType')->dropDownList(\common\models\PaymentType::getPaymentTypes());

echo \yii\helpers\Html::button('Оформить заказ', [
    'type'  =>  'submit'
]);

$form->end();