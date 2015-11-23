<?php
use kartik\dropdown\DropdownX;
use yii\helpers\Url;

$dateDiff = time() - strtotime($model->lastActivity);
?>
<div class="col-xs-4" style="margin: 5px 0">
    <div class="row">
        <div class="col-xs-3">
            <img src="<?=!empty($model->avatar) ? $model->avatar : '/img/noimage.png'?>" class="img-circle" style="max-height: 90px">
        </div>
        <div class="col-xs-9">
            <h3 style="margin-top: 5px;"><?=$model->name?> <small><?=$model->username?></small></h3>
            <h6 style="position: absolute; margin-top: -10px;">
                <?=\yii\helpers\Html::tag('span', \Yii::$app->formatter->asRelativeTime($model->lastActivity), [
                    'class' =>  'label label-'.($dateDiff > 60 ? ($dateDiff > (86400 * 30) ? 'default' : 'warning') : 'success')
                ])?>
                <?php if(!in_array($model->lastLoginIP, \Yii::$app->params['ourIPs'])){
                    echo \yii\helpers\Html::tag('span', $model->lastLoginIP, [
                        'class' =>  'label label-danger'
                    ]);
                } ?>
            </h6>
            <div class="btn-group btn-group-sm" style="margin-top: 10px;">
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-expanded="true">
                    Действия <span class="caret"></span>
                </button>
                <a class="btn btn-default"><?=\rmrevin\yii\fontawesome\FA::icon('key')?></a>
                <?=DropdownX::widget([
                    'items' =>  [
                        [
                            'label'     =>  'Просмотреть',
                            'url'       =>  Url::toRoute('showuser/'.$model->id)
                        ],

                    ]
                ])?>
            </div>
        </div>
    </div>
</div>