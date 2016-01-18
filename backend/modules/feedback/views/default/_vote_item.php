<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 12/3/2015
 * Time: 12:16 PM
 */
use backend\widgets\AddVoteWidget;

?>
<div class="pull-right">
    <?= AddVoteWidget::widget([
        'model'         =>  $model,
        'buttonLabel'   =>  '<i class="glyphicon glyphicon-pencil"></i>',
        'buttonClass'   =>  'btn btn-link'
    ])?>
</div>

<span style=" opacity: 0.4;  text-overflow: ellipsis; "><?=$model->date_to?></span>
<br>
<span style=" opacity: 0.4;  text-overflow: ellipsis; "><?=$model->date_from?></span>
<br>
<small   style="opacity: 0.4; text-overflow: ellipsis;">

    <br>

</small><br>
<span style=" opacity: 0.4;  text-overflow: ellipsis; "><?=$model->text?></span>



