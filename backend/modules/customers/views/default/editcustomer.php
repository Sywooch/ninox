<?php
echo $form->field($customer, 'Company');
echo $form->field($customer, 'City');
echo $form->field($customer, 'Address');
echo $form->field($customer, 'phone');
echo $form->field($customer, 'email');
/*echo $form->field($customer, 'Discount')->widget(\kartik\range\RangeInput::className(), [
    'options' => ['placeholder' => '% скидки'],
    'html5Options' => ['min' => 0, 'max' => 100],
    'addon' => ['append' => ['content' => '%']]
]);*/
echo $form->field($customer, 'deleted')->checkbox();
echo $form->field($customer, 'shippingType');
echo $form->field($customer, 'PaymentType');
?>
<center><button class="btn btn-success btn-lg" type="submit" style="margin-left: 10px;">Сохранить</button> или <a href="/customers/showcustomer/<?=$customer->ID?>" class="btn btn-info">в режим просмотра</a></center>