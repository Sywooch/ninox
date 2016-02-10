<?php
use yii\bootstrap\Tabs;
use yii\helpers\Html;

$js = <<<'JS'
var deliveryType = function(element){
	$(element).next('.tabsLabels').tab('show');
	var id = $(element).next('.tabsLabels').tab().attr('data-target');
	var label = $(id + ' input:radio[name="OrderForm[deliveryParam]"]')[0];
	if(label){
		deliveryParam(label);
	}
}, deliveryParam = function(element){
	element.checked = true;
	$(element).next('.tabsLabels').tab('show');
    $('.payment-types').each(function(){
        $(this).attr('disabled', 'disabled');
    });
    $('.payment-params').each(function(){
        $(this).attr('disabled', 'disabled');
    });
    $.each($(element).data('payment-params'), function(index, value){
        $('.payment-param-' + index).removeAttr('disabled').data('commission-static', value.commissionStatic).data('commission-percent', value.commissionPercent);
	});
	$(element).data('payment-types').forEach(function(index, value){
		$('.payment-type-' + index).removeAttr('disabled');
		if(value == 0){
			var input = $('.payment-type-' + index)[0];
			if(input){
				paymentType(input);
			}
		}
	});
}, paymentType = function(element){
	element.checked = true;
	var input = $('.field-orderform-paymentparam.payment-type-' + $(element).val() + ' input:radio[name="OrderForm[paymentParam]"]:not(:disabled)')[0];
	if(input){
		paymentParam(input);
	}
}, paymentParam = function(element){
	element.checked = true;
	var amount = parseFloat($('.amount').text().replace(/\D+/, ''));
	var actionDiscount = parseFloat($('.action-discount-amount').text().replace(/\D+/, ''));
	var cardDiscount = parseFloat($('.card-discount-amount').text().replace(/\D+/, ''));
	var cst = parseFloat($(element).data('commission-static'));
	var cpc = parseFloat($(element).data('commission-percent'));
	$('.commission-percent').toggleClass('disabled', !(cpc > 0)).text(cpc + '% ');
	$('.commission-static').toggleClass('disabled', !(cst > 0)).text('+ ' + cst);
	var commission = ((amount - actionDiscount - cardDiscount) / 100 * cpc) + cst;
	$('.commission-amount').text('+' + commission.toFixed(2).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 '));
	$('.total-amount').text((amount - actionDiscount - cardDiscount + commission).toFixed(2).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 '));
};

$('input:radio[name="OrderForm[deliveryType]"]').on('click', function(e){
	deliveryType(e.currentTarget);
});

$('input:radio[name="OrderForm[deliveryParam]"]').on('click', function(e){
    deliveryParam(e.currentTarget);
});

$('input:radio[name="OrderForm[paymentType]"]').on('click', function(e){
	paymentType(e.currentTarget);
});

$('input:radio[name="OrderForm[paymentParam]"]').on('click', function(e){
	paymentParam(e.currentTarget);
});

$('.content-data-body-delivery-type input[type="radio"]:checked + label').tab('show');

$('input:radio[name="OrderForm[deliveryType]"]:checked').each(function(){
	var id = $(this).data('target');
	var input = $(id + ' input:radio[name="OrderForm[deliveryParam]"]:checked')[0];
	if(input){
		deliveryParam(input);
	}else{
		input = $(id + ' input:radio[name="OrderForm[deliveryParam]"]')[0];
		if(input){
			deliveryParam(input);
		}
	}
});

$('input:radio[name="OrderForm[paymentType]"]:checked').each(function(){
	var input = $('.field-orderform-paymentparam.payment-type-' + $(this).val() + ' input:radio[name="OrderForm[paymentParam]"]:checked')[0];
	if(!input){
		input = $('.field-orderform-paymentparam.payment-type-' + $(this).val() + ' input:radio[name="OrderForm[paymentParam]"]:not(:disabled)')[0];
		if(input){
			paymentType(input);
		}
	}
});

$('input:radio[name="OrderForm[anotherReceiver]"]').on('change', function(){
    $(this).next('.tabsLabels').tab('show');
});

$('input:radio[name="OrderForm[anotherReceiver]"]:checked + label').each(function(){
	var id = $(this).tab().attr('data-target');
	var input = $(id + ' input:radio[name="OrderForm[anotherReciver]"]:checked + label')[0];
	if(!input){
		input = $(id + ' input:radio[name="OrderForm[anotherReciver]"] + label')[0];
		if(input){
			input.click();
		}
	}
});
JS;
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
								        'id'        =>      'tab-'.Tabs::$counter.$index,
									    'data-payment-types'    =>  '["'.implode('", "', $label['paymentTypes']).'"]',
									    'data-payment-params'   =>  \yii\helpers\Json::encode($label['paymentParams'], JSON_FORCE_OBJECT)
							        ]).
							        Html::tag('label', !empty($label['options']->label) ? buildContent($label['options']->label) : $label['name'],[
								        'class' =>  'tabsLabels',
								        'data-target'   =>  '#w'.Tabs::$counter.'-tab'.$index,
								        'for'   =>  'tab-'.Tabs::$counter.$index,
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
		            'id'        =>      'tab-'.sizeof($domainConfiguration['deliveryTypes']).$index,
		            'data-target'   =>  '#w'.sizeof($domainConfiguration['deliveryTypes']).'-tab'.$index
	            ]).
	            Html::tag('label', $label['name'], [
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
        return Html::radio($name, $checked, [
	            'value'     =>  $value,
	            'id'        =>  'payment-type-'.$value,
	            'class'     =>  'payment-types payment-type-'.$value,
	        ]).
	        Html::tag('label', $label['name'], [
		        'for'   =>  'payment-type-'.$value
	        ]).
			Html::tag('span', '?', [
				'class'         =>  'question-round-button',
				'data-toggle'   =>  'tooltip',
				'data-title'    =>  \Yii::t('shop', 'Эта сумма может измениться, в случае если вдруг не будет товаров на складе')

			]).
			$form->field($model, 'paymentParam', ['options' => ['class' => 'payment-type-'.$value], 'template' => Html::tag('div', '{input}{label}', (sizeof($label['params']) > 1 ? [] : ['class' => 'payment-params-none']))])->radioList($label['params'], [
			    'item' => function ($index, $label, $name, $checked, $value){
					    return Html::radio($name, $checked, [
						    'value'     =>  $value,
						    'id'        =>  'payment-param-'.$value,
						    'class'     =>  'payment-params payment-param-'.$value,
					    ]).
					    Html::tag('label', $label['name'], [
						    'for'   =>  'payment-param-'.$value
					    ]);
				    }
		    ])->label(false);
    }
])->label(false);?>
<div class="add-comment">Добавить коментарий к заказу</div>