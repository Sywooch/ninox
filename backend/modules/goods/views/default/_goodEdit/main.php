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
CSS;

$this->registerCss($css);

$form = new ActiveForm([
    'id' => 'login-form-horizontal',
    'type' => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]
]);

echo $form->field($model, 'name');
echo $form->field($model, 'code', [
    'addon' => ['prepend' => ['content' => FA::i('key')]],
    'hintType' => ActiveField::HINT_SPECIAL,
    'hintSettings' => ['placement' => 'left', 'onLabelClick' => true, 'onLabelHover' => false]
])
    ->hint('Код является очень важной частью системы, поэтому редактировать его нежелательно!');
echo $form->field($model, 'category')->widget(\kartik\select2\Select2::className(), [
    'data' => Category::getList(),
    'options' => [
        'placeholder' => 'Выберите категорию...'
    ],
    'pluginOptions' => [
        'allowClear' => false
    ],
]);
echo $form->field($model, 'barcode', ['addon' => ['prepend' => ['content' => FA::i('barcode')]]]);
echo $form->field($model, 'additionalCode', ['addon' => ['prepend' => ['content' => FA::i('tags')]]]);
echo $form->field($model, 'description')->widget(\bobroid\imperavi\Widget::className(), [
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
]);

echo $form->field($model, 'wholesalePrice', ['addon' => ['prepend' => ['content' => '₴']]]);
echo $form->field($model, 'retailPrice', ['addon' => ['prepend' => ['content' => '₴']]]);
echo $form->field($model, 'inPackageAmount', [
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
]);
echo $form->field($model, 'anotherCurrencyValue', [
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
]);
//echo $form->field($model, '');

echo Html::tag('div', '', ['class' => 'clearfix']);