
<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?=Html::a($category->Name.' (включеных: '.$enabled.', выключеных: '.$disabled .')', Url::toRoute([empty($category->childs) ? '/goods' : '/categories', 'category' => $category->Code, 'smartFilter' => \Yii::$app->request->get("smartFilter")]))?>
<?=\backend\widgets\CategoryDropdownWidget::widget([
    'category'  =>  $category
])?>
<?=Html::tag('span', Html::a('', Url::toRoute(['show/'.$category->ID, 'act' => 'edit']), ['class' => 'glyphicon glyphicon-pencil']), ['class' => 'pull-right'])?>