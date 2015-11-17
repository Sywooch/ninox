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
			}else{
			    button.setAttribute('class', 'btn btn-default btn-danger priceRuleState');
			    button.innerHTML = '<i class="fa fa-eye-slash"></i>';
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
},  buttons1 = document.querySelectorAll(".priceRuleState"),
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
<?=\kartik\grid\GridView::widget([
    'dataProvider'  =>  $dataProvider,
    'summary'       =>  '',
    'condensed'     =>  true,
    'bordered'      =>  false,
    'hover'         =>  true,
    'resizableColumns'=>false,
    'columns'       =>  [
        [
            'attribute' =>  'ID',
            'options'   =>  [
                'style' =>  'border-right: 1px solid #ddd'
            ]
        ],[
            'attribute' =>  'Name',
        ],[
            'attribute' =>  'Formula',
        ],[
            'class'     =>  \kartik\grid\EditableColumn::className(),
            'attribute' =>  'Priority',
        ],[
            'class'     =>  \kartik\grid\ActionColumn::className(),
            'buttons'   =>  [
                'update'    =>  function($a, $model){
                    return Html::a(FA::icon('pencil'), '#updateRule', [
                        'class' =>  'priceRuleEdit btn btn-default',
                        'data-attribute-ruleID' =>  $model->ID,
                    ]);
                },
                'disable'    =>  function($a, $model){
                    return Html::button(FA::icon($model->Enabled == 1 ? 'eye' : 'eye-slash'), [
                        'class'                 =>  'priceRuleState btn btn-default'.($model->Enabled != 1 ? ' btn-danger' : ' btn-success'),
                        'data-attribute-ruleID' =>  $model->ID
                    ]);
                },
            ],
            'template'  =>  Html::tag('div', '{disable}{update}', [
                'class'  => 'btn-group btn-group-sm'
            ])
        ],
    ]
])?>

<?=$ruleModal->renderModal()?>