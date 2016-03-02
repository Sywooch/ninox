<?php
use kartik\sortable\Sortable;
use kartik\dropdown\DropdownX;
use yii\helpers\Html;
use yii\helpers\Url;

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

$(".categoryActions .shutdown").on('click', function(e){
    changeCategoryState(e);
});

$(".categoryActions .canBuy").on('click', function(e){
    changeCategoryCanBuy(e);
});
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


$sf = \Yii::$app->request->get("smartfilter");

$items = [];
?>
    <h1>Категории<?php if(!empty($nowCategory)){ ?>&nbsp;<small><?=$nowCategory->Name?></small></h1>
    <ul class="nav nav-pills">
        <?=''/*Html::tag('li',
            Html::a('Всего товаров: '.Html::tag('span', ($goodsCount['all']['enabled'] + $goodsCount['all']['disabled']), ['class'=>'label label-info']), Url::toRoute(['/goods', 'category' => $nowCategory->Code, 'smartfilter' => ''])),
            [
                'role'      =>  'presentation',
                'class'     =>  $sf == '' ? 'active' : ''
            ])?>
        <?=Html::tag('li',
            Html::a('Выключено: '.Html::tag('span', ($goodsCount['all']['disabled']), ['class'=>'label label-danger']), Url::toRoute(['/goods', 'category' => $nowCategory->Code, 'smartfilter' => 'disabled'])),
            [
                'role'      =>  'presentation',
                'class'     =>  $sf == 'disabled' ? 'active' : ''
            ])?>
        <?=Html::tag('li',
            Html::a('Включено: '.Html::tag('span', ($goodsCount['all']['enabled']), ['class'=>'label label-success']), Url::toRoute(['/goods', 'category' => $nowCategory->Code, 'smartfilter' => 'enabled'])),
            [
                'role'      =>  'presentation',
                'class'     =>  $sf == 'enabled' ? 'active' : ''
            ])*/?>
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
                        'url'       =>  Url::toRoute('showcategory/'.$nowCategory->ID)
                    ],
                    [
                        'label'     =>  'Просмотреть на сайте',
                        'url'       =>  'https://krasota-style.com.ua/'.$nowCategory->link
                    ],
                    [
                        'label'     =>  'Редактировать',
                        'url'       =>  Url::toRoute(['showcategory/'.$nowCategory->ID, 'act' => 'edit'])
                    ],
                    [
                        'label'     =>  'Добавить',
                        'items'     =>  [
                            [
                                'label'     =>  'Товар',
                                'url'       =>  Url::toRoute(['addgood', 'category' => $nowCategory->ID])
                            ],
                            [
                                'label'     =>  'Несколько товаров',
                                'url'       =>  Url::toRoute(['addgood', 'category' => $nowCategory->ID, 'mode' => 'lot'])
                            ],
                            '<li class="divider"></li>',
                            [
                                'label'     =>  'Категорию',
                                'url'       =>  Url::to(['addcategory', 'category' => $nowCategory->ID])
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
foreach($categories->getModels() as $c){
    //$goodsCount['all']['enabled'] -= isset($goodsCount[$c->Code]['enabled']) ? $goodsCount[$c->Code]['enabled'] : 0;
    //$goodsCount['all']['disabled'] -= isset($goodsCount[$c->Code]['disabled']) ? $goodsCount[$c->Code]['disabled'] : 0;

    $items[] = [
        'content' =>  $this->render('_category_list_item', [
            'category'  =>  $c,
            'enabled'   =>  isset($goodsCount[$c->Code]['enabled']) ? $goodsCount[$c->Code]['enabled'] : 0,
            'disabled'  =>  isset($goodsCount[$c->Code]['disabled']) ? $goodsCount[$c->Code]['disabled'] : 0
        ]),
        'options' =>  [
            'class' =>  "category ".($c->menu_show == "1" ? "bg-success" : "bg-danger"),
            'data-category-id'    =>  $c->ID
        ]
    ];
};

/*if(!empty($nowCategory)){
    $nowItemText = 'Товары этой категории';
    $nowItemText .= ' (включеных: '.$goodsCount['all']['enabled'];
    $nowItemText .= ' выключеных: '.$goodsCount['all']['disabled'].')';

    $nowItem = [
        'content' => Html::a($nowItemText, Url::toRoute(['/goods', 'category' => $nowCategory->Code, 'onlyGoods' => 'true'])),
        'options' =>  [

        ],
        'disabled'  =>  true
    ];

    array_unshift($items, $nowItem);
}*/

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