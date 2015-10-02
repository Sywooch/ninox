<?php
echo $form->field($customer, 'Company');
echo $form->field($customer, 'City');
echo $form->field($customer, 'Address');
echo $form->field($customer, 'Phone');
echo $form->field($customer, 'eMail');
echo $form->field($customer, 'Discount')->widget(\kartik\range\RangeInput::className(), [
    'options' => ['placeholder' => '% скидки'],
    'html5Options' => ['min' => 0, 'max' => 100],
    'addon' => ['append' => ['content' => '%']]
]);
echo $form->field($customer, 'Deleted')->checkbox();
echo $form->field($customer, 'ShippingType');
echo $form->field($customer, 'PaymentType');
?>
<center><button class="btn btn-success btn-lg" type="submit" style="margin-left: 10px;">Сохранить</button> или <a href="/admin/customers/showcustomer/<?=$customer->ID?>" class="btn btn-info">в режим просмотра</a></center>