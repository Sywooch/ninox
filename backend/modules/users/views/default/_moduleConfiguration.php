<?php
use kartik\editable\Editable;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;

$js = <<<'SCRIPT'

var addControllerToDB = function(e){
    $.ajax({
        type: 'POST',
        url: '/addcontroller',
        data: {
            'controller': e.currentTarget.getAttribute('data-controller')
        },
        success: function(data){

        }
    });
}

document.querySelector("button#addControllerToDB").addEventListener('click', addControllerToDB, false);
SCRIPT;

$this->registerJs($js);
?>
<div style="text-align: left">
    <?=Html::tag('div', 'Состояние контроллера в БД: '.Html::button((!empty($controller->id) ? 'Добавлено!' : 'Добавить'), [
            !empty($controller->id) ? 'disabled' : 'enabled'    =>  'disabled',
            'class' =>  'btn btn-'.(!empty($controller->id) ? 'success' : 'default'),
            'id'    =>  'addControllerToDB',
            'data-controller'   =>  \Yii::$app->controller->className()
        ]), [
        'class'     =>  'bg-'.(!empty($controller->id) ? 'success' : 'danger'),
        'style'     =>  'padding: 10px;'
    ])?>

    <div class="well well-sm">
        Список экшнов модуля:
        <?=\yii\grid\GridView::widget([
            'dataProvider'  =>  $dataProvider
        ])?>
        <?php
        $editable = Editable::begin([
            'model'         =>  $action,
            'attribute'     =>  'action',
            'format'        =>  Editable::FORMAT_BUTTON,
            'editableButtonOptions' =>  [
                'label'     =>  'Добавить '.\rmrevin\yii\fontawesome\FA::icon('plus'),
                'class'     =>  'btn btn-default btn-success'
            ],
            'asPopover'     =>  false,
            'inlineSettings'=>  [
                'templateBefore'    =>  Editable::INLINE_BEFORE_2,
                'templateAfter'     =>  Editable::INLINE_AFTER_2
            ],
            'containerOptions'  =>  [
                'style' =>  'max-width: 592px; min-width: 100%'
            ],
            'contentOptions' => [
                'style' =>  'max-width: 592px; min-width: 100%'
            ],
            'ajaxSettings'  =>  [
                'type'      =>  'post',
                'url'       =>  Url::to('/addaction'),
            ],
            'displayValue'  => ' ',
            'options'       =>  [
                'placeholder'   =>  'Введите название экшна',
            ]
        ]);

        $form = $editable->getForm();

        echo Html::hiddenInput('kv-complex', '1');

        $editable->afterInput = $form->field($action, 'description').Html::input('text', 'controller', \Yii::$app->controller->className(), ['style' => 'display: none;'])."\n";

        Editable::end();
        ?>
    </div>

</div>