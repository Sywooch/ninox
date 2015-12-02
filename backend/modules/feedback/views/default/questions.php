<?php
use kartik\grid\GridView;
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
SCRIPT;
$this->registerJs($js);
?>
<h1>Вопросы</h1>
<div class="btn-group">
    <br><br>
    <?=ListView::widget([
        'dataProvider'  => $questions,
        'itemOptions'   => [
            'class'     => 'col-xs-6',
            'tag'       =>  'div'
            //'data-attribute-questionID'    =>  $questions->id,
          //  'class' =>  $model->published != 1 ? 'alert alert-danger' : 'alert alert-success'
        ],
        'layout'        =>  '<div class="row"><div class="col-xs-10">{summary}</div><div class="col-xs-10"><div class="row">{items}</div></div><div class="col-xs-12"><center>{pager}</center></div></div>',
        'summary'       =>  '',
        'itemView' => function ($model, $key, $index, $widget) {
            return $this->render('_question_item', [
                'model' =>  $model
            ]);
        },
    ]
    )
    ?>

