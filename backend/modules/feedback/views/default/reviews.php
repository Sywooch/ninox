<?php
use yii\widgets\ListView;
$this->title = 'Отзывы';
$this->params['breadcrumbs'][] = $this->title;
$js = <<<'SCRIPT'
var changeState = function(e){
    var button = e.currentTarget,
        id = button.getAttribute('data-attribute-reviewID');
    $.ajax({
	    type: 'POST',
		url: '/feedback/changereviewstate',
		data: {
		    'id': id
		},
		success: function(data){
			if(data == 1){
			    button.setAttribute('class', 'btn btn-default btn-success ReviewState');
			    button.innerHTML = '<i class="fa fa-eye"></i>';
			    button.parentNode.parentNode.setAttribute('col-xs-6', 'alert alert-success');
			}else{
			    button.setAttribute('class', 'btn btn-default btn-danger ReviewState');
			    button.innerHTML = '<i class="fa fa-eye-slash"></i>';
			    button.parentNode.parentNode.setAttribute('col-xs-6', 'alert alert-danger');
			}
		}
	});
},
    buttons1 = document.querySelectorAll(".ReviewState");
for(var i = 0; i < buttons1.length; i++){
    buttons1[i].addEventListener('click', changeState, false);
}
SCRIPT;
$this->registerJs($js);
?>
<h1>Отзывы</h1>
<div class="btn-group">

    <br><br>
    <?=ListView::widget([
        'dataProvider'  => $reviews,
        'itemOptions'   => [
            'class'     => 'col-xs-6',
            'tag'       =>  'div'
        ],
        'layout'        =>  '<div class="row"><div class="col-xs-12">{summary}</div><div class="col-xs-12"><div class="row">{items}</div></div><div class="col-xs-12"><center>{pager}</center></div></div>',
        'summary'       =>  '',
        'itemView' => function ($model, $key, $index, $widget) {
            return $this->render('_review_item', [
                'model' =>  $model
            ]);
        },
    ])?>

