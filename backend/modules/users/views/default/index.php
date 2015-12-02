<?php
    $this->title = 'Пользователи';

    $this->params['breadcrumbs'][] = $this->title;

    $js = <<<'SCRIPT'
    var changePasswordModalOpen = function(e){
        var button  = e.currentTarget,
            userID  = button.getAttribute('data-user-id'),
            modal   = document.querySelector("div.remodal[data-remodal-id='changePassword']");

        $.ajax({
            type: 'POST',
            url: '/users/changepassword',
            data: {
                'id': userID
            },
            success: function(data){
                modal.innerHTML = data;
            }
        });
    };

    $(".changePasswordButton").on('click', function(e){
        changePasswordModalOpen(e);
    });
SCRIPT;

    $this->registerJs($js);

    $changePasswordModal = new \bobroid\remodal\Remodal([
        'id'            =>  'changePassword',
        'addRandomToID' =>  false,
    ]);
?>
<h1><?=$this->title?></h1>
<div class="btn-group">
    <?=\backend\widgets\AddUserWidget::widget([])?>
</div>
    <br>
    <br>
    <br>
<?=\yii\widgets\ListView::widget([
    'summary'       =>  '',
    'dataProvider'  =>  $dataProvider,
    'itemView'      =>  function($model){
        return $this->render('_user', [
            'model' =>  $model
        ]);
    }
]),
$changePasswordModal->renderModal()?>

<div style="clear: both"></div>
    <br>
    <br>
    <br>
    <br>
<?=''/*\kartik\grid\GridView::widget([
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
                    return \backend\widgets\AddUserWidget::widget([
                        'model'         =>  $model,
                        'buttonText'    =>  '<i class="glyphicon glyphicon-pencil"></i>'
                    ]);
                },
                'delete'        =>  function($url, $model){
                        return '<button class="btn btn-default"><i class="glyphicon glyphicon-trash" title="Удалить"></i></button>';
                    },
                'view'        =>  function($url, $model){
                    return \yii\helpers\Html::a('<i class="glyphicon glyphicon-user"></i>', \yii\helpers\Url::toRoute('/users/showuser/'.$model->id), [
                        'class' =>  'btn btn-default'
                    ]);
                },
            ],
            'template'  =>  '<div class="btn-group btn-group-sm" role="group">{view}{update}{delete}</div>',
            'width'     =>  '120px'
        ]
    ]
])*/?>