<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 19.05.16
 * Time: 12:11
 */

use yii\helpers\Html;

$model = new \frontend\models\ReviewForm();
?>

<?php    $form = \yii\bootstrap\ActiveForm::begin([
    'id'            =>  'review-form'
]);
?>
<?php if(\Yii::$app->user->isGuest) {
   echo $form->field($model, 'name'),
        $form->field($model, 'city');
    } else{
    echo Html::tag('div',
        Html::a(\Yii::$app->user->identity->name.' '.\Yii::$app->user->identity->surname/*.'
        '.!empty($sliderBanners).' ? '.\Yii::$app->user->identity->city.' : '.$form->field($model, 'city')*/));
}
?>
<?= $form->field($model, 'review')->textarea()?>
<?php
echo $form->field($model, 'customerType')->dropDownList($model->customerTypes);
?>
<?php
echo $form->field($model, 'question')->checkbox($model->question);
?>

<?= Html::submitButton(\Yii::t('shop', 'Отправить'), ['class' => 'middle-button modal-blue-button', 'name' =>
    'review-button']) ?>

<?php $form->end(); ?>