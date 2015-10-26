<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 23.10.15
 * Time: 13:58
 */

use yii\helpers\Html;

$form = new \yii\widgets\ActiveForm();

echo $form->field($invoice, 'ServiceType', ['inputOptions' => []])->dropDownList(\Yii::$app->NovaPoshta->serviceTypes());

if(empty($invoice->Recipient) || $invoice->getErrors('Recipient')){
    echo $form->field($invoice, 'Recipient', ['inputOptions' => []]);
}

if(empty($invoice->CityRecipient) || $invoice->getErrors('CityRecipient')){
    echo $form->field($invoice, 'CityRecipient', ['inputOptions' => []]);
}else{
    echo $form->field($invoice->recipientDelivery, 'city', ['inputOptions' => ['disabled' => true]]);
}

if(empty($invoice->RecipientAddress) || $invoice->getErrors('RecipientAddress')){
    echo $form->field($invoice, 'RecipientAddress', ['inputOptions' => []]);
}else{
    echo $form->field($invoice->recipientDelivery, 'shippingParam', ['inputOptions' => ['disabled' => true]]);
}

if(empty($invoice->ContactRecipient) || $invoice->getErrors('ContactRecipient')){
    echo $form->field($invoice, 'ContactRecipient', ['inputOptions' => []]);
}else{
    echo $form->field($invoice->recipientData, 'Company', ['inputOptions' => ['disabled' => true]]);
}

echo $form->field($invoice, 'RecipientsPhone', ['inputOptions' => []]);

echo $form->field($invoice, 'PaymentMethod', ['inputOptions' => []])->dropDownList(\Yii::$app->NovaPoshta->paymentMethods());
echo $form->field($invoice, 'PayerType', ['inputOptions' => []])->dropDownList($invoice::$typesOfPayers);
echo $form->field($invoice, 'Cost', ['inputOptions' => []]);
echo $form->field($invoice, 'SeatsAmount', ['inputOptions' => []]);
echo $form->field($invoice, 'Description', ['inputOptions' => []]);
echo $form->field($invoice, 'CargoDescription', ['inputOptions' => []]);
echo '<button type="submit">Отправить</button>';


if(empty($invoice->getErrors())){
    echo Html::tag('div', 'Успех! Номер накладной: '.$invoice->orderData->nakladna, [
        'class' =>  'alert alert-success'
    ]);
}