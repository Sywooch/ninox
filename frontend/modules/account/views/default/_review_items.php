<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 12/11/2015
 * Time: 12:29 PM
 */
?>
<div class="items">
    <div class="image">
        <?=\yii\bootstrap\Html::img('https://krasota-style.com.ua/img/catalog/'.$model->photo)?>
    </div>
    <div class="write-review">
        <?=\yii\bootstrap\Html::a(\Yii::t('shop', 'написать отзыв'), '/tovar/'.$model->good->link.'-g'.$model->itemID)?>
    </div>
</div>