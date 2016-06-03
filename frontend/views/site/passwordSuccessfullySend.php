<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 01.06.16
 * Time: 14:00
 */
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="content" xmlns="http://www.w3.org/1999/html">
    <div class="">
        Письмо отправлено Вам на <? echo Html::a(\Yii::t('shop', 'почту')/*, Url::to([
            ('http://'.substr($model->email, strrpos($model->email, '@')+1))
        ])*/)
        ?>
    </div>
</div>
<!--'onclick' => "window.open ('http://'+document.getElementById
('passwordresetrequestform-email'.substr($model->email, strrpos($model->email, '@')+1)).value)"-->