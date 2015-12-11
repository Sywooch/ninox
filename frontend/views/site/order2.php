<?php

$form = new \yii\widgets\ActiveForm();

$css = <<<'STYLE'

.order-head{
    width: 100%;
    font-family: OpenSans-Semibold;
    font-size: 32px;
    color: #40403e;
    padding-bottom: 25px;
    border-bottom: 4px solid #d3e8f9;

}

.order-logo{
    width: 50%;
    background-repeat: no-repeat;
    background-image: url(https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcSCZwIQ2i-Bz3NyE7NBDEVxzwTu5bpbr4YBaQ1gvaFmnHly-U0W);
}

.order-call-phone{
    float: right;
}

.ordering{
    width: 100%;
}

.order-body{
    width: 100%;
    padding-top: 25px;
}
STYLE;

$this->registerCss($css);
?>

<div class="content">
    <div class="order-head">
        <div class="order-logo">
        </div>
        <div class="order-call-phone">
            (044) 232 82 20
        </div>
        <div class="ordering">
            Оформление заказа
        </div>
    </div>
    <div class="order-body">
        <div class="contact-data">
            <?=$form->field($model, 'customerName'),
                $form->field($model, 'customerSurname'),
                $form->field($model, 'customerEmail'),
                $form->field($model, 'customerPhone', [
                    'template'  =>  ' <div class="row"><div class="col-xs-1">{label}</div><div class="col-sm-4">{input}{error}{hint}</div></div>'
                ]),
                $form->field($model, 'deliveryCity'),
                $form->field($model, 'deliveryType')->dropDownList(\common\models\DeliveryTypes::getDeliveryTypes()),
                $form->field($model, 'deliveryInfo')?>
        </div>
        <?php
echo \yii\helpers\Html::button('Оформить заказ', [
    'type'  =>  'submit'
]);
?>