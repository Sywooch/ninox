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
        selectOptions = {"allowClear":true,"options":{"name_format":"GoodOption[%d][option]"},"theme":"krajee","width":"100%","language":{"noResults": function(){ return 'Такого параметра нет. <span class="addOption newAttribute">Добавить?</span>'; }}},
        counter = ($(element[0].parentNode).find(" > *").length - 1),
        depDropOptions = {"depends":["good_attribute_" + counter],"url":"\/goods\/filters?act=getattributes","params":["good_attribute"]},
        select2options = {"allowClear":false,"theme":"krajee","width":"100%","placeholder":"Выбрать...","language":{"noResults": function(){ return 'Такого параметра нет. <span class="addAttribute newAttribute">Добавить?</span>'; }}},
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
    }

    $("#goodAttributesList").on('inputArea.added', function(event, element) {
        addEvents(element);
    });

    $("body").on('click', '.addOption', function(){
        console.log('clicked .addOption');

        console.log(this.parentNode.parentNode.getAttribute('ID'));

        $.ajax({
            data: {
                value: this.parentNode.parentNode.parentNode.parentNode.querySelector("input").value
            },
            method: 'POST',
            url: '/goods/filters?act=newOption',
            success: function(data){
                console.log(data);
            }
        });
    });

    $("body").on('click', '.addAttribute', function(){
        console.log('clicked .addAttribute');
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
    $goodOptions[] = Html::tag('li', Html::tag('div', Select2::widget([
            'name'      =>  'GoodOption[0][option]',
            'id'        =>  'good_attribute_0',
            'pluginOptions'     =>  [
                'language'              =>  [
                    'noResults' =>  new \yii\web\JsExpression("function(){ console.log(); return 'Такого параметра нет. <span class=\"addOption newAttribute\">Добавить?</span>'; }")
                ],
                'escapeMarkup'  =>  new \yii\web\JsExpression("function(markup){return markup;}"),
                'allowClear'    =>  true,
                'options'   =>  [
                    'class' =>  'goodOption',
                    'name_format'   =>  "GoodOption[%d][option]"
                ],
            ],
            'options'   =>  [
                'name_format'   =>  "GoodOption[%d][option]"
            ],
            'size'      =>  'sm',
            'data'      =>  \common\models\GoodOptions::getList(),
        ]), ['class'    => 'col-xs-5']).
        Html::tag('div', DepDrop::widget([
            'type'      =>  DepDrop::TYPE_SELECT2,
            'name'      =>  'GoodOption[0][value]',
            'options'   =>  [
                'class'         =>  'goodAttribute',
                'id'            =>  'good_attribute_option_0',
                'placeholder'   =>  'Выбрать...',
                'name_format'   =>  'GoodOption[%d][value]'
            ],
            'select2Options'    =>  [
                'size'  =>  'sm',
                'pluginOptions' =>  [
                    'language'              =>  [
                        'noResults' =>  new \yii\web\JsExpression("function(){ return 'Такого значения нет. <span class=\"addAttribute newAttribute\">Добавить?</span>'; }")
                    ],
                    'options'   =>  [
                        'class' =>  'goodOption',
                    ],
                    'escapeMarkup'  =>  new \yii\web\JsExpression("function(markup){return markup;}"),
                    'allowClear'            =>  false,
                    'createSearchChoice'    =>  new \yii\web\JsExpression("function(term, data){return {id: term, text: term}}")
                ]
            ],
            'pluginOptions' =>[
                'depends'       =>  [
                    'good_attribute_0'
                ],
                'url'           =>  Url::to(['/goods/filters', 'act' => 'getattributes']),
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
                'options'   =>  [
                    'class' =>  'goodAttribute',
                    'name_format'   =>  "GoodOption[%d][option]"
                ],
            ],
            'options'   =>  [
                'name_format'   =>  "GoodOption[%d][option]"
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
                'name_format'   =>  'GoodOption[%d][value]'
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
                    'options'   =>  [
                        'class' =>  'goodOption',
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

$form = new \kartik\form\ActiveForm([
    'options'   =>  [
        'class' =>  'goodEditForm'
    ]
]);
$form->begin();

echo Html::tag('ol', implode('', $goodOptions), ['id' => 'goodAttributesList']),
Html::button(FA::icon('plus').' Добавить', [
    'class' => 'goodAttributesList_add btn btn-success btn-sm',
    'style' =>  'margin: 0px auto; display: block;'
]);
$form->end();