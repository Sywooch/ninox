<?php
use bobroid\remodal\Remodal;
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\Html;

$this->title = 'Ценовые правила';
$this->params['breadcrumbs'][] = $this->title;

$js = <<<'SCRIPT'
var changeState = function(e){
    var button = e.currentTarget,
        id = button.getAttribute('data-attribute-ruleID');

    $.ajax({
	    type: 'POST',
		url: '/pricerules/changestate',
		data: {
		    'id': id
		},
		success: function(data){
			if(data == 1){
			    button.setAttribute('class', 'btn btn-default btn-success priceRuleState');
			    button.innerHTML = '<i class="fa fa-eye"></i>';
			    button.parentNode.parentNode.setAttribute('class', 'alert alert-success');
			}else{
			    button.setAttribute('class', 'btn btn-default btn-danger priceRuleState');
			    button.innerHTML = '<i class="fa fa-eye-slash"></i>';
			    button.parentNode.parentNode.setAttribute('class', 'alert alert-danger');
			}
		}
	});
}, updateRuleModal = function(e){
    $.ajax({
	    type: 'POST',
		url: '/pricerules/edit',
		data: {
		    'id': e.currentTarget.getAttribute('data-attribute-ruleid')
		},
		success: function(data){
			document.querySelector("div[data-remodal-id='updateRule']").innerHTML = data;
		}
	});
}, updSort = function(){
    var a = document.querySelectorAll("#pricerules li"),
        b = new Array();


    for(var i = 0; i < a.length; i++){
        b.push(a[i].getAttribute("data-attribute-ruleID"));
    }

    console.log(b);

    $.ajax({
		type: 'POST',
		url: '/pricerules/updatesort',
		data: {
		    'data': b
		}
	});
},
    buttons1 = document.querySelectorAll(".priceRuleState"),
    buttons2 = document.querySelectorAll(".priceRuleEdit");

for(var i = 0; i < buttons1.length; i++){
    buttons1[i].addEventListener('click', changeState, false);
    buttons2[i].addEventListener('click', updateRuleModal, false);
}

SCRIPT;

$this->registerJs($js);

$ruleModal = new Remodal([
    'addRandomToID' =>  false,
    'id'            =>  'updateRule',
    'cancelButtonOptions'  =>  [
        'label' =>  'Отменить'
    ],
    'confirmButtonOptions'  =>  [
        'label' =>  'Сохранить'
    ],
]);

?>
<h1><?=$this->title?></h1>
<br>
<?=Remodal::widget([
    'id'            =>  'newPriceRule',
    'buttonOptions' =>  [
        'label' =>  FA::icon('plus').' Добавить',
        'class' =>  'btn btn-default'
    ],
    'cancelButtonOptions'  =>  [
        'label' =>  'Отменить'
    ],
    'confirmButtonOptions'  =>  [
        'label' =>  'Сохранить'
    ],
    'content'   =>  $this->render('_rule_edit', [
        'rule'  =>  new \backend\models\Pricerule()
    ])
])?>
<br>
<br>
<?php
$items = [];

foreach($rules as $rule){
    $items[] = [
        'content' =>  $this->render('_rule_item', [
            'rule'  =>  $rule
        ]),
        'options' =>  [
            'data-attribute-ruleID'    =>  $rule->ID,
            'class' =>  $rule->Enabled != 1 ? 'alert alert-danger' : 'alert alert-success'
        ]
    ];
}?>
<?=\kartik\sortable\Sortable::widget([
    'items' =>  $items,
    'options'   =>  [
        'id'  =>  'pricerules'
    ],
    'pluginEvents' => [
        'sortupdate' => 'function() { updSort(); }',
    ]
])?>