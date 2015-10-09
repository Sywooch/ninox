<?php use common\models\Siteuser;
use kartik\form\ActiveForm;
use bobroid\switchinput\SwitchInput;

$form = ActiveForm::begin([
    'id' => 'login-form-horizontal',
    'type' => ActiveForm::TYPE_INLINE,
    'fieldConfig'   =>  [
        'template'  =>  '{input}'
    ]
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
                        <?=$form->field($model, 'deliveryType')->dropDownList(\common\models\DeliveryTypes::getDeliveryTypes())->label(false)?>
                    </td>
                    <td style="width: 20%;">
                        Менеджер:
                    </td>
                    <td style="width: 30%;">
                        <?=$form->field($model, 'responsibleUserID')->dropDownList(Siteuser::getActiveUsers())->label(false)?>
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
                        <?=$form->field($model, 'paymentType')->dropDownList(\common\models\PaymentTypes::getPaymentTypes())->label(false)?>
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
                        <?=$form->field($model, 'moneyConfirmed')->widget(SwitchInput::classname(), [
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
                        <?=$form->field($model, 'globalmoney')->widget(SwitchInput::classname(), [
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
        <button class="btn btn-default btn-lg">Сохранить</button>
    </div>
</div>
<?php
ActiveForm::end();
?>