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

.cart-close{
	float: right;
/*	color: #4b6350;*/
    /*
	background: #a2e0b0;
    */
/*	font-size: 13px;
	padding: 0 5px 0 10px;*/
	cursor: pointer;
	height: auto;
	width: 35px;
}

.cart-close .cross{
	width: 35px;
	height: 35px;
	border-radius: 50%;
	background: #c6c6c6;
	position: relative;
	display: inline-block;
	vertical-align: middle;
    /*
	margin-left: 5px;
    */
}

.cart-close .cross:before, .cart-close .cross:after{
	content: '';
	position: absolute;
	display: inline-block;
	height: 3px;
	width: 25px;
	top: 49%;
	margin-top: -1px;
	left: 32%;
	margin-left: -6px;
	background: #fff;
}

.cart-close .cross:before{
	-webkit-transform: rotate(45deg);
	-moz-transform: rotate(45deg);
	-ms-transform: rotate(45deg);
	-o-transform: rotate(45deg);
	transform: rotate(45deg);
}

.cart-close .cross:after{
	-webkit-transform: rotate(135deg);
	-moz-transform: rotate(135deg);
	-ms-transform: rotate(135deg);
	-o-transform: rotate(135deg);
	transform: rotate(135deg);
}


CSS;

$this->registerCss($css);

$model = new \frontend\models\CallbackForm();

?>
<div class="callback">
    <?php    $form = \yii\bootstrap\ActiveForm::begin([
        'id'            =>  'callback-form'
    ]);
    ?>
    <?=
    Html::tag('div',
        Html::tag('span', 'Хотите мы вам перезвоним?', [
            'class'     =>  ''
        ]).
        Html::tag('div', \Yii::t('shop', '').
            Html::tag('div', '', [
                'class'   =>  'cross'
            ]), [
            'class'                 =>  'cart-close',
            'data-remodal-action'   =>  'close'
            ]),[
            'class'     =>  'head'
        ]).

    Html::tag('div',
        $form->field($model, 'phone')->textInput(['value' => !\Yii::$app->user->isGuest ?
            \Yii::$app->user->identity->phone :
            (\Yii::$app->request->cookies->getValue("customerPhone", false) ?
                \Yii::$app->request->cookies->getValue("customerPhone") : '')]).

        Html::submitButton('Перезвоните мне', ['class' => 'yellow-button-modal',
            'name'                  =>  'callback-button',
            'href'                  =>  '#callbackSuccess',
        ]), [
            'class' => 'callback-content'
        ])?>
</div>
<?php $form->end(); ?>

