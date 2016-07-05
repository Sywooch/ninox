<?php
use kartik\typeahead\Typeahead;
use yii\helpers\Url;
use yii\web\JsExpression;

$itemTemplate = 'Handlebars.compile(\'<div class="findedCustomer" data-attribute-id="{{ID}}"><p>{{Company}}</p><small>тел. {{phone}}{{#if cardNumber}}, карта {{cardNumber}}{{/if}}</small></div>\')';

$js = <<<'JS'
var typeaheadSelectFunc = function(suggestion){
    $.ajax({
        type: 'POST',
        url: '/changecustomer',
        data: {
          customerID: suggestion.ID
        },
        success: function(data){
            var customerData = suggestion.Company;

            if(suggestion.phone.length > 0){
                customerData += '<br><small>';
                customerData += suggestion.phone;
                customerData += '</small>';
            }

            $('#changeCustomer')[0].innerHTML = customerData;

            $("[data-remodal-id=customerModal]").remodal().close();

            $.pjax.reload({container: '#cashboxGrid-pjax'});

            summary.update(data);

            Messenger().post({
                message: 'Текущий клиент: ' + suggestion.Company,
                type: 'info',
                showCloseButton: true,
                hideAfter: 5
            });
        }
    });
};

$('#cardNumberSearch').bind('typeahead:select', function(ev, suggestion) {
    typeaheadSelectFunc(suggestion);
});

$('#phoneSearch').bind('typeahead:select', function(ev, suggestion) {
    typeaheadSelectFunc(suggestion);
});

$('#companySearch').bind('typeahead:select', function(ev, suggestion) {
    typeaheadSelectFunc(suggestion);
});
JS;

$js = $this->registerJs($js);
?>

<h3>Выбор существующего клиента:</h3>
<br>

<div class="col-xs-12">
    <div class="col-xs-4">
        Номер карты
    </div>
    <div class="col-xs-8">
        <?=Typeahead::widget([
            'name' => 'Customer[cardNumber]',
            'options' => ['id' => 'cardNumberSearch', 'placeholder' => '900000xxx'],
            'pluginOptions' => [
                'highlight'=>true,
            ],
            'scrollable'    =>  true,
            'dataset' => [
                [
                    'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                    'display' => 'value',
                    'limit'     =>  10,
                    'templates' => [
                        'notFound' => '<div class="text-danger" style="padding:0 8px">'.\Yii::t('admin', 'По вашему запросу ничего не найдено!').'</div>',
                        'suggestion' => new JsExpression($itemTemplate)
                    ],
                    'remote' => [
                        'rateLimitBy'   =>  'throttle',
                        'url' => Url::to(['findcustomer']) . '?attribute=cardNumber&query=%QUERY',
                        'wildcard' => '%QUERY'
                    ]
                ]
            ]
        ]);?>
    </div>
    <div class="col-xs-12">
        или
    </div>
    <div class="col-xs-4">
        Телефон клиента
    </div>
    <div class="col-xs-8">
        <?=Typeahead::widget([
            'name' => 'Customer[phone]',
            'options' => ['id' => 'phoneSearch', 'placeholder' => '0xx1234567'],
            'scrollable'    =>  true,
            'pluginOptions' => ['minLength' => '3', 'highlight'=>true],
            'dataset' => [
                [
                    'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                    'display'   => 'value',
                    'limit'     =>  10,
                    'templates' => [
                        'notFound' => '<div class="text-danger" style="padding:0 8px">'.\Yii::t('admin', 'По вашему запросу ничего не найдено!').'</div>',
                        'suggestion' => new JsExpression($itemTemplate)
                    ],
                    'remote' => [
                        'rateLimitBy'   =>  'throttle',
                        //'rateLimitWait' =>  'throttle',
                        'url'           => Url::to(['findcustomer']) . '?attribute=phone&query=%QUERY',
                        'wildcard'      => '%QUERY',
                    ]
                ]
            ]
        ]);?>
    </div>
    <div class="col-xs-12">
        или
    </div>
    <div class="col-xs-4">
        Имя или фамилия
    </div>
    <div class="col-xs-8">
        <?=Typeahead::widget([
            'name' => 'Customer[Company]',
            'options' => ['id' => 'companySearch', 'placeholder' => 'Василий Пупкин'],
            'pluginOptions' => ['minLength' => '2', 'highlight'=>true],
            'scrollable'    =>  true,
            'dataset' => [
                [
                    'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                    'display' => 'value',
                    'limit'     =>  10,
                    'templates' => [
                        'notFound' => '<div class="text-danger" style="padding:0 8px">'.\Yii::t('admin', 'По вашему запросу ничего не найдено!').'</div>',
                        'suggestion' => new JsExpression($itemTemplate)
                    ],
                    'remote' => [
                        'rateLimitBy'   =>  'throttle',
                        'url' => Url::to(['findcustomer']) . '?attribute=Company&query=%QUERY',
                        'wildcard' => '%QUERY'
                    ]
                ]
            ]
        ]);?>
    </div>
</div>