<?php
use yii\bootstrap\Tabs;
use yii\helpers\Html;

$js = <<<'SCRIPT'
$('input:radio').on('change', function(){
    $(this).next('.tabsLabels').tab('show');
    var id = $(this).next('.tabsLabels').tab().attr('data-target');
	var input = $(id + ' input:radio[name="OrderForm[deliveryParam]"] + label')[0];
	if(input){
		$(input).click();
	}
});

$('.content-data-body-delivery-type input[type="radio"]:checked + label').tab('show');

$('.content-data-body-delivery-type input:radio[name="OrderForm[deliveryType]"]:checked + label').each(function(){
	var id = $(this).tab().attr('data-target');
	var input = $(id + ' input:radio[name="OrderForm[deliveryParam]"]:checked + label')[0];
	if(!input){
		input = $(id + ' input:radio[name="OrderForm[deliveryParam]"] + label')[0];
		if(input){
			$(input).click();
		}
	}
});

$('.content-data-body-delivery-type input:radio[name="OrderForm[anotherReciver]"]:checked + label').each(function(){
	var id = $(this).tab().attr('data-target');
	var input = $(id + ' input:radio[name="OrderForm[anotherReciver]"]:checked + label')[0];
	if(!input){
		input = $(id + ' input:radio[name="OrderForm[anotherReciver]"] + label')[0];
		if(input){
			$(input).click();
		}
	}
});
SCRIPT;

$this->registerJs($js);

$tabItems = [];

function buildContent($blocks){
	$return = '';
	if(!empty($blocks)){
		foreach($blocks as $block){
			$return .= (is_object($block) && $block->tag ? Html::tag($block->tag, (is_object($block->content) || is_array($block->content) ? buildContent($block->content) : $block->content), $block->options) : (is_object($block) && $block->content ? $block->content : $block));
		}
	}
	return $return;
}
?>
<div class="content-data-body-delivery-type">
<?=$form->field($model, 'deliveryType', [
    ])->radioList($domainConfiguration['deliveryTypes'], [
        'item' => function($index, $label, $name, $checked, $value) use (&$tabItems, $form, $model, $domainConfiguration){
		    $subTabItems = [];
	        $tabItems[] = [
		        'content'   =>  $form->field($model, 'deliveryParam', (sizeof($label['params']) > 1 ? [] : (['options' => ['style' => 'display: none']]))
			        )->radioList($label['params'],[
					        'item' => function($index, $label, $name, $checked, $value) use (&$subTabItems){
							        $subTabItems[] = [
								        'content'   =>  buildContent($label['options']->block),
								        'label'     =>  $label['name'],
								        'id'        =>  ''
							        ];
							        return Html::tag('div', Html::radio($name, $checked, [
								        'value'     =>      $value,
								        'id'        =>      "tab-".Tabs::$counter.$index
							        ]).
							        Html::tag('label', !empty($label['options']->label) ? buildContent($label['options']->label) : $label['name'],[
								        'class' =>  'tabsLabels',
								        'data-target'   =>  '#w'.Tabs::$counter.'-tab'.$index,
								        'for'   =>  'tab-'.Tabs::$counter.$index
							        ]), ['class' =>  'tab']);
						        }
				        ])->label(false).
			        Tabs::widget([
				        'headerOptions' =>  [
					        'style' =>  'display: none'
				        ],
				        'items' =>  $subTabItems
			        ]),
		        'label'     =>  $label['name'],
		        'id'        =>  '',
		        'active'    =>  $checked
	        ];
            return Html::tag('div', Html::radio($name, $checked, [
		            'value'     =>      $value,
		            'id'        =>      "tab-".sizeof($domainConfiguration['deliveryTypes']).$index
	            ]).
	            Html::tag('label', (sizeof($label['params']) < 2 && $label['replaceDescription'] == 1 ? reset($label['params'])['name'] : $label['name']), [
		            'class' =>  'tabsLabels',
		            'data-target'   =>  '#w'.sizeof($domainConfiguration['deliveryTypes']).'-tab'.$index,
		            'for'      =>   'tab-'.sizeof($domainConfiguration['deliveryTypes']).$index
	            ]), ['class' =>  'tab']);
        }
    ])->label(false).
    Tabs::widget([
        'headerOptions' =>  [
            'style' =>  'display: none'
        ],
        'items' =>  $tabItems
    ])?>
</div>

<div class="content-data-body-delivery-type">
<?=$form->field($model, 'anotherReceiver')->radioList([
    '0' =>  'Отправлять на меня',
    '1' =>  'Будет получать другой человек',
    ],
    [
        'item'  =>  function ($index, $label, $name, $checked, $value) {
                return Html::radio($name, $checked, [
                        'value' => $value,
                        'id' => 'tab-'.Tabs::$counter.$index
                    ]).
                    Html::tag('label', $label, [
	                    'class'         =>  'tabsLabels',
	                    'data-target'   =>  '#w'.Tabs::$counter.'-tab'.$index,
	                    'for'           =>  'tab-'.Tabs::$counter.$index
                    ]);
        }
    ]
)->label(false).
Tabs::widget([
    'headerOptions' =>  [
        'style' =>  'display: none'
    ],
    'items' =>  [
        [
            'content'   =>  '',
            'label'     =>  '',
            'id'        =>  '1',
            'active' => true
        ],
        [
            'content'   =>  Html::tag('div', $form->field($model, 'anotherReceiverName').
		            $form->field($model, 'anotherReceiverSurname').
		            $form->field($model, 'anotherReceiverPhone'),
		            ['class' => 'content-data-body-first']),
            'label'     =>  '',
            'id'        =>  '2'


        ]
    ]
])?>
</div>
<div class="payment-type">Способ оплаты</div>
<?=$form->field($model, 'paymentType', [])->radioList($domainConfiguration['paymentTypes'], [
    'item' => function ($index, $label, $name, $checked, $value) use ($form, $model, $domainConfiguration){
        return Html::tag('div', Html::radio($name, $checked, [
            'value'     =>      $value,
            'id'        =>      "tab-".Tabs::$counter.$index
        ]).
        Html::tag('label', $label['name'], [
	        'for'   =>  'tab-'.Tabs::$counter.$index
        ]).
		Html::tag('span', '?', [
			'class'         =>  'question-round-button',
			'data-toggle'   =>  'tooltip',
			'data-title'    =>  \Yii::t('shop', 'Эта сумма может измениться, в случае если вдруг не будет товаров на складе')

		]), [
			    'class' =>  'tab'
		    ]);
    }
])->label(false);?>
<div class="add-comment">Добавить коментарий к заказу</div>