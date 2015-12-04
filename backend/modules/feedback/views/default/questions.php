<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 12/2/2015
 * Time: 4:19 PM
 */
use yii\widgets\ListView;
$this->title = 'Вопросы';
$this->params['breadcrumbs'][] = $this->title;
$js = <<<'SCRIPT'
var changeState = function(e){
    var button = e.currentTarget,
        id = button.getAttribute('data-attribute-questionID');
    $.ajax({
	    type: 'POST',
		url: '/feedback/changequestionstate',
		data: {
		    'id': id
		},
		success: function(data){
			if(data == 1){
			    button.setAttribute('class', 'btn btn-default btn-success QuestionState');
			    button.innerHTML = '<i class="fa fa-eye"></i>';
			    button.parentNode.parentNode.setAttribute('col-xs-6', 'alert alert-success');
			}else{
			    button.setAttribute('class', 'btn btn-default btn-danger QuestionState');
			    button.innerHTML = '<i class="fa fa-eye-slash"></i>';
			    button.parentNode.parentNode.setAttribute('col-xs-6', 'alert alert-danger');
			}
		}
	});
},
    buttons1 = document.querySelectorAll(".QuestionState");
for(var i = 0; i < buttons1.length; i++){
    buttons1[i].addEventListener('click', changeState, false);
}

changeTrashState = function(e){
    var target = e.currentTarget;
    $.ajax({
        type: 'POST',
        url: '/feedback/workwithquestiontrash',
        data: {
            'QuestionID': e.currentTarget.getAttribute("data-attribute-questionID")
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
    <br><br>
    <?=ListView::widget([
        'dataProvider'  => $questions,
        'itemOptions'   => [
            'class'     => 'col-xs-6',
            'tag'       =>  'div'
        ],
        'summary'       =>  '',
        'itemView' => function ($model, $key, $index, $widget) {
            return $this->render('_question_item', [
                'model' =>  $model
            ]);
        },
    ]
    )
    ?>

