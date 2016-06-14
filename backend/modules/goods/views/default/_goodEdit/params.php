<?php
use common\models\GoodOptionsVariant;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\Html;
use yii\helpers\Url;

\backend\assets\InputAreaAsset::register($this);

$js = <<<'JS'
    $('#goodAttributesList').addInputArea();

    var addEvents = function(element){
        selectOptions = {"allowClear":true,"options":{"name_format":"GoodOption[%d][option]"},"theme":"krajee","width":"100%","escapeMarkup":function(markup){return markup;},"language":{"noResults": function(){ return 'Такого параметра нет. <span class="addOption newAttribute">Добавить?</span>'; }}},
        counter = ($(element[0].parentNode).find(" > *").length - 1),
        depDropOptions = {"depends":["good_attribute_" + counter],"url":"\/goods\/filters?act=getattributes","params":["good_attribute"]},
        select2options = {"allowClear":false,"theme":"krajee","width":"100%","placeholder":"Выбрать...","escapeMarkup":function(markup){return markup;},"language":{"noResults": function(){ return 'Такого параметра нет. <span class="addAttribute newAttribute">Добавить?</span>'; }}},
        select2options2 = {"themeCss":".select2-container--krajee","sizeCss":"input-sm","doReset":true,"doToggle":false,"doOrder":false};

        if ($('#good_attribute_' + counter).data('select2')) { $('#good_attribute_' + counter).select2('destroy'); }
        $.when($('#good_attribute_' + counter).select2(selectOptions)).done(initS2Loading('good_attribute_' + counter,'select2options2'));

        if ($('#good_attribute_option_' + counter).data('depdrop')) { $('#good_attribute_option_' + counter).depdrop('destroy'); }
        $('#good_attribute_option_' + counter).depdrop(depDropOptions);

        if ($('#good_attribute_option_' + counter).data('select2')) { $('#good_attribute_option_' + counter).select2('destroy'); }
        $.when($('#good_attribute_option_' + counter).select2(select2options)).done(function(){
            initS2Loading('good_attribute_option_' + counter,'select2options2');
            $("#good_attribute_option_" + counter)[0].disabled = true;
        });

        $(element[0].parentNode).find(".kv-plugin-loading").remove();
    },
    addOption = function(option, selector){
        var block = document.createElement('option');

        block.innerHTML = option.name;
        block.setAttribute('value', option.id);

        if(selector != null){
            document.querySelector(selector).appendChild(block);
            return;
        }

        return block;
    }

    $("#goodAttributesList").on('inputArea.added', function(event, element) {
        addEvents(element);
    });

    $("body").on('change', '.goodAttribute', function(){
        $("#" + this.getAttribute("nested") + this.getAttribute('ID').replace(/\D+/g, ''))[0].setAttribute('data-optionID', $(this).val())
    });

    $("body").on('click', '.addOption', function(){
        if(this.parentNode.parentNode.parentNode.parentNode.querySelector("input").value.replace(/\s+/g, '').length == 0){
            return;
        }

        var select = this.parentNode.parentNode.getAttribute('ID').replace(/(^select2-|-results)/g, '');

        $.ajax({
            data: {
                value: this.parentNode.parentNode.parentNode.parentNode.querySelector("input").value
            },
            method: 'POST',
            url: '/goods/filters?act=newOption',
            success: function(data){
                addOption(data, "#" + select);

                $("#" + select).val(data.id).trigger('change').trigger('depdrop');
            }
        });
    });

    $("body").on('click', '.addAttribute', function(){
        if(this.parentNode.parentNode.parentNode.parentNode.querySelector("input").value.replace(/\s+/g, '').length == 0){
            return;
        }

        var select = this.parentNode.parentNode.getAttribute('ID').replace(/(^select2-|-results)/g, '');

        $.ajax({
            data: {
                value: this.parentNode.parentNode.parentNode.parentNode.querySelector("input").value,
                option: $("#" + select)[0].getAttribute('data-optionid')
            },
            method: 'POST',
            url: '/goods/filters?act=newAttributeOption',
            success: function(data){
                addOption(data, "#" + select);

                $("#" + select).val(data.id).trigger('change');
            }
        });
    });
