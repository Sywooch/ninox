<?php
    $this->title = 'Пользователи';

    $this->params['breadcrumbs'][] = $this->title;
?>
<h1>Пользователи</h1>
<div class="btn-group">
    <?=\app\components\AddUserWidget::widget([])?>
</div>
<br>
<br>
<?=\kartik\grid\GridView::widget([
    'dataProvider'  =>  $dataProvider,
    'hover' =>  true,
    'condensed' =>  true,
    'responsiveWrap' =>  true,
    'columns'       =>  [
        [
            'attribute' =>  'name',
            'vAlign'    =>  \kartik\grid\GridView::ALIGN_MIDDLE,
            'hAlign'    =>  \kartik\grid\GridView::ALIGN_CENTER
        ],
        [
            'attribute' =>  'username',
            'vAlign'    =>  \kartik\grid\GridView::ALIGN_MIDDLE,
            'hAlign'    =>  \kartik\grid\GridView::ALIGN_CENTER
        ],
        [
            'attribute' =>  'lastActivity',
            'value'     =>  function($model){
                return \Yii::$app->formatter->asRelativeTime($model->lastActivity);
            },
            'vAlign'    =>  \kartik\grid\GridView::ALIGN_MIDDLE,
            'hAlign'    =>  \kartik\grid\GridView::ALIGN_CENTER
        ],
        [
            'attribute' =>  'lastLoginIP',
            'format'    =>  'html',
            'class'     =>  \kartik\grid\DataColumn::className(),
            'vAlign'    =>  \kartik\grid\GridView::ALIGN_MIDDLE,
            'hAlign'    =>  \kartik\grid\GridView::ALIGN_CENTER,
            'value'     =>  function($model){
                return \kartik\popover\PopoverX::widget([
                    'content' => $model->lastLoginIP,
                    'toggleButton' => [
                        'label' =>  $model->lastLoginIP,
                        'class' =>  'btn btn-default btn-link'
                    ],
                ]);
            }
        ],
        [
            'attribute' =>  'default_route',
            'vAlign'    =>  \kartik\grid\GridView::ALIGN_MIDDLE,
            'hAlign'    =>  \kartik\grid\GridView::ALIGN_CENTER,
        ],
        [
            'class'     =>  \kartik\grid\ActionColumn::className(),
            'buttons'   =>  [
                'update'        =>  function($url, $model){
                    return \app\components\AddUserWidget::widget([
                        'model'         =>  $model,
                        'buttonText'    =>  '<i class="glyphicon glyphicon-pencil"></i>'
                    ]);
                },
                'delete'        =>  function($url, $model){
                        return '<button class="btn btn-default"><i class="glyphicon glyphicon-trash" title="Удалить"></i></button>';
                    },
                'view'        =>  function($url, $model){
                    return '<a href="/admin/users/showuser/'.$model->id.'" class="btn btn-default" title="Просмотр"><i class="glyphicon glyphicon-user"></i></a>';
                },
            ],
            'template'  =>  '<div class="btn-group-vertical" role="group">{view}{update}{delete}</div>',
            'width'     =>  '120px'
        ]
    ]
])?>