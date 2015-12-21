<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 12/1/2015
 * Time: 4:19 PM
 */
use yii\widgets\ListView;
$this->title = 'Проблемы';
$this->params['breadcrumbs'][] = $this->title;
$js = <<<'SCRIPT'
var changeState = function(e){
    var button = e.currentTarget,
        id = button.getAttribute('data-attribute-problemID');
    $.ajax({
	    type: 'POST',
		url: '/feedback/changeproblemstate',
		data: {
		    'id': id
		},
		success: function(data){
			if(data == 1){
			    button.setAttribute('class', 'btn btn-default btn-success ProblemState');
			    button.innerHTML = '<i class="fa fa-eye"></i>';
			    button.parentNode.parentNode.setAttribute('class', 'alert alert-success');
			}else{
			    button.setAttribute('class', 'btn btn-default btn-danger ProblemState');
			    button.innerHTML = '<i class="fa fa-eye-slash"></i>';
			    button.parentNode.parentNode.setAttribute('class', 'alert alert-danger');
			}
		}
	});
},
    buttons1 = document.querySelectorAll(".ProblemState");
for(var i = 0; i < buttons1.length; i++){
    buttons1[i].addEventListener('click', changeState, false);
}
SCRIPT;
$this->registerJs($js);
?>
    <h1>Проблемы</h1>
    <div class="btn-group">
        <br><br>
    <?=ListView::widget([
        'dataProvider'  => $problems,
        'itemOptions'   => [
            'class'     => $problem->read != 1 ? 'alert alert-danger' : 'alert alert-success',
            'tag'       =>  'div'

        ],
        'summary'       =>  '',
        'itemView' => function ($model, $key, $index, $widget) {
            return $this->render('_problem_item', [
                'model' =>  $model
        ]);
    },
])?>