<?php
//TODO: hide me plz
$js = <<<'SCRIPT'
disableItemInOrder = function(button){
    $.ajax({
        type: 'POST',
        url: '/orders/changeiteminorderstate',
        data: {
            'orderID': button.parentNode.getAttribute("data-attribute-orderID"),
            'itemID': button.parentNode.getAttribute("data-attribute-itemID"),
            'param': 'inorder'
        },
        success: function(data){
            var parentRow = button.parentNode.parentNode.parentNode;

            if(data == 1){
                button.setAttribute('class', 'btn btn-default btn-sm glyphicon glyphicon-off');
                parentRow.setAttribute('class', parentRow.getAttribute('class').replace(/warning/g, ''));
            }else if(data == 0){
                button.setAttribute('class', 'btn btn-default btn-sm glyphicon glyphicon-off btn-warning');
                parentRow.setAttribute('class', 'warning ' + parentRow.getAttribute('class'));
            }else{
                alert('Что-то не получилось');
            }
        }
    });
}, deleteItemInOrder = function(button){
    $.ajax({
        type: 'POST',
        url: '/orders/changeiteminorderstate',
        data: {
            'orderID': button.parentNode.getAttribute("data-attribute-orderID"),
            'itemID': button.parentNode.getAttribute("data-attribute-itemID"),
            'param': 'deleted'
        },
        success: function(data){
            var parentRow = button.parentNode.parentNode.parentNode;

            if(data == 0){
                button.setAttribute('class', 'btn btn-default btn-sm glyphicon glyphicon-trash');
                parentRow.setAttribute('class', parentRow.getAttribute('class').replace(/danger/g, ''));
            }else if(data == 1){
                button.setAttribute('class', 'btn btn-default btn-sm glyphicon glyphicon-trash btn-danger');
                parentRow.setAttribute('class', 'danger ' + parentRow.getAttribute('class'));
            }else{
                alert('Что-то не получилось');
            }
        }
    });
}, refreshItemInOrder = function(button){
    var orderID = button.parentNode.getAttribute("data-attribute-orderID"),
        itemID = button.parentNode.getAttribute("data-attribute-itemID");

    $.ajax({
        type: 'POST',
        url: '/orders/restoreitemdata',
        data: {
            'itemID': itemID
        },
        success: function(data){

        }
    });
}
SCRIPT;

$this->registerJs($js);

?>
<div class="btn-group-vertical" data-attribute-orderID="<?=$model->orderID?>" data-attribute-itemID="<?=$model->id?>">
<?php
use yii\bootstrap\Modal;

Modal::begin([
    'header' => 'Редактирование товара',
    'options'   =>  [
        'style' =>  'color: black'
    ],
    'toggleButton' => [
        'tag'       =>  'button',
        'label'     =>  '',
        'class'     =>  'btn btn-default btn-sm glyphicon glyphicon-pencil'
    ],
    'size'  =>  Modal::SIZE_DEFAULT,
]);
$form = \kartik\form\ActiveForm::begin([
    'fieldConfig' => [
        'template' => "<div class=\"row\"><div class=\"col-xs-4\">{label}</div><div class=\"col-xs-5\">{input}</div><div class=\"col-xs-3\">{error}</div></div>",
        'labelOptions' => ['class' => 'control-label'],
    ],
    'options'   =>  [
        'enctype' => 'multipart/form-data'
    ]
]);

echo $form->field($model, 'id')->hiddenInput()->label(false),
$form->field($model, 'name'),
$form->field($model, 'count'),
$form->field($model, 'originalPrice'),
$form->field($model, 'discountSize'),
$form->field($model, 'discountType')->dropDownList($model::$DISCOUNT_TYPES);
?>
<button class="btn btn-large btn-success">Сохранить</button>
<?php
$form->end();
Modal::end(); ?>
<button class="btn btn-default btn-sm glyphicon glyphicon-off<?=$model->nalichie == 0 ? ' btn-warning' : ''?>" onclick="disableItemInOrder(this)"></button>
<button class="btn btn-default btn-sm glyphicon glyphicon-refresh" onclick="refreshItemInOrder(this)"></button>
<button class="btn btn-default btn-sm glyphicon glyphicon-trash<?=$model->nezakaz == 1 ? ' btn-danger' : ''?>" onclick="deleteItemInOrder(this)"></button></div>