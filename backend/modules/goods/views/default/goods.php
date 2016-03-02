<?php
use kartik\dropdown\DropdownX;
use yii\widgets\ListView;

$this->title = "Товары";
if(!empty($nowCategory)){
    $this->params['breadcrumbs'][] = [
        'label' =>  'Категории',
        'url'   =>  '/goods'
    ];
}else{
    $this->params['breadcrumbs'][] = $this->title;
}

foreach($breadcrumbs as $b){
    $this->params['breadcrumbs'][] = $b;
}

if(!empty($nowCategory)){
    $this->params['breadcrumbs'][] = $nowCategory->Name;
}

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

$js = <<<'SCRIPT'
var good = {
    'changeState': function(e){
        var target = e.currentTarget;
        $.ajax({
            type: 'POST',
            url: '/goods/changestate',
            data: {
                'GoodID': e.currentTarget.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.getAttribute("data-value-goodID")
            },
            success: function(data){
                if(data.length >= "1"){
                    target.innerHTML = data == "1" ? "Отключить" : "Включить";
                    if(document.querySelector("#good-show_img") != null && document.querySelector("#good-show_img") != undefined){
                        var el = document.querySelector("#good-show_img");
                        el.querySelector("input[value='" + data + "']").checked = true;
                    }
                    if(target.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode !== null){
                        target = target.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode;
                        if(target.getAttribute('class').replace(/(\s+)(bg-.*)/, '') == 'thumbnail'){
                            if(target.getAttribute('data-attribute-deleted') !== null){
                                target.setAttribute('class', "thumbnail bg-very-danger");
                            }else{
                                target.setAttribute('class', data == "1" ? "thumbnail bg-success" : "thumbnail bg-danger");
                            }
                        }
                    }
                }
            }
        });
    },
    'changeTrashState': function(e){
        var target = e.currentTarget;
        $.ajax({
            type: 'POST',
            url: '/goods/workwithtrash',
            data: {
                'GoodID': e.currentTarget.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.getAttribute("data-value-goodID")
            },
            success: function(data){
                if(data.length >= "1"){
                    target.innerHTML = data == "1" ? "Восстановить" : "Удалить";
                }
            }
        });
    }
}

var changeCategoryState = function(e){
    $.ajax({
		type: 'POST',
		url: '/goods/changecategorystate',
		data: {
		    'category': e.target.parentNode.getAttribute("data-attribute-categoryID")
		},
		success: function(data){
			e.target.innerHTML = data == 1 ? "Выключить" : "Включить";

			if(e.target.parentNode.parentNode.parentNode !== null){
			    if(e.target.parentNode.parentNode.parentNode.parentNode.getAttribute('class').replace(/(\s+)(bg-.*)/, '') == 'category'){
			       e.target.parentNode.parentNode.parentNode.parentNode.setAttribute('class', data == 1 ? 'category bg-success' : 'category bg-danger');
			    }
			}
		}
	});
}, changeCategoryCanBuy = function(e){
    $.ajax({
		type: 'POST',
		url: '/goods/changecategorycanbuy',
		data: {
		    'category': e.target.parentNode.getAttribute("data-attribute-categoryID")
		},
		success: function(data){
			e.target.innerHTML = data == 1 ? "Не продавать" : "Продавать";
		}
	});
}

$(".changeState-btn").on('click', function(e){
    good.changeState(e);
});

$(".delete-btn").on('click', function(e){
    good.changeTrashState(e);
});

$(".categoryActions .canBuy").on('click', function(e){
    changeCategoryCanBuy(e);
});

$(".categoryActions .shutdown").on('click', function(e){
    changeCategoryState(e);
});

SCRIPT;

$this->registerJs($js);

$this->registerCss($s);

$sf = \Yii::$app->request->get("smartfilter");
$enabled = isset($goodsCount[$nowCategory->Code]['enabled']) ? $goodsCount[$nowCategory->Code]['enabled'] : 0;
$disabled = isset($goodsCount[$nowCategory->Code]['disabled']) ? $goodsCount[$nowCategory->Code]['disabled'] : 0;
?>
<h1><?=$this->title?><?php if(!empty($nowCategory)){ ?>&nbsp;<small><?=$nowCategory->Name?></small><?php } ?></h1>
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
                    'url'       =>  '/goods/showcategory/'.$nowCategory->ID
                ],
                [
                    'label'     =>  'Просмотреть на сайте',
                    'url'       =>  'https://krasota-style.com.ua/'.$nowCategory->link
                ],
                [
                    'label'     =>  'Редактировать',
                    'url'       =>  '/goods/showcategory/'.$nowCategory->ID.'?act=edit'
                ],
                [
                    'label'     =>  'Добавить',
                    'items'     =>  [
                        [
                            'label'     =>  'Товар',
                            'url'       =>  '/goods/addgood?category='.$nowCategory->ID
                        ],
                        [
                            'label'     =>  'Несколько товаров',
                            'url'       =>  '/goods/addgood?category='.$nowCategory->ID.'?mode=lot'
                        ],
                        '<li class="divider"></li>',
                        [
                            'label'     =>  'Категорию',
                            'url'       =>  '/goods/addcategory?category='.$nowCategory->ID
                        ],
                    ]
                ],
                '<li class="divider"></li>',
                [
                    'label' =>  $nowCategory->menu_show == "1" ? "Выключить" : "Включить",
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
</div>
<div class="row">
    <br>
    <?php
    \yii\widgets\Pjax::begin([

    ]);

    echo ListView::widget([
       'dataProvider'  => $goods,
       'itemOptions'   => [
           'class' => 'item'
       ],
       'layout'        =>  '<div class="row"><div class="col-xs-12">{summary}</div><div class="col-xs-12">{items}</div><div class="col-xs-12"><center>{pager}</center></div></div>',
       'summary'       =>  '<span style="margin-left: 15px">Показаны товары {begin}-{end}, всего товаров {totalCount}</span><br><br>',
       'itemView'  => '_goods_oneItem',
    ]);

    \yii\widgets\Pjax::end();
    ?>
</div>