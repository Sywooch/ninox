<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 23.10.15
 * Time: 13:58
 *
 * @var \backend\models\NovaPoshtaOrder $invoice
 * @var integer $orderID
 */

use kartik\form\ActiveForm;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;

echo Html::button('', [
    'class'                 =>  'remodal-close',
    'data-remodal-action'   =>  'close'
]);

$seat = new \backend\models\NovaPoshtaSeat();

\yii\widgets\Pjax::begin([
    'ID'                    =>  'x1',
    'enablePushState'    =>  false
]);
$form = ActiveForm::begin([
    'id'        =>  'invoiceForm',
    'action'    =>  '/orders/createinvoice/'.$orderID,
    'enableAjaxValidation' => false,
    'options'   =>[
        'data-pjax'=>'#x1'
    ],
    'enableClientValidation' => true
]);
echo Html::tag('div',
        Html::tag('div',
            $form->field($invoice, 'ServiceType')->dropDownList(\Yii::$app->NovaPoshta->serviceTypes()).
            $form->field($invoice, 'PaymentMethod')->dropDownList(\Yii::$app->NovaPoshta->paymentMethods()).
            $form->field($invoice, 'PayerType')->dropDownList(\Yii::$app->NovaPoshta->typesOfPayers()).
            $form->field($invoice, 'Cost').
            $form->field($invoice, 'Description'), [
                'class' => 'col-xs-4',
                'style' =>  'border-right: 1px solid rgba(0, 0, 0, 0.4)'
            ]).
        Html::tag('div',
            Html::tag('div', Html::tag('b', 'Отправления'), ['class' => 'col-xs-12']).
            Html::tag('div',
                Html::tag('div', $form->field($seat, 'volumetricWidth', [
                        'inputOptions'  =>  [
                            'name'      =>  'NovaPoshtaOrder[OptionsSeat][0][volumetricWidth]',
                            'name_format'      =>  'NovaPoshtaOrder[OptionsSeat][%d][volumetricWidth]',
                            'class'     =>  'form-control'
                        ],
                        'options'   => [
                            'class' => 'col-xs-3',
                            'name_format'      =>  'NovaPoshtaOrder[OptionsSeat][%d][volumetricWidth]',
                        ]
                    ]).
                    $form->field($seat, 'volumetricLength', [
                        'inputOptions'  =>  [
                            'name'      =>  'NovaPoshtaOrder[OptionsSeat][0][volumetricLength]',
                            'name_format'      =>  'NovaPoshtaOrder[OptionsSeat][%d][volumetricLength]',
                            'class'     =>  'form-control'
                        ],
                        'options'   => [
                            'class' => 'col-xs-3',
                        ]
                    ]).
                    $form->field($seat, 'volumetricHeight', [
                        'inputOptions'  =>  [
                            'name'      =>  'NovaPoshtaOrder[OptionsSeat][0][volumetricHeight]',
                            'name_format'      =>  'NovaPoshtaOrder[OptionsSeat][%d][volumetricHeight]',
                            'class'     =>  'form-control'
                        ],
                        'options'   => [
                            'class' => 'col-xs-3',
                        ]
                    ]).
                    $form->field($seat, 'weight', [
                        'inputOptions'  =>  [
                            'name'      =>  'NovaPoshtaOrder[OptionsSeat][0][weight]',
                            'name_format'      =>  'NovaPoshtaOrder[OptionsSeat][%d][weight]',
                            'class'     =>  'form-control'
                        ],
                        'options'   => [
                            'class' => 'col-xs-2',
                        ]
                    ]).Html::tag('div', Html::button(FA::i('times'), ['class' => 'seats_del btn btn-danger btn-sm', 'style' => 'margin-top: 28px;']), ['class' => 'col-xs-1']), [
                    'class' =>  'seats_var row',
                ]), [ 'id' => 'seats']).Html::button(FA::icon('plus').' Добавить', [
                'class' => 'seats_add btn btn-success btn-sm',
                'style' =>  'margin: 0px auto; display: block;'
            ]), [
                'class' => 'col-xs-8'
            ]),[
        'class' => 'row'
    ]),
    Html::button('Создать накладную', ['id' => 'createInvoice', 'type' => 'submit', 'class' => 'btn btn-default btn-success']);

$form::end();
\yii\widgets\Pjax::end();