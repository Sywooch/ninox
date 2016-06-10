<?php
$css = <<<'STYLE'
    .lables-div .label{
        float: left;
        margin-right: 5px;
        margin-left: 5px;
    }
STYLE;

$this->registerCss($css);

/** @var \backend\models\Customer $customer */
?>
<div class="col-xs-4">
    <div class="row" style="margin-left: 0; margin-bottom: 20px; margin-top: 10px;">
        <div class="col-xs-4">
            <div class="circle">
                <div class="admin-background admin-orders-background">
                    <span class="circle-info"><?=$ordersStats['count'] != '' ? $ordersStats['count'] : 0?></span>
                    <span class="circle-span">заказов</span>
                </div>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="circle">
                <div class="admin-background admin-orderssumm-background">
                    <span class="circle-info"><?=($ordersStats['summ'] != '' && $ordersStats['count'] != '') ? round($ordersStats['summ'] / $ordersStats['count']) : 0?></span>
                    <span class="circle-span">средний чек</span>
                </div>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="circle">
                <div class="admin-background admin-money-background">
                    <span class="circle-info"><?=$customer->money?></span>
                    <span class="circle-span">на счету</span>
                </div>
            </div>
        </div>
    </div>
    <div style="display: block;" class="lables-div">
        <center><?=implode('&nbsp;', $lables)?></center>
    </div>
    <br>
    <table class="table table-stripped customer-data-table table-condensed">
        <tbody>
        <tr>
            <td>Имя:</td>
            <td><?=$customer->Company?></td>
        </tr>
        <tr>
            <td>Город, область:</td>
            <td><?=$customer->City?></td>
        </tr>
        <tr>
            <td>Адрес доставки:</td>
            <td><?=$customer->Address?></td>
        </tr>
        <tr>
            <td>Номер телефона:</td>
            <td><?=\Yii::$app->formatter->asPhone($customer->phone)?></td>
        </tr>
        <tr>
            <td>email:</td>
            <td><?=$customer->email?></td>
        </tr>
        <tr>
            <td>Номер карты:</td>
            <td><?=$customer->cardNumber?></td>
        </tr>
        <tr>
            <td>Скидка:</td>
            <td><?=$customer->Discount?>%</td>
        </tr>
        </tbody>
    </table>
    <?php if(!$customer->lastOrder->isNewRecord){ ?>
        <div class="panel panel-info">
            <div class="panel-heading">
                <h1 class="panel-title">Последний заказ</h1>
            </div>
            <div class="panel-body">
                <table class="table table-stripped">
                    <tbody>
                    <tr>
                        <td style="width: 40%;">Номер заказа</td>
                        <td><a href="/orders/showorder/<?=$customer->lastOrder->id?>"><?=$customer->lastOrder->number?></a></td>
                    </tr>
                    <tr>
                        <td>Фактическая сумма заказа</td>
                        <td><?=$customer->lastOrder->actualAmount?> грн.</td>
                    </tr>
                    <tr>
                        <td>Дата заказа</td>
                        <td><?=\Yii::$app->formatter->asDate($customer->lastOrder->added)?></td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    <?php } ?>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h1 class="panel-title">Дополнительная информация</h1>
        </div>
        <div class="panel-body">
            <table class="table table-stripped">
                <tbody>
                <tr>
                    <td>Сумма заказов</td>
                    <td><?=$ordersStats['summ'] == '' ? 0 : $ordersStats['summ']?> грн.</td>
                </tr>
                <tr>
                    <td>Дата рождения:</td>
                    <td><?=\Yii::$app->formatter->asDate($customer->birthday, 'php:d.m.Y')?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="col-xs-8">
    <div class="panel panel-default" style="margin-top: 10px;">
        <?=\kartik\grid\GridView::widget([
            'dataProvider'  =>  $orders,
            'summary'       =>  false,
            'tableOptions'       =>  [
                'class'     =>  'col-xs-12 customer-data-orders',
                'style'     =>  'text-align: center'
            ],
            'rowOptions'    =>  function($model){
                $class = '';

                if($model->done == 1){
                    $class = 'success';
                }else if($model->deleted == 1){
                    $class = 'danger';
                }

                return [
                    'class' =>  $class
                ];
            },
            'columns'       =>  [
                [
                    'header'    =>  '',
                    'class'     =>  '\kartik\grid\ActionColumn'
                ],
                [
                    'attribute' =>  'number',
                    'format'    =>  'html',
                    'value'     =>  function($model){
                        return '<a href="/orders/showorder/'.$model->id.'">'.$model->number.'</a>';
                    }
                ],
                [
                    'attribute' =>  'Name',
                    'label'     =>  'Имя',
                    'value'     =>  function($model){
                        return $model->customerName.' '.$model->customerSurname;
                    }
                ],
                [
                    'attribute' =>  'customerPhone',
                    'label'     =>  'Телефон'
                ],
                [
                    'attribute' =>  'added',
                    'label'     =>  'Дата заказа',
                    'value'     =>  function($model){
                        return date('d.m.Y', $model->added);
                    }
                ],
                [
                    'class'     =>  '\kartik\grid\CheckboxColumn',
                    'rowHighlight' =>  false,
                    'header'    =>  'Заказ оплачен',
                    'checkboxOptions'   =>  function($model){
                        return [
                            'checked'   =>  $model->moneyConfirmed == 1,
                            'disabled'  =>  true
                        ];
                    }
                ],
                [
                    'class'     =>  '\kartik\grid\CheckboxColumn',
                    'rowHighlight' =>  false,
                    'header'    =>  'Удалён',
                    'checkboxOptions'   =>  function($model){
                        return [
                            'checked'   =>  $model->deleted == 1,
                            'disabled'  =>  true
                        ];
                    }
                ],
                [
                    'attribute' =>  'actualAmount',
                    'label'     =>  'К оплате',
                    'value'     =>  function($model){
                        return $model->actualAmount.' грн.';
                    }
                ]
            ],
            'condensed'     =>  true,
            'hover'         =>  true,
            'bordered'      =>  false,
            'striped'       =>  false,
            'pjax'          =>  true,
            'persistResize' =>  true,
            'responsiveWrap' =>  true,
            'pager'         =>  [
                'options'   =>  [
                    'class' =>  'pagination pagination-sm',
                    'style' =>  'margin: 0'
                ]
            ],
            'layout'        =>  '<div class="panel-heading">{summary}</div>{items}<center>{pager}</center>'
        ])?>
    </div>
</div>