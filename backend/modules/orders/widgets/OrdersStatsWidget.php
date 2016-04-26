<?php
/**
 * Created by PhpStorm.
 * User: bobroid
 * Date: 25.04.16
 * Time: 17:45
 */

namespace backend\modules\orders\widgets;


use rmrevin\yii\fontawesome\FA;
use yii\base\Widget;
use yii\helpers\Html;

class OrdersStatsWidget extends Widget
{

    public $model;

    public function run(){
        $parts = [
            $this->renderOrders(),
            $this->renderNotCalled(),
            $this->renderInStore()
        ];

        if(\Yii::$app->user->identity->superAdmin == 1){
            $parts[] = $this->renderCosts();
        }

        return Html::tag('div',
            Html::tag('div',
                Html::tag('div',
                    implode('', $parts),
                    [
                        'style' =>  'display: table; margin: 0 auto; position: relative; top: 11px'
                    ]
                ),
                [
                    'class' =>  'ordersStats'
                ]
            ),
            [
                'class' =>  'ordersStatsContainer'
            ]
        );
    }

    public function renderOrders(){
        $completed = $this->model->doneOrders;
        $total = $this->model->orders;

        return $this->renderBlock(
            FA::icon('dropbox', ['class' =>  'yellow'])->size(FA::SIZE_3X)->inverse(),
            Html::tag('table',
                Html::tag('tr',
                    Html::tag('td',
                        Html::tag('span', 'Заказов')
                    ).
                    Html::tag('td',
                        Html::tag('span', 'Выполнено')
                    )
                ).
                Html::tag('tr',
                    Html::tag('td',
                        Html::tag('h1',
                            strlen($total) >= 4 ? Html::tag('small', $total) : $total,
                            [
                                'style' =>  'line-height: '.(strlen($total) < 4 ? '26px;' : '0px;')
                            ]
                        )
                    ).
                    Html::tag('td',
                        Html::tag('h1',
                            (strlen($completed) >= 4 ? Html::tag('small', $completed, ['style' => 'color: #fff']) : $completed),
                            [
                                'style' =>  'color: #fff; min-width: 36px; background: #B5B5B5; padding: 5px; border-radius: 3px; display: inline-block; line-height: '.(strlen($completed) < 4 ? '26px;' : '0px;')
                            ]
                        )
                    )
                )
            )
        );
    }

    public function renderNotCalled(){
        return $this->renderBlock(
            FA::icon('frown-o', ['class' =>  'purple'])->size(FA::SIZE_3X)->inverse(),
            Html::tag('span', 'Не прозвонено').
            Html::tag('h1', $this->model->notCalled)
        );
    }

    public function renderInStore(){
        return $this->renderBlock(
            FA::icon('cubes', ['class' =>  'green'])->size(FA::SIZE_2X)->inverse(),
            Html::tag('span', 'Всего на складе').
            Html::tag('h1', $this->model->waitDelivery)
        );
    }

    public function renderCosts(){
        return $this->renderBlock(
            FA::icon('calculator', ['class' =>  'blue'])->size(FA::SIZE_3X)->inverse(),
            Html::tag('span', 'Сумма заказов').
            Html::tag('h1', "{$this->model->ordersActualAmount}₴", ['title' => 'Фактическая сумма заказов', 'style' => 'line-height: 22px; font-size: 22px']).
            Html::tag('small', "{$this->model->ordersAmount}₴", ['title' => 'Общая сумма заказов'])
        );
    }

    public function renderBlock($label, $description){
        return Html::tag('div',
            Html::tag('div',
                Html::tag('div',
                    $label,
                    [
                        'class' =>  'icon'
                    ]
                ).
                Html::tag('div',
                    $description,
                    [
                        'class' =>  'description'
                    ])
            ),
            [
                'style' =>  'display: table-cell'
            ]
        );
    }

}