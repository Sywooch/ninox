<?php
use bobroid\remodal\Remodal;
use kartik\grid\GridView;
use rmrevin\yii\fontawesome\FA;
$this->title = 'Отзывы';
$this->params['breadcrumbs'][] = $this->title;
$js = <<<'SCRIPT'
var changeState = function(e){
    var button = e.currentTarget,
        id = button.getAttribute('data-attribute-reviewID');

    $.ajax({
	    type: 'POST',
		url: '/feedback/changestate',
		data: {
		    'id': id
		},
		success: function(data){
			if(data == 1){
			    button.setAttribute('class', 'ReviewEdit btn btn-default btn-success priceRuleState');
			    button.innerHTML = '<i class="fa fa-eye"></i>';
			    button.parentNode.parentNode.setAttribute('class', 'alert alert-success');
			}else{
			    button.setAttribute('class', 'ReviewEdit btn btn-default btn-danger priceRuleState');
			    button.innerHTML = '<i class="fa fa-eye-slash"></i>';
			    button.parentNode.parentNode.setAttribute('class', 'alert alert-danger');
			}
		}
	});
}, updateRuleModal = function(e){
    $.ajax({
	    type: 'POST',
		url: '/feedback/edit',
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
		url: '/feedback/updatesort',
		data: {
		    'data': b
		}
	});
}
    buttons1 = document.querySelectorAll(".ReviewState");
    buttons2 = document.querySelectorAll(".ReviewEdit");

for(var i = 0; i < buttons1.length; i++){
    buttons1[i].addEventListener('click', changeState, false);
    buttons2[i].addEventListener('click', updateRuleModal, false);
}

SCRIPT;

$this->registerJs($js);

$ruleModal = new Remodal([
    'addRandomToID' =>  false,
    'id'            =>  'updateReview',
    'cancelButtonOptions'  =>  [
        'label' =>  'Отменить'
    ],
    'confirmButtonOptions'  =>  [
        'label' =>  'Сохранить'
    ],
]);

?>
    <h1>Отзывы</h1>
<?=Remodal::widget([
    'id'            =>  'updateReview',
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
    'content'   =>  $this->render('_Review_edit', [
        'Review'  =>  new \backend\models\Review()
    ])
])?>
<?php
$items = [];

foreach($reviews as $review){
    $items[] = [
        'content' =>  $this->render('_review_item', [
            'review'  =>  $review
        ]),
        'options' =>  [
            'class' =>  'alert alert-success'
        ]
    ];
}?>
<?=\kartik\sortable\Sortable::widget([
    'items' =>  $items,
    'options'   =>  [
        'id'  =>  'feedback'
    ],
    'pluginEvents' => [
        'sortupdate' => 'function() { updSort(); }',
    ]
])?>