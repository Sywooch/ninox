<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$js = <<<JS
function myFunction() {
    window.open("http://www.w3schools.com");
}
JS;

$this->title = \Yii::t('shop', 'Востановление пароля');
$this->params['breadcrumbs'][] = $this->title;


$this->registerMetaTag(['name' => 'robots', 'content' => 'noindex, follow'], 'robots');
?>
<div class="content" xmlns="http://www.w3.org/1999/html">
    <div class="site-request-password-reset">
        <h1><?= Html::encode($this->title) ?></h1>
        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
                    <?= $form->field($model, 'email') ?>
                    <div class="form-group">
                        <?= Html::submitButton(\Yii::t('shop', 'Отправить'), [
                            'class'   => 'btn btn-primary',
                        ]) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>