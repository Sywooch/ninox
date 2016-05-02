<?php use common\models\Siteuser;
use kartik\form\ActiveForm;
use kartik\switchinput\SwitchInput;

$form = ActiveForm::begin([
    'id'            =>  'orderPreview-form-horizontal'.$model->id,
    'options'       =>  [
        'class' =>  'orderPreviewAJAXForm',
    ],
    'type'          =>  ActiveForm::TYPE_INLINE,
    'fieldConfig'   =>  [
        'template'  =>  '{input}'
    ],
    //'action'                =>  '/orders/order-preview',
    'validationUrl'         =>  '/orders/order-preview',
    'enableAjaxValidation'  =>  true,
]);
?>
<div class="row">
    <div class="col-xs-10">
        <table style="width: 100%; margin-bottom: 0; vertical-align: middle; line-height: 100%;" class="table table-condensed good-preview-table">
            <tbody>
                <tr>
                    <td style="width: 20%;">
                        Способ доставки:
                    </td>
                    <td style="width: 30%;">
                        <?=''//$form->field($model, 'deliveryType')->dropDownList(\common\models\DeliveryType::getDeliveryTypes())->label(false); TODO: переделать типы доставки?>
                    </td>
                    <td style="width: 20%;">
                        Менеджер:
                    </td>
                    <td style="width: 30%;">
                        <?=$form->field($model, 'responsibleUser')->dropDownList(Siteuser::getActiveUsers())->label(false)?>
                    </td>
                </tr>
                <tr>
                    <td>
                        ТТН:
                    </td>
                    <td>
                        <?=$form->field($model, 'nakladna')->label(false)?>
                    </td>
                    <td>
                        Способ оплаты:
                    </td>
                    <td>
                        <?=''//$form->field($model, 'paymentType')->dropDownList(\common\models\PaymentType::getPaymentTypes())->label(false); TODO: переделать способы оплаты?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Статус ТТН:
                    </td>
                    <td>

                    </td>
                    <td>
                        Сума к оплате:
                    </td>
                    <td>
                        <?=$form->field($model, 'actualAmount')->label(false)?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Статус СМС:
                    </td>
                    <td>

                    </td>
                    <td>
                        Оплата:
                    </td>
                    <td>
                        <?=$form->field($model, 'paymentConfirmed')->widget(SwitchInput::classname(), [
                            'type'  =>  SwitchInput::CHECKBOX,
                            'pluginOptions' => [
                                'onText' => 'Да',
                                'offText' => 'Нет',
                            ]
                        ]);?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Статус посылки:
                    </td>
                    <td>

                    </td>
                    <td>
                        Платёж Global Money:
                    </td>
                    <td>
                        <?=$form->field($model, 'globalMoneyPayment')->widget(SwitchInput::classname(), [
                            'type'  =>  SwitchInput::CHECKBOX,
                            'pluginOptions' => [
                                'onText' => 'Да',
                                'offText' => 'Нет',
                            ]
                        ]);?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-xs-1">
        <?=$form->field($model, 'id')->hiddenInput(['display' => 'none'])?>
        <button class="btn btn-default btn-lg">Сохранить</button>
    </div>
</div>
<?php
ActiveForm::end();
?>