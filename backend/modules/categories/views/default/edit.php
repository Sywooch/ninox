<?php
use common\models\Category;
use kartik\file\FileInput;
use kartik\select2\Select2;
use kartik\form\ActiveForm;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;

\backend\assets\InputAreaAsset::register($this);

$this->title = $category->isNewRecord == '' ? 'Новая категория' : "Категория {$category->Name}";

$css = <<<'STYLE'
.tab-content .good-table tr:first-child td{
    border-top: 0px;
}

.good-table td:first-child{
    font-weight: bold;
    width: 23%;
}


dl{
    padding: 0px;
    margin: 0px;
}

dl dt{
    float: left;
    margin-right: 6px;
}

.tab-content{
    padding: 0;
}

#goodAttributesList{
    list-style: none;
}
STYLE;

$js = <<<'JS'

var addEvents = function(element){
    selectOptions = {"allowClear":true,"options":{"name_format":"CategoryForm[GoodOption][%d]"},"theme":"krajee","width":"100%","escapeMarkup":function(markup){return markup;},"language":{"noResults": function(){ return 'Такого параметра нет. <span class="addOption newAttribute">Добавить?</span>'; }}},
    counter = ($(element[0].parentNode).find(" > *").length - 1),
    select2options2 = {"themeCss":".select2-container--krajee","sizeCss":"input-sm","doReset":true,"doToggle":false,"doOrder":false};

    if ($('#good_attribute_' + counter).data('select2')) { $('#good_attribute_' + counter).select2('destroy'); }
    $.when($('#good_attribute_' + counter).select2(selectOptions).val($('#good_attribute_' + counter + ' option[selected]').val()).trigger('change')).done(initS2Loading('good_attribute_' + counter,'select2options2'));

    $(element[0].parentNode).find(".kv-plugin-loading").remove();
}

$('#goodAttributesList').addInputArea();

$("#goodAttributesList").on('inputArea.added', function(event, element) {
    addEvents(element);
});

$(".recalcCategoryPrices").on('click', function(){
    $.ajax({
        url: '/categories/recalc?act=retailPrice',
        method: 'POST',
        data: {
            categoryID: $("#categoryID").attr("data-categoryID"),
            size: $(this).parent().find("input").val()
        }, success: function(){
            Messenger().post({
                message: 'Цены на товары в категории пересчитаны!',
                type: 'info',
                showCloseButton: true,
                hideAfter: 300,
                actions: {
                    expand: {
                        label: 'к товарам',
                        action: function(){
                            location.href = '/goods?category=' + data.categoryCode;
                        }
                    },
                    close: {
                        label: 'Закрыть'
                    }
                }
            });
        }
    })
})

$("body").on('click', '#saveBtn', function(){
   $("#category-edit-form").submit(); 
});
JS;

$this->registerJs($js);

$this->registerCss($css);

$keywords = $skeyword = [];
$t1 = "<div class=\"row\"><div class=\"col-lg-12\"><div class=\"row\"><div class=\"col-xs-2\" style=\"line-height: 34px; vertical-align: middle;\">{label}</div><div class=\"col-xs-10\">{input}</div></div><div class=\"col-lg-3\">{error}</div></div></div>";
$t2 = "<div class=\"row\"><div class=\"col-lg-12\">{input}</div><div class=\"col-lg-3\">{error}</div></div>";

$categoryUk = $category;

if(isset($category->keyword) && strlen($category->keyword) >= 1){
    $a = explode(', ', strip_tags($category->keyword));
    if(count($a) >= 1 && $a['0'] != ""){
        foreach($a as $aa){
            $keywords[] = $aa;
            $skeyword[$aa] = $aa;
        }
    }
}

