<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = \Yii::t('shop', 'Востановление пароля');
$this->params['breadcrumbs'][] = $this->title;

$this->registerMetaTag(['name' => 'robots', 'content' => 'noindex, follow'], 'robots');
?>
<div class="content">
    <div class="site-reset-password">
        <?=
            Html::tag('h1', Html::encode($this->title))
/*            Html::tag('p', \Yii::t('shop', 'Пожалуйста введите Ваш новый пароль:'))*/
        ?>

        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['id' => 'reset-password-form']);

                    echo $form->field($model, 'password')->passwordInput(),
                        Html::tag('div',
                            Html::submitButton(\Yii::t('shop', 'сохранить'), ['class' => 'btn btn-primary']),
                            [
                                'class' => 'form-group'
                            ]
                        );

                 ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
