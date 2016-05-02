<?php
use yii\bootstrap\Html;

$css = <<<'CSS'
    body { background: none; color: #000;}
	table.printOrder { width: 90%; margin: 80px; color: #616161; }
	td.print_head { border-bottom: 1px solid #9a9a9a; }
	.leader { font-size: 26px; padding-top: 10px; height: 30px; }
	.tovars2 { border: none;}
	.tovars_img2 {
		display: block;
		border:1px solid #555555;
		width: 165px;
		height: 123px;}
	.tovars_img2 img{
		width: 165px;
		height: 123px;}
	.shopping table td {text-align:center;}
	.shopping table td.city { font-size:30px; text-decoration:underline; font-weight: bold; }
	.shopping table td.oblast { font-size:12px;}
	.shopping table td.nova_poshta { font-size:18px; }
	.shopping table td.name { font-size:18px; font-weight:bold;}
	.shopping table td.telefon { font-size:14px; }
	.shopping table td.plateg, .shopping table td.manager, .shopping table td.delivery { font-size:14px; font-weight:bold;white-space: nowrap; text-align: right; }
	.shopping table td.plateg > span > span, .shopping table td.manager > span > span, .shopping table td.delivery > span > span { padding-right: 5px; }
	.shopping table td.plateg > span, .shopping table td.manager > span, .shopping table td.delivery > span { margin-right: 33%; }
	.shopping table td.manager { border-bottom: 1px solid #9a9a9a; padding-bottom: 5px; }
	.shopping table td.plateg { border-top: 1px solid #9a9a9a; padding-top: 5px; }
	.shopping table td.zakaznumber { font-size: 60px; }
	.shopping table td.plateg img, .shopping table td.manager img, .shopping table td.delivery img{ vertical-align:middle; }
	.shopping input, .shopping textarea, .shopping select { border:none; }
	@media print {
		.shopping table td.plateg > span, .shopping table td.manager > span, .shopping table td.delivery > span { margin-right: 0% !important; }
		.pageend { page-break-after: always; }
		.block, x:-moz-any-link { page-break-before: always; }
        .barcode {
            float: right;
        }
	}
    .barcode {
        float: right;
    }

    .warning{
        color: red;
        font-size: 24px;
    }

    .success{
        color: blue;
        font-size: 24px;
    }


    table td li {
        list-style: none;
    }

    .tov_title {
        font-size: 14px;
        text-decoration: underline;
    }

    .tovars2 {
        padding: 4px 2px 0px 1px;
        margin: 12px 12px 10px 0;
        border-radius: 5px;
        border: 1px solid #b3b3b3;
        width: 185px;
        display: table-cell;
        border-spacing: 0px;
    }

    .tovars_top2 {
        height: 58px;
        text-align: center;
        display: table-cell;
        vertical-align: middle;
        font-size: 12px;
        font-weight: normal;
        color: #960b66;
    }

    .tovars_top2 table {
        border-spacing: 0px;
    }

    table.printOrder {
        color: #616161;
    }
CSS;

$this->registerCss($css);

$i = $counter = 0;

$items = '';

foreach($order->items as $item){
    $i++;
    $counter++;

    if($i == 1){
        $items .= Html::beginTag('tr');
    }

    $items .= $this->render('_order_item', [
        'item'      =>  $item,
        'counter'   =>  $counter
    ]);


    if($i == 2){
        $items .= Html::endTag('tr');
        $i = 0;
    }
}

echo Html::tag('table',
    Html::tag('tr',
        Html::tag('td',
            Html::tag('span',
                ($order->canChangeItems != 1 ?
                    "Замену в заказе не делать!!! Штраф!!!"  :
                    "Замену в заказе можно делать"),
                [
                    'class' =>  $order->canChangeItems == 1 ? 'info' : 'warning'
                ]
            ).
            Html::tag('div',
                "Содержимое заказа №{$order->number}".
                Html::tag('span',
                    "Подтверждён".
                    Html::input('checkbox', null, null, [
                        $order->confirmed == 1 ? 'checked' : 'notChecked' => 'checked'
                    ]),
                    [
                        'style' =>  'font-size: 14px'
                    ]
                ),
                [
                    'class'  => 'leader'
                ]
            ).
            ($order->newCustomer ? 'Клиент новый' : 'Клиент раньше делал заказ').
            Html::tag('br').
            "Дата поступления: ".\Yii::$app->formatter->asDatetime($order->added, 'php:d.m.Y H:i').
            Html::tag('br').
            "Город: {$order->deliveryCity}".
            Html::tag('br').
            Html::tag('span', "Комментарий: {$order->customerComment}", ['style' => 'font-size: 11px; color: #f00']),
            [
                'class' =>  'print_head'
            ]
        ).
        Html::tag('td', 'ИТОГО:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.Html::tag('br').$order->realSum.' грн.', [
            'class' =>  'print_head'
        ])
    ).
    Html::tag('tr',
        Html::tag('td', Html::tag('table', $items, ['class' => 'pageend']),
            [
                'colspan' => 2
            ]
        )
    ),
    [
        'class' =>  'printOrder'
    ]
);

    echo $this->render('_last_page', [
        'order' =>  $order
    ]);