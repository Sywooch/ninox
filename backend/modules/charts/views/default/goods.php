<?php
use kartik\editable\Editable;
use kartik\form\ActiveForm;

$this->title = 'Отключеные товары';

$this->params['breadcrumbs'][] = $this->title;

$chartBasicConf = [
    'labelRadius'   =>  0,
    'balloonText'   =>  '[[title]]<br><span style="font-size:14px"><b>[[value]]</b> ([[percents]]%)</span>',
    'titleField'    =>  'category',
    'valueField'    =>  'column-1',
    'adjustPrecision'   =>  true,
    'legend'        =>  [
        'align'         =>  'center',
        'markerType'    =>  'circle'
    ],
    'pullOutRadius' =>  0,
    'theme'         =>  'light',
    'export'        =>  [
        'enabled'   =>  true
    ]
];

$chartsConfigurations = [
    'ordersCount'   =>  [
        'type'          => 'pie',
        'dataProvider'  => [
            [
                'category'  =>  'Из магазина',
                'column-1'  =>  '0'//$orders['fromShop']['all']
            ],[
                'category'  =>  'С сайта',
                'column-1'  =>  '0'//$orders['fromSite']['all']
            ]
        ]
    ],
    'paymentType'   =>  [
        'type'          => 'pie',
        'dataProvider'  => [
            [
                'category'  =>  'На карту',
                'column-1'  =>  '0'//$orders['payments']['card']
            ],[
                'category'  =>  'Наложеным платежом',
                'column-1'  =>  '0'//$orders['payments']['COD']
            ],[
                'category'  =>  'Покупка в магазине',
                'column-1'  =>  '0'//$orders['payments']['shop']
            ],[
                'category'  =>  'Самовывоз',
                'column-1'  =>  '0'//$orders['payments']['pickup']
            ]
        ]
    ],
    'byCategories'   =>  [
        'type'          => 'serial',
        'graphs'        =>  [
            [
                'balloonText'   =>  '[[title]] из категории "[[category]]": [[value]]',
                'fillAlphas'   =>  '1',
                'id'   =>  'column-1',
                'title'   =>  'Продано товаров',
                'type'   =>  'column',
                'valueField'   =>  'count',
            ]
        ],
        'categoryField'    =>  'name',
        'mouseWheelScrollEnabled'    =>  'true',
        'startDuration'    =>  "1",
        'valueField'    =>  'count',
        'dataProvider'  => '0'//$orders['byCategories'],
    ],
];

$s = $a = $b = $c = $d = '';

$minPeriod = \Yii::$app->request->get("minPeriod");
$maxPeriod = \Yii::$app->request->get("maxPeriod");

if($minPeriod || $maxPeriod){
    $s = '<small>';

    if($minPeriod){
        $s .= 'с ';
        $s .= $minPeriod;
        $s .= ' ';
    }

    if($maxPeriod){
        $s .= 'по ';
        $s .= $maxPeriod;
    }

    $s .= '</small>';

    if($minPeriod == date('d.m.Y', (time() - 86400)) && $maxPeriod == date('d.m.Y')){
        $d = ' class="active"';
        $s = '<small>за вчера</small>';
    }elseif($minPeriod == date('d.m.Y')){
        $c = ' class="active"';
        $s = '<small>за сегодня</small>';
    }elseif($maxPeriod == date('d.m.Y') && empty($minPeriod)){
        $a = ' class="active"';
    }elseif($minPeriod != '' || $maxPeriod != ''){
        $b = ' class="active"';
    }
}else{
    $a = ' class="active"';
}


$js = <<<'SCRIPT'
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
SCRIPT;

$this->registerJs($js);

$form = ActiveForm::begin([
    'id'    =>  'chartsForm',
    'validateOnBlur'    =>  true,
    'method'    =>  'get',
    'enableClientValidation'    =>  false,
    'fieldConfig' => [
        'template' => "{input}<div class=\"col-lg-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-1 control-label'],
    ]
]);
?>
<h1><?=$this->title?> <?=$s?></h1>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li<?=$a?>><a href="/charts">За всё время <span class="sr-only">(current)</span></a></li>
                <li<?=$c?>><a href="?minPeriod=<?=date('d.m.Y')?>">За сегодня</a></li>
                <li<?=$d?>><a href="?minPeriod=<?=date('d.m.Y', (time() - 86400))?>&maxPeriod=<?=date('d.m.Y')?>">За вчера</a></li>
                <li<?=$b?>><a href="#" style="padding-top: 13px; padding-bottom: 12px;">За период с <?=\kartik\editable\Editable::widget([
                            'name'      =>  'minPeriod',
                            'showAjaxErrors'    =>  false,
                            'header'    =>  '',
                            'submitOnEnter' =>  true,
                            'options'   =>  [
                                'pluginOptions' =>  [
                                    'format'        =>  'dd.mm.yyyy',
                                    'convertFormat' =>  true,
                                    'endDate'       =>  date('d.m.Y ')
                                ]
                            ],
                            'pluginEvents'  =>  [
                                'editableSubmit'    =>  "function(val, form){
                                    var params = 'minPeriod=' + form;

                                    if(getParameterByName('maxPeriod') != ''){
                                        params += '&maxPeriod=' + getParameterByName('maxPeriod') + '&';
                                    }

                                    location.href = '/charts?' + params;
                                }"
                            ],
                            'size'  =>  'md',
                            'value' =>  !empty($minPeriod) && $minPeriod != date('d.m.Y') ? $minPeriod : '',
                            'inputType' => Editable::INPUT_DATE,
                        ])?> до <?=\kartik\editable\Editable::widget([
                            'name'      =>  'maxPeriod',
                            'showAjaxErrors'    =>  false,
                            'header'    =>  '',
                            'submitOnEnter' =>  true,
                            'pluginEvents'  =>  [
                                'editableSubmit'    =>  "function(val, form){
                                    var params = '';
                                    if(getParameterByName('minPeriod') != ''){
                                        params += 'minPeriod=' + getParameterByName('minPeriod') + '&';
                                    }

                                    params += 'maxPeriod=' + form;

                                    location.href = '/charts?' + params;
                                }"
                            ],
                            'options'   =>  [
                                'pluginOptions' =>  [
                                    'format'        =>  'dd.mm.yyyy',
                                    'convertFormat' =>  true,
                                    'endDate'       =>  date('d.m.Y '),
                                    'language'      =>  'ru'
                                ]
                            ],
                            'size'  =>  'md',
                            'value' =>  !empty($maxPeriod) && $maxPeriod != date('d.m.Y') ? $maxPeriod : '',
                            'inputType' => Editable::INPUT_DATE,
                        ])?></a></li>
            </ul>
        </div>
    </div>
</nav>
<h2>По заказам</h2>
<div class="row">
    <div class="col-xs-6">
        <div class="thumbnail">
            <center class="lead">Количество заказов</center>
            <?=yii\amcharts\Widget::widget(['chartConfiguration' => array_merge($chartBasicConf, $chartsConfigurations['ordersCount'])])?>
        </div>
    </div>
    <div class="col-xs-6">
        <div class="thumbnail">
            <center class="lead">Типы оплаты</center>
            <?=yii\amcharts\Widget::widget(['chartConfiguration' => array_merge($chartBasicConf, $chartsConfigurations['paymentType'])])?>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="thumbnail">
            <center class="lead">Продажи по категориям</center>
            <?=yii\amcharts\Widget::widget(['width' =>  '100%', 'chartConfiguration' => array_merge($chartBasicConf, $chartsConfigurations['byCategories'])])?>
        </div>
    </div>

</div>