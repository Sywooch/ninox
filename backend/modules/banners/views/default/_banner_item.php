<?php
$a = '<div class="row">
    <div class="col-xs-4">
        <img src="https://krasota-style.com.ua/'.($model->type == 'html' ? 'template/img/banner_html.jpg' : $model->banner).'" class="img-thumbnail" style="max-height: 200px;">
    </div>
    <div class="col-xs-8">'.($model->deleted ? '<h3>Удалён</h3>' : ($model->state == 1 ? '<h3>Активен с '.($model->dateStart != '0000-00-00 00:00:00' ? \Yii::$app->formatter->asDatetime($model->dateStart, 'php:d.m.Y H:i') : '-').' по '.($model->dateEnd != '0000-00-00 00:00:00' ? \Yii::$app->formatter->asDatetime($model->dateEnd, 'php:d.m.Y H:i') : '-').'</h3>' : '<h3>Неактивен</h3>')).'
        <br>
        <br>'.\common\components\AddBannerWidget::widget([
            'model' =>  $model
        ]).'
        <div class="btn-group"><button class="btn btn-default" disabled>Редактировать</button><button class="btn btn-default deleteBanner">'.($model->deleted == '1' ? 'Восстановить' : 'Удалить').'</button><button class="btn btn-default changeBannerState">'.($model->state == '1' ? 'Выключить' : 'Включить').'</button></div>
    </div>
</div>
<div class="clearfix"></div>';

echo $a;