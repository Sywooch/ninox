<?php
use kartik\grid\CheckboxColumn;
use kartik\grid\GridView;
use yii\bootstrap\Html;

/** @var \yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Статистика заказов клиентов';

$this->params['breadcrumbs'][] = $this->title;

$categoryDropDown = [];

echo Html::tag('h1', $this->title, ['class' => 'page-heading']),
    Html::tag('div',
        Html::button('Весь период', ['class' => 'btn btn-default']).
        Html::button('Выбор периода', ['class' => 'btn btn-default']),
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
        Html::button('Делали заказ', ['class' => 'btn btn-default']).
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
                            Html::tag('div', $customer->phone).
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
                            $sumArray[] = Html::tag('div', "{$customer->lastOrder->actualAmount} грн.", ['title' => 'Сумма последнего чека']);
                        }

                        if($customer->middleOrder != 0){
                            $sumArray[] = Html::tag('div', "{$customer->middleOrder} грн.", ['title' => 'Средний чек']);
                        }

                        if($customer->spentMoney != 0){
                            $sumArray[] = Html::tag('div', "{$customer->spentMoney} грн.", ['title' => 'Потрачено всего']);
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