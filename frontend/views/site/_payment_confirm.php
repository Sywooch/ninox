<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 12.04.16
 * Time: 11:59
 */

$css = <<<'CSS'

.form_content .cap{
    color: #acacac;
    font-size: 12px;
    display: block;
    padding-bottom: 20px;
}



.form_content .row{
    margin: 0px;
    padding-bottom: 25px;
}

.form_content{
    text-align: left;
}


.form_content input{
    height: 40px;
}

.order_number input{
    width: 100px;
}

.form_content .form-group{
    width: 50%;
    overflow: auto;
    float: left;
}

.form_content .form-group label{
    line-height: 40px;
    float: left;
}

.form_content .form-group input{
    width: 50%;
    margin-right: 20px;
    float: right;
}

.form_content #paymentconfirmform-paymenttype{
    width: 50%;
    height: 40px;
    float: right;
    margin-right: 20px;
}

CSS;

$this->registerCss($css);

$model = new \frontend\models\PaymentConfirmForm();

?>

<div class="form_content" id="payMessage">
    <?php    $form = \yii\bootstrap\ActiveForm::begin([
        'id'            =>  'payment-confirm-form'
    ]);
    ?>
    <div class="cap">
        1. Введите данные заказа
    </div>
        <?= $form->field($model, 'orderNumber')?>
        <?= $form->field($model, 'sum')?>
    <div class="cap">
        2. Как вы платили
    </div>
        <?= $form->field($model, 'paymentDate')?>
        <?php
        echo $form->field($model, 'paymentType')->dropDownList($model->paymentTypes);
        ?>
</div>
