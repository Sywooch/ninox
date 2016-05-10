<?php
use kartik\select2\Select2;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;

$adminFields = '';

$form = \kartik\form\ActiveForm::begin([
    'options' =>  [
        'class' =>  'form-horizontal'
    ]
]);

if(\Yii::$app->user->identity->can("99")){
    $adminFields = Html::tag('div',
        Html::tag('div',
            $form->field($order, 'added',
                [
                    'options'   =>  [
                        'class' =>  'col-xs-6'
                    ]
                ])
            .$form->field($order, 'number',
                [
                    'options'   =>  [
                        'class' =>  'col-xs-6'
                    ]
                ]),
            [
                'class' => 'row',
                'style' => 'margin: 0'
            ]),
        ['class' => 'row', 'style' => 'margin: 0']);
}

echo
Html::tag('fieldset',
    Html::tag('div',
        Html::tag('div',
            $form->field($order, 'customerName',
                [
                    'options'   =>  [
                        'class' =>  'col-xs-6'
                    ]
                ])
            .$form->field($order, 'deliveryRegion',
                [
                    'options'   =>  [
                        'class' =>  'col-xs-6'
                    ]
                ]),
            [
                'class' => 'row',
                'style' => 'margin: 0'
            ]),
        ['class' => 'row', 'style' => 'margin: 0']).
    Html::tag('div',
        Html::tag('div',
            $form->field($order, 'customerSurname',
                [
                    'options'   =>  [
                        'class' =>  'col-xs-6'
                    ]
                ])
            .$form->field($order, 'deliveryCity',
                [
                    'options'   =>  [
                        'class' =>  'col-xs-6'
                    ]
                ]),
            [
                'class' => 'row',
                'style' => 'margin: 0'
            ]),
        ['class' => 'row', 'style' => 'margin: 0']).
    Html::tag('div',
        Html::tag('div',
            $form->field($order, 'customerPhone',
                [
                    'options'   =>  [
                        'class' =>  'col-xs-6'
                    ]
                ])
            .$form->field($order, 'deliveryType', [
                'options'   =>  [
                    'class' =>  'col-xs-6'
                ],
                'inputOptions'  =>  [
                    'id'    =>  'deliveryTypeInput'
                ]
            ])->dropDownList(\Yii::$app->runAction('orders/default/get-deliveries', ['type' => 'deliveryType'])),
            [
                'class' => 'row',
                'style' => 'margin: 0'
            ]),
        ['class' => 'row', 'style' => 'margin: 0']).
    Html::tag('div',
        Html::tag('div',
            $form->field($order, 'customerEmail',
                [
                    'options'   =>  [
                        'class' =>  'col-xs-6'
                    ]
                ])
            .Html::tag('div',
                $form->field($order, 'deliveryParam',
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
                $form->field($order, 'deliveryInfo',
                    [
                        'options'   =>  [
                            'class' =>  'col-xs-4'
                        ]
                    ])
                    ->label('Склад #'),
                ['class'    =>  'row col-xs-6']
            ),
            [
                'class' => 'row',
                'style' => 'margin: 0'
            ]),
        ['class' => 'row', 'style' => 'margin: 0']).$adminFields.
    Html::tag('div',
        Html::tag('div',
            $form->field($order, 'coupon',
                [
                    'options'   =>  [
                        'class' =>  'col-xs-6'
                    ]
                ])
            .Html::tag('div',
                $form->field($order, 'paymentType', [
                    'options'   =>  [
                        'class' =>  'col-xs-6'
                    ],
                    'inputOptions'  =>  [
                        'id'    =>  'paymentTypeInput'
                    ]
                ])->dropDownList(\Yii::$app->runAction('orders/default/get-payments', ['type' => 'paymentType'])).
                $form->field($order, 'paymentParam',
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
                ]),
            [
                'class' => 'row',
                'style' => 'margin: 0'
            ]),
        ['class' => 'row', 'style' => 'margin: 0']).
    Html::tag('br').
    Html::tag('center', Html::button('Сохранить', [
            'class' =>  'btn btn-lg btn-success',
            'type'  =>  'submit'
        ]).' или '.Html::button('отменить', [
            'class'         =>  'btn btn-default',
            'data-dismiss'  =>  'modal',
            'aria-hidden'   =>  'true'
        ]), [
        'style' =>  'text-align: middle; margin: 0px auto',
    ])
) ?>
<?php
$form->end();