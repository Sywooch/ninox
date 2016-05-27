<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 13.04.16
 * Time: 14:43
 */

use yii\helpers\Html;
use yii\helpers\Url;

$css = <<<'CSS'

.callback {
text-align: left;
}

.callback-modal{
    width: 520px;
    padding: 0px;
}

.callback .form-group textarea{
    display: block;
    width: 100% !important;
    max-width: 100%;
    min-height: 60px;
}

.callback .head{
    font-size: 22px;
    padding-bottom: 30px;
    display: block;
    color: #4f4f4f;
    padding: 35px;
    border-bottom: 1px solid #e0e0e0;
    font-family: OpenSans-Semibold;

}

.callback .form-group{
    overflow: auto;
    width: 50%;
float: left;
}

.callback .form-group input{
    /*
    width: 50%;
    */
    width: 227px;
height: 45px;
float: left;
}

.callback .form-group label{
    line-height: 34px;
}


.callback .title{
    width: 200px;
    display: inline-block;
    line-height: 40px;
    font-size: 16px;
    color: rgb(79, 79, 79);
}

.callback .fio input, .callback .phone input{
    height: 40px;
    float: right;
    width: 300px;
}

.callback input.captcha{
    margin: auto;
    display: block;
    float: left;
    height: 40px;
}

.callback .captcha{
padding-bottom: 25px;
}

.callback .captcha .title{
    float: left;
}

.callback .field-callbackform-captcha img{
    height: 35px;
    margin-left: 30px;
}

.callback-content{
padding: 30px;
overflow: hidden;
}

.callback .yellow-button-modal{
    float: right;
    margin-top: 39px;
}

CSS;

$this->registerCss($css);

$model = new \frontend\models\CallbackForm();

Html::tag('div', \Yii::t('shop', '').
    Html::tag('div', '', [
        'class'   =>  'cross'
    ]), [
    'class'                 =>  'cart-close',
    'data-remodal-action'   =>  'close'
])

?>
<div class="callback">
    <?php    $form = \yii\bootstrap\ActiveForm::begin([
        'id'            =>  'callback-form'
    ]);

    ?>
    <span class="head">Хотите мы вам перезвоним?</span>
    <?= Html::tag('div',
        /*        $form->field($model, 'phone', \frontend\widgets\MaskedInput::widget([
                    'name'			=>	'phone',
                    'options'		=>	[
                        'class'			=>	'phone-number-input-modal',
                    ],
                    'clientOptions' =>  [
                        'clearIncomplete'   =>  true,
                        'alias'             =>  'phone',
                        'url'               =>  Url::to('/js/phone-codes.json'),
                        'countrycode'       =>  '',
                    ],

                ])).*//*($model, 'phone')->input(\frontend\widgets\MaskedInput::widget([
            'name'			=>	'phone',
            'options'		=>	[
                'class'			=>	'phone-number-input-modal',
            ],
            'clientOptions' =>  [
                'clearIncomplete'   =>  true,
                'alias'             =>  'phone',
                'url'               =>  Url::to('/js/phone-codes.json'),
                'countrycode'       =>  '',
            ],
            'value'         =>  !\Yii::$app->user->isGuest ?
                \Yii::$app->user->identity->phone :
                (\Yii::$app->request->cookies->getValue("customerPhone", false) ?
                    \Yii::$app->request->cookies->getValue("customerPhone") : '')
        ])).*/
        $form->field($model, 'phone')->textInput(['value' => !\Yii::$app->user->isGuest ?
            \Yii::$app->user->identity->phone :
            (\Yii::$app->request->cookies->getValue("customerPhone", false) ?
                \Yii::$app->request->cookies->getValue("customerPhone") : '')]).

        /*$form->field($model, 'question')->textarea().*/
        /*= $form->field($model, 'captcha')->widget(\yii\captcha\Captcha::className());*/
        Html::submitButton('Перезвоните мне', ['class' => 'yellow-button-modal',
            'name' => 'callback-button']), [
            'class' => 'callback-content'
        ]); ?>
</div>
<?php $form->end(); ?>

