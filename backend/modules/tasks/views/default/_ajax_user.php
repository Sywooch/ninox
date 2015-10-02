<?php
    $css = <<<'STYLE'
    .user-header > *{
        display: inline-block;
        float: left;
    }

    .user-header > div{
        height: 80px;
        margin-left: 10px;
    }

    .user-header .user-info h3{
        line-height: 60px;
        margin-bottom: -15px;
    }

    .user-header .img-thumbnail{
        height: 80px;
        width: 80px;
        overflow: hidden;
    }

    .user-header .img-thumbnail img{
        max-width: 160px;
        max-height: 160px;
        margin: -25%;
        position: relative;
    }
STYLE;

$this->registerCss($css);
?>
<div class="row">
    <div class="col-xs-12 user-header">
        <div class="img-thumbnail img-circle"><img src="<?=$user->avatar?>"></div>
        &nbsp;
        <div class="user-info">
            <h3><?=$user->name?> (@<?=$user->username?>) <small><?=\app\models\Siteuser::$workStatuses[$user->workStatus]?></small></h3>
            <?php if($user->workStatus >= 1){ ?><small>Текущая задача: </small><?php } ?>
        </div>
    </div>
    <div class="col-xs-12">
        <hr>
    </div>
    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-9">
                <h3>Мои задачи:</h3>
                <?=\kartik\grid\GridView::widget([
                    'dataProvider'      =>  $userTasks,
                    'summary'           =>  '',
                    'striped'           =>  false,
                    'bordered'          =>  false,
                    'showHeader'        =>  false,
                    'condensed'         =>  true,
                    'columns'           =>  [
                        [
                            'class'     =>  \yii\grid\SerialColumn::className()
                        ],
                        'title',
                        [
                            'attribute' =>  'desiredDateTo',
                            'value'     =>  function($model){
                                return \Yii::$app->formatter->asDate($model->desiredDateTo, 'php: d.m.Y');
                            }
                        ],
                        [
                            'attribute' =>  'status',
                            'value'     =>  function($model){
                                return \app\models\Task::$statuses[$model->status];
                            }
                        ],
                    ]
                ])?>
                <!--
                <h3>Последние действия</h3>
                <?=\kartik\grid\GridView::widget([
                    'dataProvider'      =>  $userActions,
                    'summary'           =>  '',
                    'striped'           =>  false,
                    'bordered'          =>  false,
                    'condensed'         =>  true,
                    'columns'           =>  [
                        'new_value',
                        'id',
                        'field',
                        'stamp'
                    ]
                ])?>
                -->
                <?=\yii\bootstrap\Collapse::widget([
                    'items' =>  [
                        [
                            'label'     =>  'Выполненые за сегодня',
                            'content'   =>  '1'
                        ],
                        [
                            'label'     =>  'Выполненые за вчера',
                            'content'   =>  '1'
                        ],
                        [
                            'label'     =>  'Выполненые ранее',
                            'content'   =>  '123',
                            'options'   =>  [
                                'toggle'    =>  true
                            ]
                        ],
                    ]
                ])?>
            </div>
            <div class="col-xs-3">
                <!--<button class="btn btn-default btn-sm">Добавить в задачу</button>-->
            </div>
        </div>
    </div>
</div>