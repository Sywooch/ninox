<?php
use kartik\date\DatePicker;
use kartik\editable\Editable;
use kartik\grid\CheckboxColumn;
use kartik\grid\GridView;
use yii\bootstrap\Html;

/** @var \yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Статистика заказов клиентов';

$this->params['breadcrumbs'][] = $this->title;

$categoryDropDown = [];

$dateSelected = (\Yii::$app->request->get('dateTo') != '' || \Yii::$app->request->get('dateFrom') != '');
$notDidOrder = \Yii::$app->request->get('didOrder') == 0;

$js = <<<JS
$("#customersorderssearch-datefrom-cont").on('editableSubmit', function(event, val, form, jqXHR) { 
    console.log('Submitted Value ' + val);
}).on('editableBeforeSubmit', function(event, jqXHR) { 
    console.log('Before submit triggered');
     event.preventDefault(); 
});
JS;

$this->registerJs($js);

$model = new \backend\models\CustomersOrdersSearch();

echo Html::tag('h1', $this->title, ['class' => 'page-heading']),
    Html::tag('div',
        Html::button('Весь период', ['data-period' => 'all', 'class' => 'btn btn-default', $dateSelected == false ? 'disabled' : 'enabled' => 'disabled']).
        Editable::widget([
            'model'         =>  $model,
            'attribute'     =>  'dateFrom',
            'header'        =>  'период',
            'id'            =>  'datePickerEditable',
            'asPopover'     =>  true,
            'format'        =>  Editable::FORMAT_BUTTON,
            'size'          =>  'md',
            'displayValue'  =>  ' ',
            'editableButtonOptions' =>  [
                'label'     =>  'Выбор периода',
                'class'     =>  'btn btn-default',
                'data-period' => 'period',
                'style'     =>  'margin-left: -6px; border-top-left-radius: 0px; border-bottom-left-radius: 0px;'
            ],
            'valueIfNull'   =>  ' ',
            'inputType'     =>  Editable::INPUT_DATE,
            'options'       =>  [
                'options'       =>  [
                    'placeholder'   =>  'от'
                ]
            ],
            'afterInput'    =>  function($form, $widget) {
                echo $form->field($widget->model, 'dateTo')->widget(DatePicker::classname(), [
                    'options'   =>  [
                        'placeholder'   =>  'до'
                    ]
                ])->label(false);
            },
        ]),
        [
            'class' =>  'btn-group'
        ]).
    '&nbsp;'.
    Html::tag('div',
        Html::button('Бижутерия', ['class' => 'btn btn-default']).
        Html::button('Рукоделие', ['class' => 'btn btn-default']).
        \yii\bootstrap\ButtonDropdown::widget([
            'label'     =>  'Другая категория',
            'options'   =>  [
                'class' =>  'btn btn-default'
            ],
            'dropdown'  =>  [
                'items' =>  $categoryDropDown
            ]
        ]),
        [
            'class' =>  'btn-group'
        ]
    ).
    Html::tag('hr', '', ['style' => 'border-color: transparent; margin: 5px 0',]).
    Html::tag('div',
        Html::button('Делали заказ', ['class' => 'btn btn-default', $notDidOrder ? 'disabled' : '' => 'disabled']).
        Html::button('Не делали заказ', ['class' => 'btn btn-default']),
        [
            'class' =>  'btn-group'
        ]),
    Html::tag('div',
        GridView::widget([
            'bordered'      =>  false,
            'summary'       =>  false,
            'dataProvider'  =>  $dataProvider,
            'columns'       =>  [
                [
                    'class'     =>  CheckboxColumn::className()
                ],
                [
                    'class'     =>  \kartik\grid\SerialColumn::className()
                ],
                [
                    'attribute' =>  'name',
                    'label'     =>  'Клиент',
                    'format'    =>  'html',
                    'value'     =>  function($customer){
                        return Html::tag('div', Html::a("{$customer->name} {$customer->surname}", '/customers/view/'.$customer->ID)).
                            Html::tag('div', $customer->City).
                            Html::tag('div', \Yii::$app->formatter->asPhone($customer->phone)).
                            Html::tag('div', $customer->email);
                    }
                ],
                [
                    'label'     =>  'Сумма',
                    'format'    =>  'html',
                    'hAlign'    =>  GridView::ALIGN_CENTER,
                    'vAlign'    =>  GridView::ALIGN_MIDDLE,
                    'value'     =>  function($customer){
                        $sumArray = [];

                        if(!empty($customer->lastOrder->actualAmount)){
                            $money = number_format($customer->lastOrder->actualAmount, 2, '.', ' ');

                            $sumArray[] = Html::tag('div', "{$money} грн.", ['title' => 'Сумма последнего чека']);
                        }

                        if($customer->middleOrder != 0){
                            $money = number_format($customer->middleOrder, 2, '.', ' ');

                            $sumArray[] = Html::tag('div', "{$money} грн.", ['title' => 'Средний чек']);
                        }

                        if($customer->spentMoney != 0){
                            $money = number_format($customer->ordersSum, 2, '.', ' ');

                            $sumArray[] = Html::tag('div', "{$money} грн.", ['title' => 'Потрачено всего']);
                        }

                        return implode('', $sumArray);
                    }
                ],
                [
                    'label'     =>  'Дата',
                    'format'    =>  'html',
                    'hAlign'    =>  GridView::ALIGN_CENTER,
                    'vAlign'    =>  GridView::ALIGN_MIDDLE,
                    'value'     =>  function($customer){
                        $return = [];
                        if(!empty($customer->lastOrder)){
                            $return[] = Html::tag('div', \Yii::$app->formatter->asDate($customer->lastOrder->added));
                        }

                        if(!empty($customer->ordersPeriod)){
                            $return[] = Html::tag('div', \Yii::$app->formatter->asLeftTime($customer->ordersPeriod));
                        }

                        return implode('', $return);
                    }
                ],
                [
                    'label'     =>  'Персональная скидка'
                ]
            ]
        ]),
        [
            'style' =>  'margin-top: 20px;'
        ]
    );