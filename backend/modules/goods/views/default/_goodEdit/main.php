<?php
use common\models\Category;
use kartik\form\ActiveField;
use kartik\form\ActiveForm;
use kartik\touchspin\TouchSpin;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;

$css = <<<'CSS'
.anotherCurrencyTagAddOn{
    width: 72px;
    padding: 0;
}

.anotherCurrencyTagAddOn select{
    width: 100%;
    height: 20px;
    padding: 0;
    margin: 0 10px;
}

.addOnInputWithLabel{
    width: 140px;
}

.addOnInputWithLabel > div{
    width: auto;
}

.addOnInputWithLabel > *{
    display: inline-block;
    float: left;
}

.addOnInputWithLabel label{
    margin: 0 !important;
    padding: 0 !important;
}


CSS;

$js = <<<'JS'
    $("#goodmainform-isunlimited").on('change', function(e){
        var elem = $("#goodmainform-count")[0];
        if(e.currentTarget.checked){
            elem.setAttribute('disabled', 'disabled');
        }else{
            elem.removeAttribute('disabled');
        }
    });

    $("#goodmainform-undefinedpackageamount").on('change', function(e){
        var elem = $("#goodmainform-inpackageamount")[0];
        if(e.currentTarget.checked){
            elem.setAttribute('disabled', 'disabled');
        }else{
            elem.removeAttribute('disabled');
        }
    });
JS;

$this->registerJs($js);

$this->registerCss($css);

$form = new ActiveForm([
    'options'   =>  [
        'class' =>  'goodEditForm',
    ],
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'validateOnType'    =>  true,
    'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]
]);

$form->begin();

if(empty($model->getErrors()) && $model->isSaved){
    echo Html::tag('div', Html::tag('b', 'Успех!').' Основная информация о товаре сохранена!', [
        'class' =>  'alert bg-success alert-success'
    ]);
}

echo $form->field($model, 'name'),
$form->field($model, 'code', [
    'addon' => ['prepend' => ['content' => FA::i('key')]],
    'hintType' => ActiveField::HINT_SPECIAL,
    'hintSettings' => ['placement' => 'left', 'onLabelClick' => true, 'onLabelHover' => false]
]),
$form->field($model, 'category')->widget(\kartik\select2\Select2::className(), [
    'data' => Category::getList(),
    'options' => [
        'placeholder' => 'Выберите категорию...'
    ],
    'pluginOptions' => [
        'allowClear' => false
    ],
]),
$form->field($model, 'barcode', ['addon' => ['prepend' => ['content' => FA::i('barcode')]]]),
$form->field($model, 'additionalCode', ['addon' => ['prepend' => ['content' => FA::i('tags')]]]),
$form->field($model, 'description')->widget(\bobroid\imperavi\Widget::className(), [
    'model' => $model,
    'attribute' => 'description',
    'options' => [
        'toolbar' => true,
        /*'autosave'  =>  '#',
        'autosaveInterval'  =>  '5',*/
        'imageUpload' => '/upload.php',
        'imageManagerJson' => '/images/images.json'
    ],
    'plugins' => [
        'fullscreen',
        'imagemanager',
        'fontcolor',
        'fontsize',
        'table',
    ]
]),
$form->field($model, 'wholesalePrice', ['addon' => ['prepend' => ['content' => '₴']]]),
$form->field($model, 'retailPrice', ['addon' => ['prepend' => ['content' => '₴']]]),
$form->field($model, 'inPackageAmount', [
    'template'      =>  '{label}'.Html::tag('div', '{input}'.$form
                ->field($model, 'undefinedPackageAmount', ['options' => ['class' => 'col-xs-3']])
                ->checkbox(['label' => 'неизвестно']), ['style' => 'margin-left: -15px;', 'class' => 'col-xs-8']),
    'inputOptions'  =>  [
        'class' =>  'col-xs-6'
    ]
])->widget(TouchSpin::classname(), [
    'options'   =>  [
        'disabled'  =>  empty($model->inPackageAmount)
    ],
    'pluginOptions' =>  [
        'max'   =>  10000
    ]
]),
$form->field($model, 'anotherCurrencyValue', [
    'addon' => [
        'prepend'   => [
            'content' => $form
                ->field($model, 'anotherCurrencyPeg', ['template' => '{input}'])->checkbox([], false)->label(false)
        ],
        'append'    =>  [
            'content'   =>  $form
                ->field($model, 'anotherCurrencyTag', ['template' => '{input}'])
                ->label(false)
                ->dropDownList($model->getCurrencies()),
            'options'   =>  [
                'class' =>  'anotherCurrencyTagAddOn'
            ]
        ]
    ]
]),
$form->field($model, 'count', [
    'inputOptions'   =>  [
        !$model->isUnlimited ? '' : 'disabled'  =>  'disabled'
    ],
    'addon' =>  [
        'append'   =>  [
            'content'   =>  $form->field($model, 'isUnlimited', ['template' => Html::tag('div', '{input}{label}', ['class' => 'addOnInputWithLabel'])])
                ->checkbox([], false)
                ->label('бесконечно')
        ]
    ]
]),
$form->field($model, 'isOriginal')->checkbox([], false),
$form->field($model, 'haveGuarantee')->checkbox([], false),
$form->field($model, 'enabled')->checkbox([], false),
Html::tag('div', '', ['class' => 'clearfix']);

$form->end();