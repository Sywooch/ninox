<?php
use common\helpers\Formatter;
use yii\helpers\Html;

echo Html::tag('div',
	Html::tag('div', \Yii::t('shop', 'Ваша корзина пуста'), [
		'class' =>  'cart-message cart-message-empty semi-bold'
	]).
	Html::tag('div',
		\Yii::t('shop', 'Вы покупаете по розничным ценам - {wholesaleRemind} до опта', [
			'wholesaleRemind'   =>  Html::tag('span',
					Formatter::getFormattedPrice(\Yii::$app->params['domainInfo']['wholesaleThreshold'] - \Yii::$app->cart->cartWholesaleRealSumm), [
						'class' =>  'amount-remind'
					])
		]), [
		'class' =>  'cart-message cart-message-retail semi-bold'
	]).
	Html::tag('div', \Yii::t('shop', 'Вы покупаете по оптовым ценам'), [
		'class' =>  'cart-message cart-message-wholesale semi-bold'
	]).
	Html::tag('div', \Yii::t('shop', 'Продолжить покупки').
		Html::tag('div', '', [
			'class'   =>  'cross'
		]), [
		'class'                 =>  'cart-close',
		'data-remodal-action'   =>  'close'
	]), [
	'class' =>  'cart-caption'
]).
Html::tag('div', \Yii::t('shop', 'Корзина').
	Html::tag('span', '№'.\Yii::$app->cart->cartCode, [
		'class' =>  'cart-number'
	]), [
	'class' =>  'cart-description semi-bold'
]).
$this->render('_cart_items');
echo Html::beginTag('div', ['class' => 'cart-footer']).
	Html::tag('div',
		Html::tag('div',
			Html::tag('div',
				\Yii::t('shop', 'Ваша скидка {discount}', [
					'discount'   =>  Html::tag('span',
							Formatter::getFormattedPrice(\Yii::$app->cart->cartSumWithoutDiscount - \Yii::$app->cart->cartSumm), [
								'class' =>  'amount-discount'
							])
				]), [
				'class' =>  'amount-cart-discount bold font-size-20px'
			]).
			Html::tag('div',
				\Yii::t('shop', 'сумма заказа без скидки {realAmount}', [
					'realAmount'   =>  Html::tag('span',
							Formatter::getFormattedPrice(\Yii::$app->cart->cartSumWithoutDiscount), [
								'class' =>  'amount-real'
							])
				]), [
				'class' =>  'amount-cart-real font-size-13px'
			]), [
			'class' =>  'left'
		]).
		Html::tag('div',
			Html::tag('div',
				Html::tag('span', '?', [
					'class'         =>  'question-round-button',
					'data-toggle'   =>  'tooltip',
					'data-title'    =>  \Yii::t('shop', 'Эта сумма может измениться, в случае если вдруг не будет товаров на складе')
				]).
				\Yii::t('shop', 'Предварительная сумма к оплате'), [
				'class' =>  'amount-cart-text font-size-13px'
			]).
			Html::tag('div', Formatter::getFormattedPrice(\Yii::$app->cart->cartSumm), [
				'class' =>  'amount-cart bold font-size-28px'
			]), [
			'class' =>  'right'
		]), [
		'class' =>  'cart-footer-top'
	]).
	Html::beginTag('div', ['class' => 'cart-footer-bottom']);
	$form = new \yii\bootstrap\ActiveForm([
		'action'	=>	'/order'
	]);
	$form->begin();
		$maskedInputOptions = [
			'name'			=>	'phone',
			'mask'			=>	'+38-999-999-99-99',
			'options'		=>	[
				'class'			=>	'phone-number',
			]
		];

		if(!\Yii::$app->user->isGuest){
			$maskedInputOptions['value'] 	=	\Yii::$app->user->identity->phone;
		}elseif(\Yii::$app->request->cookies->getValue("customerPhone", false)){
			$maskedInputOptions['value']	=   \Yii::$app->request->cookies->getValue("customerPhone");
		}

		echo Html::tag('div',
			Html::tag('div',
				Html::tag('div',
					\Yii::t('shop', 'Введите ваш телефон:'), [
					'class' =>  'phone-number-text'
				]).
				\yii\widgets\MaskedInput::widget($maskedInputOptions), [
				'class' =>  'phone-number-block'
			]), [
			'class' =>  'left'
		]).
		Html::tag('div',
			Html::tag('div',
				Html::button(\Yii::t('site', 'Оформить заказ'), [
					'type'	    =>	'submit',
					'name'	    =>	'orderType',
					'value'	    =>	'0',
					'class'	    =>	'yellow-button cart-button form-order',
					'disabled'  =>  \Yii::$app->cart->cartRealSumm < \Yii::$app->params['domainInfo']['minimalOrderSum'] || \Yii::$app->cart->itemsCount < 1
				]).
				Html::button(\Yii::t('site', 'Заказать в 1 клик'), [
					'type'	    =>	'submit',
					'name'	    =>	'orderType',
					'value'	    =>	'1',
					'class'	    =>	'cart-button one-click-order',
					'disabled'  =>  \Yii::$app->cart->cartRealSumm < \Yii::$app->params['domainInfo']['minimalOrderSum'] || \Yii::$app->cart->itemsCount < 1
				]), [
					'class' =>  'cart-buttons'
				]), [
			'class' =>  'right'
		]);
	$form->end();
echo Html::endTag('div').
Html::endTag('div');