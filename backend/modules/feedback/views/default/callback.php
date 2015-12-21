<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 12/2/2015
 * Time: 1:49 PM
 */
use yii\widgets\ListView;
$this->title = 'Запросы на перезвон';
$this->params['breadcrumbs'][] = $this->title;
$js = <<<'SCRIPT'
var changeState = function(e){
    var button = e.currentTarget,
        id = button.getAttribute('data-attribute-callbackID');
    $.ajax({
	    type: 'POST',
		url: '/feedback/changecallbackstate',
		data: {
		    'id': id
		},
		success: function(data){
			if(data == 1){
			    button.setAttribute('class', 'btn btn-default btn-success CallbackState');
			    button.innerHTML = '<i class="fa fa-eye"></i>';
			    button.parentNode.parentNode.setAttribute('col-xs-6', 'alert alert-success');
			}else{
			    button.setAttribute('class', 'btn btn-default btn-danger CallbackState');
			    button.innerHTML = '<i class="fa fa-eye-slash"></i>';
			    button.parentNode.parentNode.setAttribute('col-xs-6', 'alert alert-danger');
			}
		}
	});
},
    buttons1 = document.querySelectorAll(".CallbackState");
for(var i = 0; i < buttons1.length; i++){
    buttons1[i].addEventListener('click', changeState, false);
}

changeTrashState = function(e){
    var target = e.currentTarget;
    $.ajax({
        type: 'POST',
        url: '/feedback/workwithcallbacktrash',
        data: {
            'CallbackID': e.currentTarget.getAttribute("data-attribute-callbackID")
        },
        success: function(data){
            if(data.length >= "1"){
                target.innerHTML = data == "1" ? "Восстановить" : "Удалить";
            }
        }
    });
}

$(".changeTrashState").on('click', function(e){ changeTrashState(e); });
SCRIPT;
$this->registerJs($js);
?>
<h1>Вопросы</h1>
<div class="btn-group">
    <br><br>
    <?=ListView::widget([
            'dataProvider'  => $callback,
            'itemOptions'   => [
                'class'     => 'col-xs-6',
                'tag'       =>  'div'
            ],
            'layout'        =>  '<div class="row"><div class="col-xs-10">{summary}</div><div class="col-xs-10"><div class="row">{items}</div></div><div class="col-xs-12"><center>{pager}</center></div></div>',
            'summary'       =>  '',
            'itemView' => function ($model, $key, $index, $widget) {
                return $this->render('_callback_item', [
                    'model' =>  $model
                ]);
            },
        ]
    )
    ?>

