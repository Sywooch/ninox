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
        $('.payment-param-' + index).removeAttr('disabled').data('commission-static', value.static).data('commission-percent', value.percent);
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
	var amount = parseFloat($('.amount').text().replace(/[^\d\.,]+/g, ''));
	var actionDiscount = parseFloat($('.action-discount-amount').text().replace(/[^\d\.,]+/g, ''));
	var cardDiscount = parseFloat($('.card-discount-amount').text().replace(/[^\d\.,]+/g, ''));
	var stc = parseFloat($(element).data('commission-static'));
	var pct = parseFloat($(element).data('commission-percent'));
	$('.commission-percent').toggleClass('disabled', !(pct > 0)).text(pct + '% ');
	$('.commission-static').toggleClass('disabled', !(stc > 0)).text('+ ' + stc);
	var commission = ((amount - actionDiscount - cardDiscount) / 100 * pct) + stc;
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

?>
<div class="content-data-body-delivery-type">
<?=$form->field($model, 'deliveryType', [
    ])->radioList($domainConfiguration['deliveryTypes'],[
		'unselect'  => null,
        'item' => function($index, $label, $name, $checked, $value) use (&$tabItems, $form, $model, $domainConfiguration){
	        $deliveryType = $value;
		    $subTabItems = [];
	        $tabItems[] = [
		        'content'   =>  $form->field($model, 'deliveryParam', (sizeof($label['params']) > 1 ? [] : (['options' => ['style' => 'display: none']]))
			        )->radioList($label['params'],[
				        'unselect'  => null,
				        'item' => function($index, $label, $name, $checked, $value) use (&$subTabItems, $form, $model, $deliveryType){
						        switch($label['content']){
							        case 'address':
								        $label['content'] = Html::tag('div',
									        $form->field($model, 'deliveryInfo['.$deliveryType.']['.$value.']')->label(\Yii::t('shop', 'Мои адреса:')),
									        ['class' => 'clear-fix content-data-body-'.$label['content']]);
								        break;
							        case 'department':
								        $label['content'] = Html::tag('div',
									        $form->field($model, 'deliveryInfo['.$deliveryType.']['.$value.']')->label(\Yii::t('shop', 'Отделение:')).
									        Html::tag('span',
										        \Yii::t('shop', 'См. на карте'), [
											        'id' => 'go-department',
											        'class' => 'map-icon'
										        ]
									        ),
									        ['class' => 'clear-fix content-data-body-'.$label['content']]);
								        break;
							        case 'stock':
								        $label['content'] = Html::tag('div',
									        Html::tag('div',
										        \Yii::t('shop', 'Наш склад находится по адресу:'),
										        ['class' => 'semi-bold']
									        ).
									        \Yii::t('shop', 'г. Киев, ул. Электротехническая, 2:').
									        Html::tag('span',
										        \Yii::t('shop', 'См. на карте'), [
											        'id' => 'go-stock',
											        'class' => 'map-icon'
										        ]
									        ).
									        Html::tag('div', \Yii::t('shop', 'Время работы с 9:00 до 17:00'), ['class' => 'work-time']).
									        Html::tag('div', \Yii::t('shop', 'все дни кроме понедельника'), ['class' => 'work-time']),
									        ['class' => 'content-data-body-'.$label['content']]);
								        break;
						        };
						        $subTabItems[] = [
							        'content'   =>  $label['content'],
							        'label'     =>  $label['name'],
							        'id'        =>  ''
						        ];
						        return Html::tag('div', Html::radio($name, $checked, [
							        'value'     =>      $value,
							        'id'        =>      'tab-'.Tabs::$counter.$index,
								    'data-payment-types'    =>  '["'.implode('", "', $label['paymentTypes']).'"]',
								    'data-payment-params'   =>  \yii\helpers\Json::encode($label['paymentParams'], JSON_FORCE_OBJECT)
						        ]).
						        Html::tag('label', $label['label'],[
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
	    'unselect'  => null,
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
		            $form->field($model, 'anotherReceiverPhone')->hint(\Yii::t('site', 'Телефон получателя, если он отличается от вашего!')),
		            ['class' => 'content-data-body-second']),
            'label'     =>  '',
            'id'        =>  '2'


        ]
    ]
])?>
</div>
<div class="payment-type">Способ оплаты</div>
<?=$form->field($model, 'paymentType')->radioList($domainConfiguration['paymentTypes'], [
	'unselect'  => null,
    'item' => function ($index, $label, $name, $checked, $value) use ($form, $model){
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
				'title'    =>  \Yii::t('shop', 'Эта сумма может измениться, в случае если вдруг не будет товаров на складе')

			]).
			$form->field($model, 'paymentParam', ['options' => ['class' => 'payment-type-'.$value], 'template' => Html::tag('div', '{input}{label}', (sizeof($label['params']) > 1 ? [] : ['class' => 'payment-params-none']))])->radioList($label['params'], [
				'unselect'  => null,
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