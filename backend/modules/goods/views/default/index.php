<?php
use kartik\sortable\Sortable;
use kartik\dropdown\DropdownX;

$this->title = "Категории";

if(!empty($nowCategory)){
    $this->title = 'Товары категории "'.$nowCategory->Name.'"';
}

$js = <<<'SCRIPT'

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function updSort(){
    var a = document.querySelectorAll("#goods-categories li.category"),
        b = new Array();


    for(var i = 0; i < a.length; i++){
        b.push(a[i].getAttribute("data-category-id"));
    }

    $.ajax({
		type: 'POST',
		url: '/goods/updatecategorysort',
		data: {
		    'data': b,
		    'category': getParameterByName('category')
		}
	});
}

var a = document.querySelectorAll(".categoryActions .shutdown");

for(var i = 0; i < a.length; i++){
    a[i].addEventListener('click', function(e){
        changeCategoryState(e);
    }, false);
}

a = document.querySelectorAll(".categoryActions .canBuy");

for(var i = 0; i < a.length; i++){
    a[i].addEventListener('click', function(e){
        changeCategoryCanBuy(e);
    }, false);
}

function changeCategoryState(e){
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
}

function changeCategoryCanBuy(e){
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

SCRIPT;

$css = <<<'STYLE'
.dropdown li{
    border: none;
    list-style: none;
    margin: 0;
    padding: 0;
}
STYLE;


$this->registerCss($css);
$this->registerJs($js);

if(!empty($nowCategory)){
  $this->params['breadcrumbs'][] = [
      'label' =>  'Категории',
      'url'   =>  '/goods'.(\Yii::$app->request->get("smartfilter") != '' ? '?smartfilter='.\Yii::$app->request->get("smartfilter") : '')
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

$items = [];
?>
<h1>Категории<?php if(!empty($nowCategory)){ ?>&nbsp;<small><?=$nowCategory->Name?></small> <?php
        $items[] = [
        'content' => \yii\helpers\Html::a('Товары этой категории', \yii\helpers\Url::toRoute(['/goods', 'category' => $nowCategory->Code, 'onlyGoods' => 'true'])),
        'options' =>  [

        ],
        'disabled'  =>  true
    ];
    $sf = \Yii::$app->request->get("smartfilter");
    ?></h1>
    <ul class="nav nav-pills" style="margin-left: -15px;">
        <li role="presentation"><a href="/admin/goods?category=<?=\Yii::$app->request->get("category")?>">Всего товаров: <span class="label label-info"><?=($goodsCount['all']['enabled'] + $goodsCount['all']['disabled'])?></span></a></li>
        <li role="presentation" class="<?=$sf == 'enabled' ? 'active' : ''?>"><a href="/admin/goods?category=<?=\Yii::$app->request->get("category")?>&smartfilter=enabled">включено: <span class="label label-success"><?=$goodsCount['all']['enabled']?></span></a></li>
        <li role="presentation" class="<?=$sf == 'disabled' ? 'active' : ''?>"><a href="/admin/goods?category=<?=\Yii::$app->request->get("category")?>&smartfilter=disabled">выключено: <span class="label label-danger"><?=$goodsCount['all']['disabled']?></span></a></li>
    </ul>
    <br style="margin-bottom: 0;">
    <div class="clearfix"></div>
    <div class="dropdown categoryActions">
        <div class="btn-group" role="group" aria-label="Действия">
            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-expanded="true">
                Действия с категорией <span class="caret"></span>
            </button>
            <?=DropdownX::widget([
                'items' =>  [
                    [
                        'label'     =>  'Просмотреть',
                        'url'       =>  '/admin/goods/showcategory/'.$nowCategory->ID
                    ],
                    [
                        'label'     =>  'Просмотреть на сайте',
                        'url'       =>  'https://krasota-style.com.ua/'.$nowCategory->link
                    ],
                    [
                        'label'     =>  'Редактировать',
                        'url'       =>  '/admin/goods/showcategory/'.$nowCategory->ID.'?act=edit'
                    ],
                    [
                        'label'     =>  'Добавить',
                        'items'     =>  [
                            [
                                'label'     =>  'Товар',
                                'url'       =>  '/admin/goods/addgood?category='.$nowCategory->ID
                            ],
                            [
                                'label'     =>  'Несколько товаров',
                                'url'       =>  '/admin/goods/addgood?category='.$nowCategory->ID.'?mode=lot'
                            ],
                            '<li class="divider"></li>',
                            [
                                'label'     =>  'Категорию',
                                'url'       =>  '/admin/goods/addcategory?category='.$nowCategory->ID
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
    <br>
<?php }else{
    ?>
    </h1>
    <?php
}
foreach($categories as $c){
    $enabled = isset($goodsCount[$c->Code]['enabled']) ? $goodsCount[$c->Code]['enabled'] : 0;
    $disabled = isset($goodsCount[$c->Code]['disabled']) ? $goodsCount[$c->Code]['disabled'] : 0;
  $items[] = [
    'content' =>  '<a href="/admin/goods?category='.$c->Code.(\Yii::$app->request->get("smartfilter") != '' ? '&smartfilter='.\Yii::$app->request->get("smartfilter") : '').'">'.$c->Name.' (включеных: '.$enabled.', выключеных: '.$disabled.')</a>
    <div class="dropdown pull-right" style="margin-left: 5px; margin-top: -8px; display: inline-block;">
    <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-expanded="true">
        <span class="glyphicon glyphicon-option-horizontal large"></span>
        <span class="caret"></span>
    </button>
    '.DropdownX::widget([
            'options'   =>  [
                'class' =>  'categoryActions'
            ],
            'items' =>  [
                [
                    'label'     =>  'Просмотреть',
                    'url'       =>  '/admin/goods/showcategory/'.$c->ID
                ],
                [
                    'label'     =>  'Просмотреть на сайте',
                    'url'       =>  'https://krasota-style.com.ua/'.$c->link
                ],
                [
                    'label'     =>  'Редактировать',
                    'url'       =>  '/admin/goods/showcategory/'.$c->ID.'?act=edit'
                ],
                [
                    'label'     =>  'Добавить',
                    'items'     =>  [
                        [
                            'label'     =>  'Товар',
                            'url'       =>  '/admin/goods/addgood?category='.$c->ID
                        ],
                        [
                            'label'     =>  'Несколько товаров',
                            'url'       =>  '/admin/goods/addgood?category='.$c->ID.'?mode=lot'
                        ],
                        '<li class="divider"></li>',
                        [
                            'label'     =>  'Категорию',
                            'url'       =>  '/admin/goods/addcategory?category='.$c->ID
                        ],
                    ],
                ],
                '<li class="divider"></li>',
                [
                    'label' =>  $c->menu_show == "1" ? "Выключить" : "Включить",
                    'options'   =>  [
                        'class' =>  'shutdown',
                        'data-attribute-categoryID' =>  $c->ID
                    ],
                    'url'   =>  '#'
                ],
                [
                    'label' =>  $c->canBuy == "1" ? "Не продавать" : "Продавать",
                    'options'   =>  [
                        'class' =>  'canBuy',
                        'data-attribute-categoryID' =>  $c->ID
                    ],
                    'url'   =>  '#'
                ]

            ]
        ]).'
    </div>
    <span class="pull-right"><a class="glyphicon glyphicon-pencil" href="/admin/goods/showcategory/'.$c->ID.'?act=edit"></a></span>',
    'options' =>  [
      'class' =>  "category ".($c->menu_show == "1" ? "bg-success" : "bg-danger"),
      'data-category-id'    =>  $c->ID
    ]
  ];
};

echo Sortable::widget([
  'showHandle'  =>  true,
  'options'   =>  [
      'id'  =>  'goods-categories'
  ],
  'items' =>  $items,
  'pluginEvents' => [
        'sortupdate' => 'function() { updSort(); }',
    ]
]);
?>