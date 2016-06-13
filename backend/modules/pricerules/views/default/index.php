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

    categoriesDropdown = data.categoriesDropdown;

    for(var key in terms){
        if(terms.hasOwnProperty(key)){
            var object = terms[key];
            var li = document.createElement('li'),
            selectTerm = document.createElement('select'),
            selectOperand = document.createElement('select'),
            buttonRemove = document.createElement('button'),
            iRemove = document.createElement('i');
            selectTerm.setAttribute('name', 'priceRuleTerms[' + key + '][term]');
            selectTerm.setAttribute('name_format', 'priceRuleTerms[%d][term]');
            selectTerm.setAttribute('class', 'ruleTerm');
            selectOperand.setAttribute('name', 'priceRuleTerms[' + key + '][type]');
            selectOperand.setAttribute('name_format', 'priceRuleTerms[%d][type]');
            selectOperand.setAttribute('class', 'ruleOperand');
            selectTerm.setAttribute('id', 'ruleTerm_' + key);
            selectOperand.setAttribute('id', 'ruleOperand_' + key);
            buttonRemove.setAttribute('class', 'btn btn-default btn-danger ruleTermsList_del');
            buttonRemove.setAttribute('type', 'button');
            iRemove.setAttribute('class', 'fa fa-remove');
            buttonRemove.appendChild(iRemove);
            for(var term in data.termsDropdown){
                var option = document.createElement('option');
                option.value = data.termsDropdown[term].id;
                data.termsDropdown[term].id == object.term ? option.setAttribute('selected', 'selected') : '';
                option.textContent = data.termsDropdown[term].text;
                selectTerm.appendChild(option);
            }
            for(var operand in data.typesDropdown[object.term]){
                var option = document.createElement('option');
                option.value = operand;
                operand == object.type ? option.setAttribute('selected', 'selected') : '';
                option.textContent = operand;
                selectOperand.appendChild(option);
            }
            li.setAttribute('class', 'ruleTermsList_var');
            li.appendChild(selectTerm);
            li.appendChild(selectOperand);
            li.appendChild(buttonRemove);
            list.append(li);
            inputValueCreate($(li), object.term, object.value, key, false);

        }
    }

    $('input[name="ruleName"]').val(data.name);
    $('input[name="priceRuleActions[Discount]"]').val(data.rule.actions.Discount);
    $('select[name="priceRuleActions[Type]"]').val(data.rule.actions.Type);

    if(!$('#ruleTermsList').data('add-input-area')){
        $('#ruleTermsList').addInputArea({});
        $('#ruleTermsList').data('add-input-area', true);
    }


    $('select.ruleTerm').select2();
    $('select.ruleOperand').each(function(index){
        var id = $(this).attr('id').replace(/\D+/, '');
        $(this).depdrop({
            "depends":[
                'ruleTerm_' + id,
            ],
            "url":'/pricerules/getoperands'
        });
    });
    $('input.term-value-date').kvDatepicker({
        format: 'dd.mm.yyyy',
        autoclose: true,
    });
    $('select.term-value-goodgroup').select2({
        width: "200px"
    });
}

function inputValueCreate(li, term, value, id, init){
    var input = li.find('.term-value');
    if(typeof input == "object"){
        if($(input).data('select2')){
            input.select2('destroy');
        }
        input.remove();
        input = '';
    }
    switch(term){
        case 'DocumentSum':
            input = document.createElement('input');
            input.setAttribute('type', 'text');
            input.setAttribute('id', 'ruleValue_' + id);
            input.setAttribute('class', 'term-value');
            input.setAttribute('name', 'priceRuleTerms[' + id + '][value]');
            input.setAttribute('name_format', 'priceRuleTerms[%d][value]');
            input.value = value;
            $(input).insertBefore(li.find('.ruleTermsList_del'));
            break;
        case 'GoodGroup':
            input = document.createElement('select');
            input.setAttribute('id', 'ruleValue_' + id);
            input.setAttribute('class', 'term-value term-value-goodgroup');
            input.setAttribute('name', 'priceRuleTerms[' + id + '][value]');
            input.setAttribute('name_format', 'priceRuleTerms[%d][value]');
            for(var key in categoriesDropdown){
                var option = document.createElement('option');
                option.value = categoriesDropdown[key].id;
                option.textContent = categoriesDropdown[key].text;
                categoriesDropdown[key].id == value ? option.setAttribute('selected', 'selected') : '';
                input.appendChild(option);
            }
            $(input).insertBefore(li.find('.ruleTermsList_del'));
            if(init){
                $(input).select2({
                    width: "200px"
                });
            }
            break;
        case 'WithoutBlyamba':
            input = document.createElement('input');
            input.setAttribute('type', 'text');
            input.setAttribute('id', 'ruleValue_' + id);
            input.setAttribute('class', 'term-value term-value-date');
            input.setAttribute('name', 'priceRuleTerms[' + id + '][value]');
            input.setAttribute('name_format', 'priceRuleTerms[%d][value]');
            input.setAttribute('disabled', 'disabled');
            input.value = true;
            $(input).insertBefore(li.find('.ruleTermsList_del'));
            break;
        case 'Date':
            input = document.createElement('input');
            input.setAttribute('type', 'text');
            input.setAttribute('id', 'ruleValue_' + id);
            input.setAttribute('class', 'term-value term-value-date');
            input.setAttribute('name', 'priceRuleTerms[' + id + '][value]');
            input.setAttribute('name_format', 'priceRuleTerms[%d][value]');
            if(value){
                input.value = value;
            }else{
                var today = new Date();
                var dd = today.getDate();
                var mm = today.getMonth() + 1;
                var yyyy = today.getFullYear();

                dd = dd < 10 ? '0' + dd : dd;
                mm = mm < 10 ? '0' + mm : mm;

                input.value = dd + '.' + mm + '.' + yyyy;
            }
            $(input).insertBefore(li.find('.ruleTermsList_del'));
            if(init){
                $(input).kvDatepicker({
                    format: 'dd.mm.yyyy',
                    autoclose: true,
                });
            }
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
    var id = e.currentTarget.getAttribute('data-attribute-ruleid');
    $.ajax({
	    type: 'POST',
		url: '/pricerules/edit',
		data: {
		    'id': id
		},
		success: function(data){
		    var list = $('.ruleEditForm #ruleTermsList');
		    $('input[name="ruleID"]').val(id);
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
}, addEvents = function(element){
    var counter = ($(element[0].parentNode).find(" > *").length - 1);
    $('#ruleTerm_' + counter).select2().val($('#ruleTerm_' + counter + ' option[selected]').val()).trigger('change');
    $('#ruleOperand_' + counter).depdrop({
        "depends":[
            'ruleTerm_' + counter,
        ],
        "url":'/pricerules/getoperands'
    }).val($('#ruleOperand_' + counter + ' option[selected]').val()).trigger('change');
    $('#ruleValue_' + counter + '.term-value-goodgroup').select2({width: "200px"});
    $('#ruleValue_' + counter + '.term-value-date').kvDatepicker({
        format: 'dd.mm.yyyy',
        autoclose: true,
    });
}

$('body').on('click', '.priceRuleState', changeState);
$('body').on('click', '.priceRuleEdit', updateRuleModal);
$('body').on("select2:selecting", 'select.ruleTerm', function(e){
    inputValueCreate($(e.currentTarget).parent('li'), e.params.args.data.id, 0, $(e.currentTarget).attr('id').replace(/\D+/, ''), true);
});

$("#ruleTermsList").on('inputArea.added', function(event, element){
    addEvents(element);
});

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