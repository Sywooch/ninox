<?php

use yii\bootstrap\Html;

$commentsDataProvider = new \yii\data\ActiveDataProvider([
    'query' =>  $order->getComments(),
    'sort'  =>  [
        'defaultOrder'  =>  [
            'stamp' =>  SORT_DESC
        ]
    ]
]);

$comment = new \backend\models\OrderCommentForm();

$js = <<<'JS'
    $("#form-pjax").on('pjax:success', function(){
        $.pjax.reload({container: '#commentsList'});
    })
JS;

$this->registerJs($js);

?>

<div class="row">
    <div class="col-xs-12">
        <?php
        \yii\widgets\Pjax::begin([
            'id'        =>  'commentsList',
        ]);
        ?>
        <div class="page-header" style="margin-top: 0">
            <h1 style="margin-top: 0"><small class="pull-right" style="line-height: 40px"><?=\Yii::t('backend', 'Комментариев: {n}', ['n' => $commentsDataProvider->getCount()])?></small> Комментарии к заказу</h1>
        </div>
        <?=\yii\widgets\ListView::widget([
            'dataProvider'  =>  $commentsDataProvider,
            'options'       =>  [
                'class'     =>  'comments-list',
                'style'     =>  'max-height: 320px; overflow-y: auto'
            ],
            'itemOptions'   =>  [
                'style'     =>  'padding: 0 10px'
            ],
            'summary'       =>  false,
            'itemView'      =>  function($comment){
                $commenter = $comment->commenter;

                if(empty($commenter)){
                    $commenter = new \backend\models\Siteuser([
                        'name'  =>  'Система'
                    ]);
                }

                return Html::tag('div',
                    Html::tag('p',
                        Html::tag('small', \Yii::$app->formatter->asRelativeTime(strtotime($comment->stamp))),
                        [
                            'class' =>  'pull-right'
                        ]
                    ).
                    Html::a(Html::img((empty($commenter->avatar) ? '/img/noimage.png' : $commenter->avatar), ['style' => 'max-height: 60px; max-width: 60px;']), '#', ['class' => 'media-left']).
                    Html::tag('div',
                        Html::tag('h4', $commenter->name, ['class' => 'media-heading user_name']).
                        $comment->comment,
                        [
                            'class' =>  'media-body'
                        ]
                    ),
                    [
                        'class' =>  'media',
                        'style' =>  'padding: 10px 0'
                    ]
                );
            }
        ]);
        \yii\widgets\Pjax::end();
        ?>
        <div class="page-footer" style="padding-top: 10px; margin-top: 20px; border-top: 1px solid rgba(0, 0, 0, 0.1)">
            <?php
            \yii\widgets\Pjax::begin([
                'id'    =>  'form-pjax'
            ]);
            $form = \kartik\form\ActiveForm::begin([
                'id'        =>  'orderCommentForm',
                'options'   =>  [
                    'data-pjax' =>  true
                ]
            ]);
            echo $form->field($comment, 'comment', [
                'options'   =>  [
                    'class' =>  'col-xs-9'
                ]
            ])->textarea(),
            Html::tag('div', Html::button('Отправить', [
                'class' =>  'btn btn-lg btn-success',
                'type'  =>  'submit'
            ]), [
                'class' =>  'col-xs-3',
                'style' =>  'padding-top: 25px'
            ]);

            $form->end();
            \yii\widgets\Pjax::end();
            ?>
        </div>
    </div>
</div>