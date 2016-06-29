<?php

use common\models\Siteuser;
use kartik\form\ActiveForm;
use rmrevin\yii\fontawesome\FA;
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
            <td><?=$form->field($model, 'nakladna').
                Html::button(FA::i('envelope-o'), ['class' => 'btn btn-sm btn-default sms-order'.((empty($model->nakladnaSendDate) || $model->nakladnaSendDate == '0000-00-00 00:00:00') ? '' : ' success')]).
                (empty($model->nakladnaSendDate) || $model->nakladnaSendDate == '0000-00-00 00:00:00' ? '' :
                    Html::tag('div',
                        Html::tag('div',
                            Html::tag('div', 'Отправлено:').
                            Html::tag('div', date('d.m.Y - H:i', strtotime($model->nakladnaSendDate)))
                        ),
                        ['class' => 'form-group ttn-send-date']
                    )
                )
                ?>
            </td>
        </tr>
        <tr>
            <td><?=$form->field($model, 'moneyConfirmed')->dropDownList(['0' => 'Не оплачено', '1' => 'Оплачено']).
                ($model->moneyConfirmed ? Html::tag('div',
                    Html::tag('div',
                        Html::tag('div', $model->moneyCollector->name.':').
                        Html::tag('div', date('d.m.Y - H:i', strtotime($model->moneyConfirmedDate)))
                    ),
                    ['class' => 'form-group money-collector']
                ) : '')
                ?>
            </td>
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