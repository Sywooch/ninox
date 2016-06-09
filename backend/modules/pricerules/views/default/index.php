<?php
use bobroid\remodal\Remodal;
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\Html;

$this->title = 'Ценовые правила';
$this->params['breadcrumbs'][] = $this->title;

\kartik\select2\Select2Asset::register($this);
\kartik\depdrop\DepDropAsset::register($this);
\kartik\date\DatePickerAsset::register($this);

$js = <<<'JS'

function loadList(list, data){
    var terms = data.rule.terms;
    list.children().remove();

    currentId = 1;
    categoriesDropdown = data.categoriesDropdown;

    for(var key in terms){
        if(terms.hasOwnProperty(key)){
            var term = terms[key];
            for(var keyId in term){
                if(term.hasOwnProperty(keyId)){
                    var subTerm = term[keyId];
                    for(var subKeyId in subTerm){
                        if(subTerm.hasOwnProperty(subKeyId)){
                            var li = document.createElement('li'),
                            selectTerm = document.createElement('select'),
                            selectOperand = document.createElement('select');
                            selectTerm.setAttribute('name', 'term[' + key + '][' + keyId + '][' + subKeyId + ']');
                            selectOperand.setAttribute('name', 'operand[' + key + '][' + keyId + '][' + subKeyId + ']');
                            selectTerm.setAttribute('id', 'term_' + currentId);
                            selectOperand.setAttribute('id', 'operand_' + currentId);
                            for(var operand in data.typesDropdown[key]){
                                var option = document.createElement('option');
                                option.setAttribute('value', operand);
                                operand == subTerm[subKeyId]['type'] ? option.setAttribute('selected', 'selected') : '';
                                option.textContent = operand;
                                selectOperand.appendChild(option);
                            }
                            li.appendChild(selectTerm);
                            li.appendChild(selectOperand);
                            list.append(li);
                            inputValueCreate($(li), key, keyId, subKeyId, subTerm[subKeyId]['term']);
                            $(selectTerm).select2({
                                "data": data.termsDropdown,
                            }).on("select2:selecting", function(e){
                                inputValueCreate($(e.currentTarget).parent('li'), e.params.args.data.id, false);
                            }).val(key).trigger('change');
                            $(selectOperand).depdrop({
                                "depends":[
                                    'term_' + currentId,
                                ],
                                "url":'/pricerules/getoperands'
                            });
                            currentId++;
                        }
                    }
                }
            }
        }
    }
}

function inputValueCreate(li, key, keyId, subKeyId, value){
    var input = li.find('.term-value');
    if(typeof input == "object"){
        if($(input).data('select2')){
            input.select2('destroy');
        }
        input.remove();
        input = '';
    }
    switch(key){
        case 'DocumentSum':
            input = document.createElement('input');
            input.setAttribute('type', 'text');
            input.setAttribute('id', 'value_' + currentId);
            input.setAttribute('class', 'term-value');
            input.setAttribute('name', 'value[' + key + '][' + keyId + '][' + subKeyId + ']');
            input.value = value;
            li.append(input);
            //input.setAttribute('type', 'text');
            //input.setAttribute('type', 'text');
            break;
        case 'GoodGroup':
            input = document.createElement('select');
            input.setAttribute('id', 'value_' + currentId);
            input.setAttribute('class', 'term-value');
            input.setAttribute('name', 'value[' + key + '][' + keyId + '][' + subKeyId + ']');
            li.append(input);
            $(input).select2({
                data: categoriesDropdown,
                width: "200px"
            }).val(value).trigger('change');
            break;
        case 'WithoutBlyamba':
            break;
        case 'Date':
            input = document.createElement('input');
            input.setAttribute('type', 'text');
            input.setAttribute('id', 'value_' + currentId);
            input.setAttribute('class', 'term-value');
            input.setAttribute('name', 'value[' + key + '][' + keyId + '][' + subKeyId + ']');
            input.value = value;
            li.append(input);
            $(input).kvDatepicker({
                format: 'dd.mm.yyyy',
                autoclose: true,
            });
            break;
    }
}

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
		    var list = $('.ruleEditForm #ruleTermsList');
			loadList(list, data);
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
}

$('body').on('click', '.priceRuleState', changeState);
$('body').on('click', '.priceRuleEdit', updateRuleModal);

$(document).on('confirmation', '.remodal[data-remodal-id="updateRule"]', function(e){
    var form = $(e.currentTarget).find('form');
    if(form){
        form.submit();
    }
});

JS;

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
    'content'   =>  $this->render('_rule_edit', [
        'rule'  =>  new \backend\models\Pricerule()
    ])
]);

?>
<h1><?=$this->title?></h1>
<br>
<?=Html::a(FA::icon('plus').' Добавить', '#updateRule', [
    'class' =>  'priceRuleEdit btn btn-default',
    'data-attribute-ruleID' =>  0,
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
        'sortupdate' => 'function(){updSort();}',
    ]
]).
$ruleModal->renderModal()?>