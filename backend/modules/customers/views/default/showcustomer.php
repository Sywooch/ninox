<?php
$css = <<<'STYLE'
    .lables-div .label{
        float: left;
        margin-right: 5px;
        margin-left: 5px;
    }
STYLE;

$this->registerCss($css);
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
            <td><?=$customer->Phone?></td>
        </tr>
        <tr>
            <td>email:</td>
            <td><?=$customer->eMail?></td>
        </tr>
        <tr>
            <td>Номер карты:</td>
            <td><?=$customer->CardNumber?></td>
        </tr>
        <tr>
            <td>Скидка:</td>
            <td><?=$customer->Discount?>%</td>
        </tr>
        </tbody>
    </table>
    <?php if(!empty($lastOrder)){ ?>
        <div class="panel panel-info">
            <div class="panel-heading">
                <h1 class="panel-title">Последний заказ</h1>
            </div>
            <div class="panel-body">
                <table class="table table-stripped">
                    <tbody>
                    <tr>
                        <td style="width: 40%;">Номер заказа</td>
                        <td><a href="/admin/orders/showorder/<?=$lastOrder->id?>"><?=$lastOrder->id?></a></td>
                    </tr>
                    <tr>
                        <td>Фактическая сумма заказа</td>
                        <td><?=$lastOrder->fakt_summ?> грн.</td>
                    </tr>
                    <tr>
                        <td>Дата заказа</td>
                        <td><?=date("d.m.Y H:i", $lastOrder->displayorder)?></td>
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
            'summary'       =>  'Заказы клиента: {begin} - {end}, всего {totalCount} заказов',
            'tableOptions'       =>  [
                'class'     =>  'col-xs-12 customer-data-orders',
                'style'     =>  'text-align: center'
            ],
            'rowOptions'    =>  function($model){
                $class = '';

                if($model->done == 1){
                    $class = 'success';
                }else if($model->trash == 1){
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
                    'attribute' =>  'id',
                    'format'    =>  'html',
                    'value'     =>  function($model){
                        return '<a href="/admin/orders/showorder/'.$model->id.'">'.$model->id.'</a>';
                    }
                ],
                [
                    'attribute' =>  'Name',
                    'label'     =>  'Имя',
                    'value'     =>  function($model){
                        return $model->name.' '.$model->surname;
                    }
                ],
                [
                    'attribute' =>  'phone',
                    'label'     =>  'Телефон'
                ],
                [
                    'attribute' =>  'displayorder',
                    'label'     =>  'Дата заказа',
                    'value'     =>  function($model){
                        return date('d.m.Y', $model->displayorder);
                    }
                ],
                [
                    'class'     =>  '\kartik\grid\CheckboxColumn',
                    'rowHighlight' =>  false,
                    'header'    =>  'Заказ оплачен',
                    'checkboxOptions'   =>  function($model){
                        return [
                            'checked'   =>  $model->confirm_money == 1,
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
                            'checked'   =>  $model->trash == 1,
                            'disabled'  =>  true
                        ];
                    }
                ],
                [
                    'attribute' =>  'fakt_summ',
                    'label'     =>  'К оплате',
                    'value'     =>  function($model){
                        return $model->fakt_summ.' грн.';
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