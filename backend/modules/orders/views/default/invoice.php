<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 23.10.15
 * Time: 13:58
 */

use yii\helpers\Html;

echo Html::button('', [
    'class'                 =>  'remodal-close',
    'data-remodal-action'   =>  'close'
]);


$form = new \yii\widgets\ActiveForm([
    'id'    =>  'invoiceForm'
]);
$form->begin();

echo $form->field($invoice, 'ServiceType')->dropDownList(\Yii::$app->NovaPoshta->serviceTypes()),
    $form->field($invoice, 'PaymentMethod')->dropDownList(\Yii::$app->NovaPoshta->paymentMethods()),
    $form->field($invoice, 'PayerType')->dropDownList(\Yii::$app->NovaPoshta->typesOfPayers()),
    $form->field($invoice, 'Cost'),
    $form->field($invoice, 'SeatsAmount'),
    $form->field($invoice, 'Description')->dropDownList(\Yii::$app->NovaPoshta->cargoTypes()),
    $form->field($invoice, 'CargoDescription')->dropDownList(\Yii::$app->NovaPoshta->cargoDescriptionList()),
    Html::button('Создать накладную', ['id' => 'createInvoice', 'type' => 'submit']);

$form->end();