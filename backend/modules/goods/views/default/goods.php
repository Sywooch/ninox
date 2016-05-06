<?php
use kartik\dropdown\DropdownX;
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\Html;
use yii\widgets\ListView;

$this->title = "Товары";

$s = <<<'STYLE'
dt{
font-weight: bold;
float: left;
margin-right: 0.4em;
}

.bg-danger{
    background: #F2DEDE !important;
}

.bg-success{
    background: #DFF0D8 !important;
}

.bg-very-danger{
    background: #FFC5CC !important;
}

img.good-sale{
    border: 2px solid red;
    border-radius: 3px;
}
STYLE;

$js = <<<JS
$("#goodsList .item").goods();

var queryConvert = function(){
    var queryStr = window.location.search,
      queryArr = queryStr.replace('?','').split('&'),
      queryParams = [];

    for (var q = 0, qArrLength = queryArr.length; q < qArrLength; q++) {
        var qArr = queryArr[q].split('=');
        queryParams[qArr[0]] = qArr[1];
    }

    return queryParams;
}

$("body").on('click', ".changeViewBtn", function(){
    var urlVars = queryConvert(),
        params = '?';
    
    for(var i in urlVars){
        if(i == "view"){
            params += "view" + "=" + $(this).attr("data-view")
        }else{
            params += i + "=" + urlVars[i];
        }
        
        params += "&";
    }
    
    if($(this).attr("data-view").length != 0 && urlVars['view'].length == 0){
        params += "view" + "=" + $(this).attr("data-view")
    }
    
    console.log(params);
    
    $.pjax({url: "/goods" + params, container: '#goods-view-pjax'});
})
JS;

$this->registerJs($js);

$this->registerCss($s);

$sf = \Yii::$app->request->get("smartfilter");

$enabled = isset($goodsCount[$nowCategory->Code]['enabled']) ? $goodsCount[$nowCategory->Code]['enabled'] : 0;
$disabled = isset($goodsCount[$nowCategory->Code]['disabled']) ? $goodsCount[$nowCategory->Code]['disabled'] : 0;
?>
<?=Html::tag('h1', $this->title.(!empty($nowCategory) ? '&nbsp;'.Html::tag('small', $nowCategory->Name) : ''))?>
<ul class="nav nav-pills" style="margin-left: -15px;">
    <li role="presentation"><a href="/goods?category=<?=\Yii::$app->request->get("category")?>">Всего товаров: <span class="label label-info"><?=($enabled + $disabled)?></span></a></li>
    <li role="presentation" class="<?=$sf == 'enabled' ? 'active' : ''?>"><a href="/goods?category=<?=\Yii::$app->request->get("category")?>&smartfilter=enabled">включено: <span class="label label-success"><?=$enabled?></span></a></li>
    <li role="presentation" class="<?=$sf == 'disabled' ? 'active' : ''?>"><a href="/goods?category=<?=\Yii::$app->request->get("category")?>&smartfilter=disabled">выключено: <span class="label label-danger"><?=$disabled?></span></a></li>
</ul>
<div class="clearfix"></div>
<br style="margin-bottom: 0;">
<div class="dropdown">
    <div class="btn-group" role="group" aria-label="Действия">
        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-expanded="true">
            Действия с категорией <span class="caret"></span>
        </button>
        <?=DropdownX::widget([
            'options'   =>  [
                'class' =>  'categoryActions'
            ],
            'items' =>  [
                [
                    'label'     =>  'Просмотреть',
                    'url'       =>  \yii\helpers\Url::to(['/categories/view/'.$nowCategory->ID])
                ],
                [
                    'label'     =>  'Просмотреть на сайте',
                    'url'       =>  'https://krasota-style.com.ua/'.$nowCategory->link
                ],
                [
                    'label'     =>  'Редактировать',
                    'url'       =>  \yii\helpers\Url::to(['/categories/view/'.$nowCategory->ID, 'act' => 'edit'])
                ],
                [
                    'label'     =>  'Добавить',
                    'items'     =>  [
                        [
                            'label'     =>  'Товар',
                            'url'       =>  \yii\helpers\Url::to(['add', 'category' => $nowCategory->ID])
                        ],
                        [
                            'label'     =>  'Несколько товаров',
                            'url'       =>  \yii\helpers\Url::to(['add', 'category' => $nowCategory->ID, 'mode' => 'lot'])
                        ],
                        Html::tag('li', '', ['class' => 'divider']),
                        [
                            'label'     =>  'Категорию',
                            'url'       =>  \yii\helpers\Url::to(['/categories/add', 'category' => $nowCategory->ID])
                        ],
                    ]
                ],
                Html::tag('li', '', ['class' => 'divider']),
                [
                    'label' =>  $nowCategory->enabled == "1" ? "Выключить" : "Включить",
                    'options'   =>  [
                        'class' =>  'shutdown',
                        'data-attribute-categoryID' =>  $nowCategory->ID
                    ],
                    'url'   =>  '#'
                ],
                [
                    'label' =>  $nowCategory->canBuy == "1" ? "Не продавать" : "Продавать",
                    'options'   =>  [
                        'class' =>  'canBuy',
                        'data-attribute-categoryID' =>  $nowCategory->ID
                    ],
                    'url'   =>  '#'
                ]
            ]
        ])?>
    </div>
    <?=\Yii::$app->request->get('withSubcategories') ? '' : Html::a('Отобразить товары подкатегорий', \common\components\RequestHelper::createGetLink('withSubcategories', 'true'), ['class' => 'btn btn-default', 'style' => 'margin-left: 15px'])?>
