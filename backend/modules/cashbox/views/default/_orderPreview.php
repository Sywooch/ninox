<div class="orderPreview">
    <?=\kartik\grid\GridView::widget([
        'dataProvider'  =>  $goods,

        'columns'       =>  [
            [
                'class' =>  \yii\grid\SerialColumn::className()
            ],
            [
                'attribute' =>  'name'
            ],
            [
                'attribute' =>  'count'
            ],
            [
                'attribute' =>  'originalPrice',
                'value'     =>  function($model){
                    return $model->originalPrice.' грн.';
                }
            ]
        ],
        'summary'   =>  false,
        'bordered'  =>  false,
        'resizableColumns'  =>  false
    ])?>
</div>