<?php
use kartik\sortable\Sortable;
use kartik\dropdown\DropdownX;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @param $nowCategory \backend\models\Category
 */

$this->title = "Категории";

if(!$nowCategory->isNewRecord){
    $this->title = 'Дочерние категории раздела "'.$nowCategory->Name.'"';
}

$js = <<<'JS'
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
		url: '/categories/updateorder',
		data: {
		    'data': b,
		    'category': getParameterByName('category')
		}
	});
}

function changeCategoryState(e){
    $.ajax({
		type: 'POST',
		url: '/categories/changecategorystate',
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
JS;

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

$items = [];


echo Html::tag('h1', 'Категории '.(!$nowCategory->isNewRecord ? Html::tag('small', $nowCategory->Name) : '')),
\backend\widgets\SmartFiltersWidget::widget([
    'items' =>  [
        [
            'label'         =>  'Всего товаров: ',
            'counterValue'  =>  $goodsCount['all']['enabled'] + $goodsCount['all']['disabled'],
            'labelClass'    =>  'label-info',
            'filter'        =>  ''
        ],
        [
            'label'         =>  'Отключеных: ',
            'counterValue'  =>  $goodsCount['all']['disabled'],
            'labelClass'    =>  'label-danger',
            'filter'        =>  'disabled'
        ],
        [
            'label'         =>  'Включеных: ',
            'counterValue'  =>  $goodsCount['all']['enabled'],
            'labelClass'    =>  'label-success',
            'filter'        =>  'enabled'
        ],
        [
            'label'         =>  'Другие',
            'labelClass'    =>  'label-success',
            'items'         =>  [
                [
                    'label'         =>  'Без фотографий',
                    'filter'        =>  'withoutPhoto'
                ],
                [
                    'label'         =>  'Без аттрибутов',
                    'filter'        =>  'withoutAttributes'
                ],
                [
                    'label'         =>  'На распродаже',
                    'filter'        =>  'onSale'
                ],
            ]
        ],
    ]
]),
Html::tag('br', '', ['style' => '']),
Html::tag('div', '', ['class' => 'clearfix']);

if(!$nowCategory->isNewRecord){
    echo $this->render('_category_actions', [
        'nowCategory'   =>  $nowCategory,
        'goodsCount'    =>  $goodsCount
    ]);
}


foreach($categories->getModels() as $child){
    $goodsCount['all']['enabled'] -= isset($goodsCount[$child->Code]['enabled']) ? $goodsCount[$child->Code]['enabled'] : 0;
    $goodsCount['all']['disabled'] -= isset($goodsCount[$child->Code]['disabled']) ? $goodsCount[$child->Code]['disabled'] : 0;

    $items[] = [
        'content' =>  $this->render('_category_list_item', [
            'category'  =>  $child,
            'enabled'   =>  isset($goodsCount[$child->Code]['enabled']) ? $goodsCount[$child->Code]['enabled'] : 0,
            'disabled'  =>  isset($goodsCount[$child->Code]['disabled']) ? $goodsCount[$child->Code]['disabled'] : 0
        ]),
        'options' =>  [
            'class' =>  "category ".($child->menu_show == "1" ? "bg-success" : "bg-danger"),
            'data-category-id'    =>  $child->ID
        ]
    ];
};

if(!$nowCategory->isNewRecord){
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
}

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