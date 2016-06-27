<?php

use common\models\Siteuser;
use kartik\form\ActiveForm;
use yii\bootstrap\Html;

$form = ActiveForm::begin([
    'id'            =>  'orderPreview-form-horizontal'.$model->id,
    'options'       =>  [
        'class' =>  'orderPreviewAJAXForm',
    ],
    'type'          =>  ActiveForm::TYPE_INLINE,
    'fieldConfig'   =>  [
        'template'      =>  '{label}{input}',
        'showLabels'    =>  true,
    ],
    'validationUrl'         =>  '/orders/order-preview',
    'enableAjaxValidation'  =>  true,
]);
?>
<table class="table table-condensed good-preview-table">
    <tbody>
        <tr>
            <td><?=$form->field($model, 'paymentType', [
                    'inputOptions'  =>  [
                        'id'    =>  'paymentTypeInput-'.$model->id
                    ]
                ])->dropDownList(\yii\helpers\ArrayHelper::map(\Yii::$app->runAction('orders/default/get-payments', ['type' => 'paymentType']), 'id', 'name')).
                $form->field($model, 'paymentParam')->dropDownList([])->label(false)?>
            </td>
            <td><?=$form->field($model, 'responsibleUser')->dropDownList(Siteuser::getActiveUsers()).
                $form->field($model, 'actualAmount', ['options' => ['class' => 'form-group pull-right']])?>
            </td>
            <td rowspan="3"><?=$form->field($model, 'id')->hiddenInput(['id' => 'orderID-'.$model->id])->label(false)?>
                <input type="submit" class="btn btn-default btn-lg btn-save" value="Сохранить">
                <input type="button" class="btn btn-default btn-lg btn-cancel" value="Отмена">
            </td>
        </tr>
        <tr>
            <td><?=$form->field($model, 'deliveryType', [
                    'inputOptions'  =>  [
                        'id'    =>  'deliveryTypeInput-'.$model->id
                    ]
                ])->dropDownList(\yii\helpers\ArrayHelper::map(\Yii::$app->runAction('orders/default/get-deliveries', ['type' => 'deliveryType']), 'id', 'name')).
                $form->field($model, 'deliveryParam')->dropDownList([])->label(false)?>
            </td>
            <td><?=$form->field($model, 'nakladna')?></td>
        </tr>
        <tr>
            <td><?=$form->field($model, 'moneyConfirmed')->dropDownList(['0' => 'Не оплачено', '1' => 'Оплачено'])?></td>
            <td><?=Html::button('Сообщить об оплате',
                    [
                        'class'                 =>  'btn btn-default btn-lg btn-inform-payment',
                        'data-remodal-target'   =>  'payment-confirm-form',
                        'data-number'           =>  $model->number
                    ]
                )?>
            </td>
        </tr>
    </tbody>
</table>
<?php
ActiveForm::end();
?>