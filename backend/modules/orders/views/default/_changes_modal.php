<button class="remodal-close" data-remodal-action="close"></button>
<?php \yii\widgets\Pjax::begin()?>
<?=\yii\grid\GridView::widget([
    'dataProvider'  =>  $dataProvider
])?>
<?php \yii\widgets\Pjax::end()?>