JS;

$css = <<<'CSS'
#goodAttributesList{
    list-style: none;
}

#goodAttributesList li{
    margin-bottom: 5px;
    line-height: 22px;
    vertical-align: top;
    list-style: none;
}

#goodAttributesList li button{
    margin-top: -2px;
}

.newAttribute:hover{
    text-decoration: underline;
    cursor: pointer;
}
CSS;

$this->registerCss($css);

$this->registerJs($js);

$goodOptions = [];

if(empty($options)){
    $options = [0 => ['optionID' => 0, 'valueID' => 0]];
}

foreach($options as $key => $option){
    $goodOptions[] = Html::tag('li', Html::tag('div', Select2::widget([
            'name'      =>  'GoodOption['.$key.'][option]',
            'id'        =>  'good_attribute_'.$key,
            'pluginOptions'     =>  [
                'allowClear'    =>  true,
                'language'              =>  [
                    'noResults' =>  new \yii\web\JsExpression("function(){ return 'Такого значения нет. <span class=\"addOption newAttribute\">Добавить?</span>'; }")
                ],
                'escapeMarkup'  =>  new \yii\web\JsExpression("function(markup){return markup;}"),
            ],
            'options'   =>  [
                'class' =>  'goodAttribute',
                'name_format'   =>  "GoodOption[%d][option]",
                'nested'        =>  'good_attribute_option_',
            ],
            'size'      =>  'sm',
            'value'     =>  $option['optionID'],
            'data'      =>  \common\models\GoodOptions::getList(),
        ]), ['class'    => 'col-xs-5']).
        Html::tag('div', DepDrop::widget([
            'type'      =>  DepDrop::TYPE_SELECT2,
            'name'      =>  'GoodOption['.$key.'][value]',
            'options'   =>  [
                'id'            =>  'good_attribute_option_'.$key,
                'placeholder'   =>  'Выбрать...',
                'name_format'   =>  'GoodOption[%d][value]',
                'class' =>  'goodOption',
                'data-optionid' =>  $option['optionID']
            ],
            'value'     =>  $option['valueID'],
            'data'      =>  GoodOptionsVariant::getList($option['optionID']),
            'select2Options'    =>  [
                'size'  =>  'sm',
                'pluginOptions' =>  [
                    'allowClear'=>false,
                    'language'              =>  [
                        'noResults' =>  new \yii\web\JsExpression("function(){ return 'Такого значения нет. <span class=\"addAttribute newAttribute\">Добавить?</span>'; }")
                    ],
                    'escapeMarkup'  =>  new \yii\web\JsExpression("function(markup){return markup;}"),
                ]
            ],
            'pluginOptions' =>[
                'depends'       =>  [
                    'good_attribute_'.$key
                ],
                'url'           =>  Url::to(['/goods/filters', 'act' => 'getattributes', 'selected' => $option['valueID']]),
                'params'        =>  [
                    'good_attribute',
                ]
            ]
        ]), ['class' => 'col-xs-5']).
        Html::tag('div', Html::button(FA::icon('times'), [
            'class' =>  'goodAttributesList_del btn btn-danger btn-sm',
        ]), [
            'class' => 'col-xs-2'
        ]),
        [
            'class' =>  'row goodAttributesList_var',
            'style' =>  'padding: 2px 0'
        ]);
}

$form = \kartik\form\ActiveForm::begin([
    'options'   =>  [
        'class' =>  'goodEditForm'
    ]
]);

echo Html::tag('ol', implode('', $goodOptions), ['id' => 'goodAttributesList']),
Html::button(FA::icon('plus').' Добавить', [
    'class' => 'goodAttributesList_add btn btn-success btn-sm',
    'style' =>  'margin: 0px auto; display: block;'
]);
$form->end();