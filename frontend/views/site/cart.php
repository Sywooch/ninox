<?php
use yii\helpers\Html;

echo Html::tag('div',
	Html::tag('div', \Yii::$app->cart->itemsCount ?
		(\Yii::$app->cart->wholesale ?
			\Yii::t('shop', 'Вы покупаете по оптовым ценам') :
			(\Yii::t('shop', 'Вы покупаете по розничным ценам').' - '.(\Yii::$app->params['domainInfo']['wholesaleThreshold'] - \Yii::$app->cart->cartWholesaleRealSumm).' '.\Yii::t('shop', 'до опта'))
		) : \Yii::t('shop', 'Ваша корзина пуста'), [
		'class' =>  'cart-message semi-bold'
	]).
	Html::tag('div', 'Продолжить покупки'.
		Html::tag('div', '', [
			'class'   =>  'cross'
		]), [
		'class'                 =>  'cart-close',
		'data-remodal-action'   =>  'close'
	]), [
	'class' =>  'cart-caption'
]);
?>
<div class="cart-description semi-bold">Корзина<span class="cart-number">№1234567890<?=\Yii::$app->cart->cartCode?></span></div>
<?=$this->render('_cart_items', [])?>
<div class="miniFooter">
    <div class="bottomShadow"></div>
	<?php $form = new \yii\bootstrap\ActiveForm([
		'action'	=>	'/order'
	]);
	$form->begin(); ?>
    <!--<form method="POST" action="/order" onkeypress="if(event.keyCode == 13) return false;" onsubmit="submitForm(this); return false;">-->
        <div class="row first">
            <span id="optSumm" class="semi-bold">Сумма заказа по оптовым ценам: <?=\Yii::$app->cart->cartWholesaleSumm?> грн</span>
            <span>Сумма заказа <span id="totalSumm" class="blue"><?=\Yii::$app->cart->cartSumm?> грн</span></span>
        </div>
        <div class="row second">
            <span id="optSummDiff">Ваша корзина пуста</span>
			<span class="extended-info">
				<span id="totalSummWithoutDiscount">сумма заказа без скидки 0 грн</span> <span id="totalDiscount" class="orange">скидка 0 грн</span>
			</span>
        </div>
        <div class="row third">
            <div class="phone">
                <span class="flag"></span>
				<?php

				$maskedInputOptions = [
					'name'			=>	'phone',
					'mask'			=>	'+38-999-999-99-99',
					'options'		=>	[
						'class'			=>	'input_phone',
					]
				];

				if(!\Yii::$app->user->isGuest){
					$maskedInputOptions['value'] 	=	\Yii::$app->user->identity->phone;
				}elseif(\Yii::$app->request->cookies->getValue("customerPhone", false)){
					$maskedInputOptions['value']	= substr(\Yii::$app->request->cookies->getValue("customerPhone"), 2, strlen(\Yii::$app->request->cookies->getValue("customerPhone")));
				}

				echo \yii\widgets\MaskedInput::widget($maskedInputOptions)?>
				<!--<input placeholder="+_(___)___-____" class="input_phone" name="phone" value="" type="text">-->
            </div>
			<?=Html::button(\Yii::t('site', 'Заказать в 1 клик'), [
				'type'	=>	'submit',
				'name'	=>	'orderType',
				'value'	=>	'1',
				'class'	=>	'yellowButton largeButton'
			]),
			Html::button(\Yii::t('site', 'Оформить заказ'), [
				'type'	=>	'submit',
				'name'	=>	'orderType',
				'value'	=>	'0',
				'class'	=>	'yellowButton largeButton'
			])?>
            <!--<input id="one_click" class="yellowButton largeButton" name="orderType" value="Заказать в 1 клик" data-disabled="true" onclick="this.form.oneClickOrder = true" type="submit">
            <input id="checkout" class="yellowButton largeButton" value="Оформить заказ" data-disabled="true" onclick="this.form.oneClickOrder = false" type="submit">
            <input value="true" name="doOrder" type="hidden">-->
        </div>
    <!--</form>-->
	<?php $form->end(); ?>
</div>