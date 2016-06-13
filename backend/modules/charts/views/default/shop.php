<?php
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\Html;

/** @var \backend\models\Shop $shop
 *  @var \backend\modules\charts\models\MonthReport $report */

$this->title = 'Продажи магазина';

$this->params['breadcrumbs'][] = $this->title;

echo Html::beginTag('div', [
            'class' =>  'col-xs-12 row'
        ]
),
    Html::tag('h1', $this->title, ['class' => 'page-header col-xs-12', 'style' => 'margin-top: 0px;']);
/*Html::tag('div',
    Html::tag('div',
        Html::tag('div', 'Краткая статистика',
            [
                'class' => 'panel-heading'
            ]
        ).
        Html::tag('div',
            Html::tag('div', Html::tag('span', 'Месячный план:')).
            Html::tag('div',
                Html::tag('div',
                    Html::tag('span', '', ['class' => 'sr-only']),
                    [
                        'class'         =>  'progress-bar progress-bar-striped active',
                        'role'          =>  'progressbar',
                        'aria-valuenow' =>  '45',
                        'aria-valuemin' =>  '45',
                        'aria-valuemax' =>  '45',
                        'style'         =>  'width: 45%'
                    ]
                ),
                [
                    'class' =>  'progress'
                ]
            ).
            Html::tag('div', Html::tag('small', 'Месячный план: '), ['style' => 'margin-top: -20px;']).
            '1. Месячный план
                                    2. Выполнено
                                    5. Осталось продать
                                    6. План на сегодня
                                    7. Выполнено : Поступлений денег за сегодня/ поступление суммы заказов за сегодня
                                    8. Подробная статистика
                                    9. Страница подробной статистики', ['class' => 'panel-body']),
        [
            'class' => 'panel panel-default'
        ]
    ),
    [
        'class' =>  'col-xs-3'
    ]
),*/

$css = <<<'CSS'
.info-parred-block{
    text-align: center;
}

.info-parred-block .count{
    float: right;
    line-height: 50px;
    vertical-align: middle;
}

.panels .panel{
    height: 150px;
    position: relative;
}

.amcharts-main-div a{
opacity: 0 !important;
}
CSS;

$this->registerCss($css);

$showFourth = ($report->month == date('m') && $report->year == date('Y'));

$columns = $showFourth ? 3 : 4;

?>
<div class="row panels">
    <div class="col-xs-<?=$columns?>">
        <div class="panel panel-<?=$report->planProgressInPercents > 99 ? 'success' : 'default'?>">
            <div class="panel-heading">
                Месячный план
            </div>
            <div class="panel-body">
                <?=Html::tag('div',
                    Html::tag('div',
                        Html::tag('span', '', ['class' => 'sr-only']),
                        [
                            'class'         =>  'progress-bar progress-bar-striped active progress-bar-'.($report->planProgressInPercents == 100 ? 'success' : ($report->planProgressInPercents < 50 ? 'danger' : 'warning')),
                            'role'          =>  'progressbar',
                            'aria-valuenow' =>  $report->planProgress,
                            'aria-valuemin' =>  '0',
                            'aria-valuemax' =>  $report->shop->monthPlan,
                            'style'         =>  'width: '.$report->planProgressInPercents.'%'
                        ]
                    ),
                    [
                        'class' =>  'progress'
                    ]
                )?>
                <small style="margin-top: -10px; display: block;">План: <?=$report->planProgress?> грн. из <?=$report->shop->monthPlan?> грн.</small>
                <?php
                if($report->planProgressInPercents != 100){
                    ?>
                    <small style="margin-top: -2px; display: block;">Осталось продать на <?=$report->shop->monthPlan - $report->planProgress?> грн.</small>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="col-xs-<?=$columns?>">
        <div class="panel panel-default">
            <div class="panel-heading">
                Статистика заказов
            </div>
            <div class="panel-body row" style="text-align: center">
                <div class="col-xs-6">
                    <div class="info-parred-block">
                        <?= FA::i('smile-o')->size(FA::SIZE_4X)?> <span class="count"><?=count($report->doneOrders)?></span>
                    </div>
                    Выполнено
                </div>
                <div class="col-xs-6">
                    <div class="info-parred-block">
                        <?= FA::i('shopping-cart')->size(FA::SIZE_4X)?> <span class="count"><?=count($report->orders)?></span>
                    </div>
                    Всего
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-<?=$columns?>">
        <div class="panel panel-default">
            <div class="panel-heading">
                Сумма продаж (за месяц)
            </div>
            <div class="panel-body" style="padding-top: 25px;">
                <h3 style="padding: 0; margin: 0; line-height: 48px; vertical-align: middle"><?= FA::i('money', ['style' => 'float: left'])->size(FA::SIZE_2X)?> <span style="margin-left: 10px; float: left;"><?=$report->planProgress?></span>&nbsp;грн.</h3>
            </div>
        </div>
    </div>
    <?php if($showFourth){ ?>
    <div class="col-xs-<?=$columns?>">
        <div class="panel panel-default">
            <div class="panel-heading">
                Статистика за сегодня
            </div>
            <div class="panel-body">
                <table>
                    <tr>
                        <td>Заказов</td>
                        <td><?=count($report->todayOrders)?></td>
                    </tr>
                    <tr>
                        <td>Интернет</td>
                        <td><?=count($report->todayInternetOrders)?>\<?=count($report->todayDoneInternetOrders)?> (<?=$report->todayInternetOrdersSum?> грн.)</td>
                    </tr>
                    <tr>
                        <td>Магазин</td>
                        <td><?=count($report->todayShopOrders)?>\<?=count($report->todayDoneShopOrders)?> (<?=$report->todayShopOrdersSum?> грн.)</td>
                    </tr>
                    <tr>
                        <td>План на сегодня&nbsp;</td>
                        <td><?=$report->shop->daySalesPlan?> грн.</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="col-xs-12">
        <h3 class="text-center">График продаж</h3>
        <?=\speixoto\amcharts\Widget::widget([
            'width'             =>  'auto',
            'chartConfiguration'   =>  [
                'type'          =>  'serial',
                'categoryField' =>  'date',
                'startDuration' =>  1,
                'categoryAxis'  =>  [
                    'gridPosition'  =>  'start'
                ],
                'valueAxis'  =>  [
                    'id'    =>  'moneyForDay',
                    'title' =>  'Сумма'
                ],
                'chartScrollbar'    =>  [
                      'enabled' =>  true
                ],
                'graphs'    =>  [
                    [
                        'balloonText'   =>  '[[category]]: [[title]] [[value]]',
                        'bullet'        =>  'round',
                        'id'            =>  'graph1',
                        'title'         =>  'Заказов на',
                        'valueField'    =>  'earned'

                    ],
                    [
                        'balloonText'   =>  '[[category]]: [[title]] [[value]]',
                        'bullet'        =>  'round',
                        'id'            =>  'graph2',
                        'title'         =>  'Подтверждено на',
                        'valueField'    =>  'confirmed'

                    ],
                    [
                        'balloonText'   =>  '[[category]]: [[title]] [[value]]',
                        'bullet'        =>  'round',
                        'id'            =>  'graph3',
                        'title'         =>  'Выполнено на',
                        'valueField'    =>  'done'

                    ],
                ],
                'dataProvider'  =>  $report->salesStats
            ]
        ])?>
    </div>
</div>
<?php
    echo Html::endTag('div');


