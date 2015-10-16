
<?php
use yii\helpers\Html;
use yii\helpers\Url; ?>

<?=Html::a($category->Name.' (включеных: '.$enabled.', выключеных: '.$disabled .')', Url::toRoute(['/goods', 'category' => $category->Code, 'smartfilter' => \Yii::$app->request->get("smartfilter")]))?>
<?=\backend\widgets\CategoryDropdownWidget::widget([
    'category'  =>  $category
])?>
<?=Html::tag('span', Html::a('', Url::toRoute(['showcategory/'.$category->ID, 'act' => 'edit']), ['class' => 'glyphicon glyphicon-pencil']), ['class' => 'pull-right'])?>