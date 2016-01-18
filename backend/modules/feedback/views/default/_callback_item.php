<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 12/2/2015
 * Time: 1:44 PM
 */
use backend\widgets\AddCallbackWidget;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
?>
<div style="width: 50%; display: inline-block; float: left;">
    <?= AddCallbackWidget::widget([
        'model'         =>  $model,
        'buttonLabel'   =>  '<i class="glyphicon glyphicon-pencil"></i>',
        'buttonClass'   =>  'btn btn-link'
    ])?>
    <span><?=$model->customerName?></span>
    <br>
    <a style="display: block; opacity: 0.4; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; max-width: 90%; font-size: "><?=$model->question?>
        <br></a>
</div>
<div style="width: 9%; display: inline-block; padding: 5px;" class="btn-group btn-group-sm">
    <?=Html::button(FA::icon($model->did_callback == 1 ? 'eye' : 'eye-slash'), [
        'class'                 =>  'CallbackState btn btn-default'.($model->did_callback != 1 ? ' btn-danger' : ' btn-success'),
        'data-attribute-callbackID' =>  $model->id
    ])
    ?>

    <button type="button" class="btn btn-default changeTrashState" <?=$model->id == '' ? 'disabled="disabled" ' : ''?>data-attribute-callbackID="<?=$model->id?>"><?=$model->deleted == "0" ? "Удалить" : "Восттановить";?></button>
</div>

<div style="clear: both"></div>

