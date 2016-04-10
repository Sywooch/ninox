<?php
$this->title = 'Контроль заказа';
$this->params['breadcrumbs'][] = $this->title;

$js = <<<'JS'
var goToPage = function(){
    var input = $("#orderNum");

    if(input.val().length == 0){
        input.validated = false;
        return;
    }

    location.href = '/orders/control/' + input.val();
}

$("#orderNum").on('keypress keyup input', function(){
    $(this).val($(this).val().replace(/\D+/, ''));
});

$("#control").on('click', function(){
    goToPage();
});

$(document).on('keypress', function(e){
    if(e.keyCode == 13){
        goToPage();
    }
});
JS;

$this->registerJs($js);

?>
<div style="margin: 200px auto 0;width: 640px; height: 300px;" class="well">
    <h1 style="text-align: center; display: block; padding: 10px;">Контроль заказа</h1>
    <input class="form-control" id="orderNum" style="width: 400px; line-height: 24px; height: 40px; font-size: 24px; text-align: center; display: block; margin: 50px auto;" autofocus="true" type="text">
    <div style="margin: 0 auto; text-align: center">
        <label for="orderNum" style="display: block; margin: -45px auto 10px;"><small>Введите здесь номер заказа</small></label>
        <button class="btn btn-default btn-lg" id="control">Контроль заказа</button>
    </div>
</div>