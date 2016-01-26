<?php
use yii\bootstrap\Html;

$form = new \yii\bootstrap\ActiveForm([
'options' =>  [
'class' =>  'form-horizontal'
]
]);

$adminFields = '';

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

$form->begin();
?>
<?=
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
            .$form->field($order, 'deliveryAddress',
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
            $form->field($order, 'customerEmail',
                [
                    'options'   =>  [
                        'class' =>  'col-xs-6'
                    ]
                ])
            .Html::tag('div',
                $form->field($order, 'deliveryType',
                    [
                        'options'   =>  [
                            'class' =>  'col-xs-8'
                        ]
                    ])
                    ->dropDownList([]).//\common\models\DeliveryType::getDeliveryTypes()).
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
            .$form->field($order, 'paymentType',
                [
                    'options'   =>  [
                        'class' =>  'col-xs-6'
                    ]
                ])
                ->dropDownList([]),//\common\models\PaymentType::getPaymentTypes()),
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