</div>
<div class="row">
    <br><?php

    \yii\widgets\Pjax::begin([
        'id'    =>  'goods-view-pjax'
    ]);

    echo Html::tag('div',
        Html::button(FA::i('th').' '.\Yii::t('admin', 'сетка'), ['data-view' => 'grid', 'class' => 'btn btn-default changeViewBtn', (\Yii::$app->request->get("view") == '' || \Yii::$app->request->get("view") == 'grid' ? 'disabled' : '') => 'disabled']).
        Html::button(FA::i('list').' '.\Yii::t('admin', 'список'), ['data-view' => 'list', 'class' => 'btn btn-default changeViewBtn', (\Yii::$app->request->get("view") == 'list' ? 'disabled' : '') => 'disabled']),
        [
            'class' =>  'btn-group',
            'style' =>  'margin-left: 15px'
        ]
    );

    switch(\Yii::$app->request->get("view")){
        case 'list':
            echo Html::tag('div',
                \kartik\grid\GridView::widget([
                    'dataProvider'  =>  $goods,
                    'id'            =>  'goodsList',
                    'layout'        =>  Html::tag('div',
                        Html::tag('div', '{summary}', ['class' => 'col-xs-12', 'style' => 'margin-bottom: 10px']).
                        Html::tag('div', '{items}', ['class' => 'col-xs-12']).
                        Html::tag('div', '{pager}', ['class' => 'col-xs-12', 'align' => 'center']),
                        [
                            'class' =>  'row',
                            'style' =>  'margin-top: 20px;'
                        ]),
                    'summary'        =>  Html::tag('span', 'Показаны товары {begin}-{end}, всего товаров {totalCount}', ['style' => 'margin-left: 15px']),
                    'columns'       =>  [
                        [
                            'attribute' =>  'name'
                        ],
                        [
                            'attribute' =>  'wholesalePrice'
                        ],
                        [
                            'attribute' =>  'retailPrice'
                        ],
                        [
                            'attribute' =>  'BarCode2'
                        ],
                        [
                            'class'     =>  \kartik\grid\ActionColumn::className(),
                            'buttons'   =>  [
                                'changeState'   =>  function($key, $model){
                                    return Html::button(($model->enabled ? \Yii::t('admin', 'Включен') : \Yii::t('admin', 'Выключен')), ['class' => 'changeStateButton btn btn-'.($model->enabled ? 'success' : 'danger')]);
                                }
                            ],
                            'template'  =>  '{changeState}'
                        ],
                    ]
                ]),
                [
                    'class' =>  'col-xs-12'
                ]);
            break;
        case 'grid':
        default:
            echo ListView::widget([
                'dataProvider'  => $goods,
                'id'            =>  'goodsList',
                'itemOptions'   => [
                    'class' => 'item col-sm-4 col-md-3',
                    'style' =>  'min-height: 500px'
                ],
                'layout'        =>  Html::tag('div',
                    Html::tag('div', '{summary}', ['class' => 'col-xs-12', 'style' => 'margin-bottom: 10px']).
                    Html::tag('div', '{items}', ['class' => 'col-xs-12']).
                    Html::tag('div', '{pager}', ['class' => 'col-xs-12', 'align' => 'center']),
                    [
                        'class' =>  'row',
                        'style' =>  'margin-top: 20px;'
                    ]),
                'summary'        =>  Html::tag('span', 'Показаны товары {begin}-{end}, всего товаров {totalCount}'),
                'itemView'       =>  'goods/card',
            ]);
            break;
    }

    \yii\widgets\Pjax::end();
    ?>
</div>