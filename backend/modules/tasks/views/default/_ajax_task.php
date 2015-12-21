<?php
use common\models\Task;
use common\models\TaskUser;
use kartik\depdrop\DepDrop;
use kartik\form\ActiveForm;
use kartik\popover\PopoverX;
use yii\helpers\Html;
use yii\helpers\Url;

$css = <<<'STYLE'
.labels-list span{
    display: inline-block;
    vertical-align: middle;
}

.task-user{
    height: 40px;
    line-height: 40px;
    vertical-align: middle;
    display: inline-block;
    float: left;
    margin-right: 20px;
}

.task-user .img-preview{
    overflow: hidden;
    position: relative;
    float: left;
    height: 40px;
    width: 40px;
    display: inline-block;
}

.task-user .img-preview img{
    max-height: 80px;
    max-width: 80px;
    margin: -25%;
}

.task-user .task-user-info{
    display: inline-block;
    margin-top: 0px;
    margin-left: 5px;
}

.task-user .task-user-info span{
    display: block;
    line-height: 18px;
    max-height: 20px;
    padding: 1px;
}
STYLE;


$this->registerCss($css);



$statusBtnClass = '';

switch($task->status){
    case '0':
        $statusBtnClass = ' btn-info';
        break;
    case '1':
        $statusBtnClass = ' btn-warning';
        break;
    case '2':
        $statusBtnClass = ' btn-success';
        break;
    default:
        $statusBtnClass = ' btn-default';
}

?>
<div class="row">
    <div class="col-xs-9">
        <div class="well well-sm" style="white-space: pre-wrap"><?=$task->description?></div>
    </div>
    <div class="col-xs-3" style="margin-left: -15px;">
        <div class="labels-list">
            <span class="label label-info">Дедлайн <?=\Yii::$app->formatter->asDate($task->desiredDateTo, 'php:d.m.Y')?></span>
            <?php if($task->desiredDateTo < $task->dateTo || $task->dateTo == '0000-00-00'){ ?><span class="label label-danger">просрочена <?=\Yii::$app->formatter->asDate($task->desiredDateTo, 'php:d.m.Y')?></span><?php } ?>
            <?php if($task->status == '2'){ ?><span class="label label-success">выполнена <?=\Yii::$app->formatter->asDate($task->dateTo, 'php:d.m.Y')?></span><?php } ?>
            <span class="label label-default">Автор: <span title="@<?php $u = \common\models\Siteuser::getUser($task->author); echo $u->username?>"><?=$u->name?></span></span>
            <br style="height: 5px; display: block; margin-top: 5px;">
            <div class="btn-group" style="width: 100%;">
                <button type="button" id="task-<?=$task->id?>" class="btn <?=$statusBtnClass?> btn-block dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?=Task::$statuses[$task->status]?> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <?php foreach(Task::$statuses as $key => $status){ ?>
                        <li><a href="#" onclick="changeTaskStatus(event)" class="statusButton" data-task="<?=$task->id?>" data-status="<?=$key?>"><?=$status?></a></li>
                    <?php } ?>
                </ul>
            </div>
            <br style="height: 5px; display: block; margin-top: 5px;">
            <a class="btn btn-default btn-block" href="/edit">Редактировать</a>
        </div>
    </div>
</div>
<hr>
<?php

\yii\widgets\Pjax::begin();

?>
<div class="row">
    <div class="col-xs-12">
        Участники:
        <div style="height: 40px; vertical-align: middle; overflow-x: auto">
            <?=\yii\widgets\ListView::widget([
                'dataProvider'  =>  $taskUsers,
                'summary'       =>  '',
                'options'       =>  [
                    'style'     =>  'width: 100%; height: 40px; overflow-y: hidden;'
                ],
                'itemOptions'   =>  [
                    'class'     =>  'task-user'
                ],
                'emptyText'     =>  'Ещё нет участников. Добавтье их с помощью формы ниже',
                'itemView'      =>  function($model){
                    return '<div class="img-circle img-preview"><img src="'.$model->avatar.'"></div><div class="task-user-info"><span class="name">'.$model->name.' (@'.$model->username.')</span><span class="status"><small>'.TaskUser::$roles[$model->user_role].'</small></span></div>';
                }
            ])?>
        </div>
        <?php if(sizeof($activeUsers) >= 1){ ?>
        <div class="row">
            <div class="col-xs-12" style="margin-top: 20px;">Добавить:</div>
            <?php
            $model = new TaskUser;
            $model->task_id = $task->id;
            $form = new ActiveForm([
                'action'    =>  '/tasks'
            ]);
            echo $form->field($model, 'task_id')->hiddenInput()->label(false),
                '<div class="col-xs-3">',
                $form->field($model, 'user_id')->dropDownList($activeUsers, ['id' => 'task-user-id'])->label(false),
                '</div><div class="col-xs-2">',
                $form->field($model, 'user_role')->dropDownList(TaskUser::$roles)->label(false),
                '</div><div class="col-xs-2">',
                '<button class="btn btn-default">добавить</button>',
                '</div>';
            ?>
        </div>
        <?php } ?>
    </div>
</div>
<?php
\yii\widgets\Pjax::end();
?>
<hr>
<div class="row">
    <div class="col-xs-12">
        Изменения по задаче:
        <?php \yii\widgets\Pjax::begin()?>
        <?=\kartik\grid\GridView::widget([
            'dataProvider'  =>  $taskChanges,
            'summary'   =>  '',
            'bordered'          =>  false,
            'columns'       =>  [
                [
                    'attribute' =>  'user_id',
                    'header'    =>  'кто',
                    'value'     =>  function($model){
                        $user = \common\models\Siteuser::getUser($model->user_id);
                        return $user->name;
                    }

                ],
                [
                    'attribute' =>  'field',
                    'header'    =>  'что',
                    'value'     =>  function($model) use($task){
                        $labels = $task->attributeLabels();
                        return isset($labels[$model->field]) ? $labels[$model->field] : $model->field;
                    }
                ],
                [
                    'attribute' =>  'stamp',
                    'header'    =>  'когда',
                    'value'     =>  function($model){
                        return \Yii::$app->formatter->asDateTime($model->stamp, 'php: d.m.Y H:i');
                    }
                ],
                [
                    'attribute' =>  'old_value',
                    'header'    =>  'Старое значение',
                    'value'     =>  function($model){
                        switch($model->field){
                            case 'status':
                                return Task::$statuses[$model->old_value];
                                break;
                            default:
                                return $model->old_value;
                        }
                    }
                ],
                [
                    'attribute' =>  'new_value',
                    'header'    =>  'Новое значение',
                    'value'     =>  function($model){
                        switch($model->field){
                            case 'status':
                                return Task::$statuses[$model->new_value];
                                break;
                            default:
                                return $model->new_value;
                        }
                    }
                ],
            ]
        ])?>
        <?php \yii\widgets\Pjax::end()?>
    </div>
</div>