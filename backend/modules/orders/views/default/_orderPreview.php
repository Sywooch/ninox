<?php use common\models\Siteuser;
use kartik\form\ActiveForm;
use kartik\switchinput\SwitchInput;
use yii\bootstrap\Html;
use yii\helpers\Url;

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
                        <?=Html::tag('div',
                            $form->field($model, 'deliveryType', [
                                'options'   =>  [
                                    'class' =>  'col-xs-4'
                                ],
                                'inputOptions'  =>  [
                                    'id'    =>  'deliveryTypeInput'
                                ]
                            ])
                                ->dropDownList(\yii\helpers\ArrayHelper::map(\Yii::$app->runAction('orders/default/get-deliveries', ['type' => 'deliveryType']), 'id', 'name')).
                            $form->field($model, 'deliveryParam',
                                [
                                    'options'   =>  [
                                        'class' =>  'col-xs-8'
                                    ]
                                ])
                                ->widget(\kartik\depdrop\DepDrop::className(), [
                                    //'type'      =>  \kartik\depdrop\DepDrop::TYPE_SELECT2,
                                    'pluginOptions' =>  [
                                        'depends'   =>  ['deliveryTypeInput'],
                                        'initialize'=>  true,
                                        'params'    =>  [
                                            'deliveryTypeInput'
                                        ],
                                        'emptyMsg'  =>  'варианты отсутствуют',
                                        'initDepends'=>  ['deliveryTypeInput'],
                                        'url'       =>  Url::to('/orders/get-deliveries')
                                    ]
                                ]).
                            $form->field($model, 'deliveryInfo',
                                [
                                    'options'   =>  [
                                        'class' =>  'col-xs-4'
                                    ]
                                ])
                                ->label('Склад #')
                            )?>
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
                        <?=Html::tag('div',
                            $form->field($model, 'paymentType', [
                                'options'   =>  [
                                    'class' =>  'col-xs-6'
                                ],
                                'inputOptions'  =>  [
                                    'id'    =>  'paymentTypeInput'
                                ]
                            ])->dropDownList(\yii\helpers\ArrayHelper::map(\Yii::$app->runAction('orders/default/get-payments', ['type' => 'paymentType']), 'id', 'name')).
                            $form->field($model, 'paymentParam',
                                [
                                    'options'   =>  [
                                        'class' =>  'col-xs-6'
                                    ]
                                ])
                                ->widget(\kartik\depdrop\DepDrop::className(), [
                                    //'type'      =>  \kartik\depdrop\DepDrop::TYPE_SELECT2,
                                    'pluginOptions' =>  [
                                        'depends'   =>  ['paymentTypeInput'],
                                        'initialize'=>  true,
                                        'params'    =>  [
                                            'paymentTypeInput'
                                        ],
                                        'emptyMsg'  =>  'варианты отсутствуют',
                                        'initDepends'=>  ['paymentTypeInput'],
                                        'url'       =>  Url::to('/orders/get-payments')
                                    ]
                                ]),
                            [
                                'class' =>  'row'
                            ])?>
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