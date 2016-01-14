<?php

$this->title = 'Магазины и склады';

$this->params['breadcrumbs'][] = $this->title;

?>

<h1><?=$this->title?></h1>

<button class="btn btn-default">Добавить новый</button><br><br>

<?=\yii\widgets\ListView::widget([
    'dataProvider'  =>  $shops,
    'summary'       =>  false,
    'itemView'      =>  function($model){
        return $this->render('_shop', [
            'model' =>  $model
        ]);
    }
])?>

<?=''//$this->render("_shop")?>