echo Html::tag('h1', (!$category->isNewRecord ? $category->name : 'Новая категория').'&nbsp;'.Html::tag('small', 'Категория'), ['data-categoryID' => $category->ID]);
?>
<div class="panel panel-info">
    <div class="panel-heading">
        <div class="btn-group pull-left">
            <?=($category->enabled ? Html::a(FA::icon('globe').' Посмотреть на сайте', \Yii::$app->params['frontend'].'/'.$category->link, ['class' => 'btn btn-default']) : '').
            \backend\widgets\ChangesWidget::widget([
                'model'         =>  $category,
                'header'        =>  'Изменения по категории '.$category->Name
            ])?>
        </div>
        <button class="btn btn-success pull-right" type="submit" style="margin-left: 10px;" id="saveBtn">Сохранить</button>
        <div class="btn-group pull-right" role="group" aria-label="...">
            <button type="button" class="btn btn-info" data-attribute-categoryID="<?=$category->ID?>"><?=$category->canBuy == "1" ? "Не продавать товары" : "Продавать товары"?></button>
            <button type="button" class="btn btn-info" data-attribute-categoryID="<?=$category->ID?>"><?=$category->enabled == "1" ? "Выключить" : "Включить"?></button>
            <a href="/categories/view/<?=$category->ID?>" type="button" class="btn btn-info">В режим просмотра</a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-3">
                <?php if(!empty($category->photo)){
                    echo Html::img(\Yii::$app->params['cdn-link'].'/img/catalog/'.$category->cat_img),
                    Html::tag('br', '', ['style' => 'margin-top: 10px; display: block;']);
                }

                echo Html::tag('div', FileInput::widget([
                    'name'  =>  'categoryPhoto',
                    'options'=>[
                        'accept' => 'image/*'
                    ],
                    'pluginOptions' => [
                        'uploadUrl' =>  '/goods/uploadcategoryphoto',
                        'uploadExtraData' => [
                            'ItemId' => $category->ID
                        ],
                        'showCaption' => false,
                        'showRemove' => false,
                        'showUpload' => true,
                        'showPreview' => false,
                        'uploadClass'   =>  'btn btn-info',
                        'browseClass' => 'btn btn-primary btn-block',
                        'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                        'browseLabel' =>  'Сменить фото',
                        'uploadLabel' =>  'Загрузить',
                        'layoutTemplates'   =>  [
                            'main1' =>  '{preview}\n<div class="kv-upload-progress hide"></div>\n<div class="input-group {class}">\n{caption}\n
                                             <div class="input-group-btn">\n{remove}\n{cancel}\n{browse}\n{upload}\n</div>\n</div>',
                            'main2' =>  '{preview} <div class="kv-upload-progress hide"></div><div class="row"><div class="col-xs-8">{browse}</div><div class="col-xs-4" style="margin-left: -17px;">{upload}</div></div>'
                        ],
                    ],
                    'pluginEvents'  =>  [
                        'fileuploaded'    =>  'function(event, data, previewId, index) {

                            }',
                    ],
                ]), [
                    'id'    =>  'updateGoodPhoto',
                    'class' =>  'clearfix'
                ])?>
            </div>
            <div class="col-xs-9">
                <?php

                $form = ActiveForm::begin([
                    'id'                    =>  'category-edit-form',
                    'validateOnBlur'        =>  true,
                    'enableClientValidation'=>  true,
                    'validateOnSubmit'      =>  true,
                    'type'                  =>  ActiveForm::TYPE_HORIZONTAL,
                    'options'               =>  [
                        'enctype'   => 'multipart/form-data'
                    ],
                    'formConfig'            =>  [
                        'labelSpan' =>  '3'
                    ]
                ]);


                echo $form->field($categoryForm, 'name'),
                $form->field($categoryForm, 'title'),
                Html::tag('div',
                    Html::tag('div', '', ['class' => 'col-xs-4']).
                    Html::tag('div',
                        \yii\bootstrap\Html::tag('div',
                            $form->field($categoryForm, 'titleAsc').
                            $form->field($categoryForm, 'titleDesc').
                            $form->field($categoryForm, 'titleNew'),
                            [
                                'style' =>  'margin-left: -30px;'
                            ]),
                        [
                            'class' =>  'col-xs-8'
                        ]),
                    [
                        'class' =>  'row'
                    ]).
                $form->field($categoryForm, 'header'),
                Html::tag('div',
                    Html::tag('div', '', ['class' => 'col-xs-4']).
                    Html::tag('div',
                        \yii\bootstrap\Html::tag('div',
                            $form->field($categoryForm, 'headerAsc').
                            $form->field($categoryForm, 'headerDesc').
                            $form->field($categoryForm, 'headerNew'),
                            [
                                'style' =>  'margin-left: -30px;'
                            ]),
                        [
                            'class' =>  'col-xs-8'
                        ]), [
                    'class' =>  'row'
                ]),
                $form->field($categoryForm, 'description')->widget(\bobroid\imperavi\Widget::className(), [
                    'options' => [
                        'toolbar' => true,
                    ],
                ]),
                $form->field($categoryForm, 'metaDescription')->widget(\bobroid\imperavi\Widget::className(), [
                    'options' => [
                        'toolbar' => true,
                    ],
                ]),
                $form->field($categoryForm, 'keywords')->widget(Select2::className(), [
                    'name'  =>  $categoryForm->formName().'[keywords]',
                    'language'  =>  'ru',
                    'value' => $categoryForm->keywordsArray,
                    'data' => $categoryForm->keywordsArray,
                    'options' => ['placeholder' => 'Введите ключевые слова'],
                    'pluginOptions' => [
                        'tags'  =>  true,
                        'tokenSeparators' => [',']
                    ],
                ]),
                $form->field($categoryForm, 'parentCategory')->widget(Select2::className(), [
                    'value'     => $categoryForm->parentCategory,
                    'data'      => Category::getList(),
                    'options'   => ['placeholder' => 'Выберите категорию...'],
                    'pluginOptions'     => [
                        'allowClear'    => true
                    ],
                ]),
                $form->field($categoryForm, 'enabled')->radioList([
                    '1' =>  'Включена',
                    '0' =>  'Выключена'
                ], [
                    'id'    =>  'menu_show',
                    'class' =>  'btn-group',
                    'data-toggle'   =>  'buttons',
                    'unselect'  =>  null,
                    'item'  =>  function ($index, $label, $name, $checked, $value) {
                        return '<label class="btn btn-default' . ($checked ? ' active' : '') . '">' .
                        Html::radio($name, $checked, ['value' => $value, 'class' => 'project-status-btn']) . $label . '</label>';
                    }
                ]),
                $form->field($categoryForm, 'retailPercent', [
                    'addon' =>  [
                        'prepend'   =>  [
                            'content'   =>  '%'
                        ],
                        'append' => [
                            'content'   =>  PHP_EOL.Html::button('Пересчитать товары категории', ['class' => 'recalcCategoryPrices'])
                        ]
                    ],
                    'options'   =>  [
                        'labelSpan' =>  6
                    ]
                ]),
                $form->field($categoryForm, 'onePrice')->radioList([
                    '1' =>  'Включена',
                    '0' =>  'Выключена'
                ], [
                    'id'    =>  'onePrice',
                    'class' =>  'btn-group',
                    'data-toggle'   =>  'buttons',
                    'unselect'  =>  null,
                    'item'  =>  function ($index, $label, $name, $checked, $value) {
                        return '<label class="btn btn-default' . ($checked ? ' active' : '') . '">' .
                        Html::radio($name, $checked, ['value' => $value, 'class' => 'project-status-btn']) . $label . '</label>';
                    }
                ]),
                $form->field($categoryForm, 'umlExport')->radioList([
                    '1' =>  'Экспортировать',
                    '0' =>  'Не экспортировать'
                ], [
                    'id'    =>  'ymlExport',
                    'class' =>  'btn-group',
                    'data-toggle'   =>  'buttons',
                    'unselect'  =>  null,
                    'item'  =>  function ($index, $label, $name, $checked, $value) {
                        return '<label class="btn btn-default' . ($checked ? ' active' : '') . '">' .
                        Html::radio($name, $checked, ['value' => $value, 'class' => 'project-status-btn']) . $label . '</label>';
                    }
                ]),$form->field($categoryForm, 'sellProducts')->radioList([
                    '1' =>  'Продаются',
                    '0' =>  'Не продаются'
                ], [
                    'id'    =>  'canBuy',
                    'class' =>  'btn-group',
                    'data-toggle'   =>  'buttons',
                    'unselect'  =>  null,
                    'item'  =>  function ($index, $label, $name, $checked, $value) {
                        return '<label class="btn btn-default' . ($checked ? ' active' : '') . '">' .
                        Html::radio($name, $checked, ['value' => $value, 'class' => 'project-status-btn']) . $label . '</label>';
                    }
                ]);

                $goodOptions = $options = [];
                $goodOptionsList = \common\models\GoodOptions::getList();

                if(!empty($category->goodOptions)){
                    $options = $category->goodOptions;
                }else{
                    $obj = new \stdClass();
                    $obj->goodOptions = \common\models\GoodOptions::find()->one();
                    $obj->option = 0;
                    $options = [0 => $obj];
                }

                foreach($options as $key => $option){
                    $variantsList = [];
                    foreach($option->goodOptions->optionVariants as $variant){
                        $variantsList[$variant->id] = $variant->value;
                    }

                    $goodOptions[] = Html::tag('li', Html::tag('div', Select2::widget([
                            'name'      =>  'CategoryForm[GoodOption]['.$key.']',
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
                                'name_format'   =>  "CategoryForm[GoodOption][%d]",
                                'nested'        =>  'good_attribute_option_',
                            ],
                            'size'      =>  'sm',
                            'value'     =>  $option->option,
                            'data'      =>  $goodOptionsList,
                        ]), ['class'    => 'col-xs-5']).
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

                echo Html::tag('div',
                    Html::tag('div', 'Свойства товаров по умолчанию', ['style'  => 'padding-left: 40px; font-weight: bold;']).
                    Html::tag('ol', implode('', $goodOptions), ['id' => 'goodAttributesList']).
                    Html::button(FA::icon('plus').' Добавить', [
                        'class' => 'goodAttributesList_add btn btn-success btn-sm',
                        'style' =>  'margin: 0px auto 10px; display: block;'
                    ]),
                    [
                        'style' =>  'margin: 10px; padding 10px; border: 1px solid;',
                    ]);

                echo
                    Html::tag('center',
                        Html::button('Сохранить', ['class' => 'btn btn-lg btn-success', 'style' => 'margin: 0 auto', 'type' => 'submit']).
                        ' или '.
                        Html::a('В режим просмотра', '/categories/view/'.$category->ID, ['type' => 'button', 'class' => 'btn btn-info'])
                    );

                $form->end();
                ?>
            </div>
        </div>
    </div>
</div>