<?php
use common\helpers\Formatter;
use yii\helpers\Html;
use yii\helpers\Url;

echo Html::tag('div',
		Html::tag('span', 'Корзина №', [
			'class'	=>  'number-of-order'
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
		Html::tag('div', \Yii::t('shop', '').
			Html::tag('div', '', [
				'class'   =>  'cross'
			]), [
			'class'                 =>  'cart-close',
			'data-remodal-action'   =>  'close'
		]), [
			'class' =>  'cart-caption'
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
					'class' =>  'amount-cart-discount'
				]).
			Html::tag('div',
				\Yii::t('shop', 'сумма заказа без скидки {realAmount}', [
					'realAmount'   =>  Html::tag('span',
						Formatter::getFormattedPrice(\Yii::$app->cart->cartSumWithoutDiscount), [
							'class' =>  'amount-real'
						])
				]), [
					'class' =>  'amount-cart-real'
				]), [
				'class' =>  'left'
			]).
		Html::tag('div',
			Html::tag('div',
				Html::tag('span', '?', [
					'class'         =>  'question-round-button',
					'data-toggle'   =>  'tooltip',
					'title'    =>  \Yii::t('shop', 'Эта сумма может измениться, в случае если вдруг не будет товаров на складе')
				]).
				Html::tag('div', \Yii::t('shop', 'Предварительная сумма к оплате'), ['class' => 'text']), [
					'class' =>  'amount-cart-text'
				]).
			Html::tag('div', Formatter::getFormattedPrice(\Yii::$app->cart->cartSumm), [
				'class' =>  'amount-cart'
			]), [
				'class' =>  'right'
			]), [
			'class' =>  'cart-footer-top'
		]).
	Html::beginTag('div', ['class' => 'cart-footer-bottom']);

if(!(isset($order) && $order == true)){
	$form = \yii\widgets\ActiveForm::begin([
		'action' => Url::to(['/order', 'language' => \Yii::$app->language])
	]);
	echo Html::tag('div',
			Html::tag('div',
				Html::tag('div',
					\Yii::t('shop', 'Ваш телефон:'), [
						'class' => 'phone-number-text'
					]
				).
				Html::input('text', 'phone', !\Yii::$app->user->isGuest ?
					\Yii::$app->user->identity->phone :
					(\Yii::$app->request->cookies->getValue("customerPhone", false) ?
						\Yii::$app->request->cookies->getValue("customerPhone") : ''),
					[
						'class' => 'phone-number-input-modal',
						'data-mask' => 'phone'
					]
				)
				/*			\frontend\widgets\MaskedInput::widget([
								'name'			=>	'phone',
								'options'		=>	[
									'class'			=>	'phone-number-input-modal',
								],
								'clientOptions' =>  [
									'clearIncomplete'   =>  true,
									'alias'             =>  'phone',
									'url'               =>  Url::to('/js/phone-codes.json'),
									'countrycode'       =>  '',
									/*						'onBeforePaste'           =>  new \yii\web\JsExpression('
																function(){
																	return true;
																}
															')*/
				/*						'oncomplete'           =>  new \yii\web\JsExpression('
											function(){
												}
											}
										') TODO: сделать вывод флага после того, как плагин будет пофикшен*/

				/*],
				'value'         =>  !\Yii::$app->user->isGuest ?
					\Yii::$app->user->identity->phone :
					(\Yii::$app->request->cookies->getValue("customerPhone", false) ?
						\Yii::$app->request->cookies->getValue("customerPhone") : '')
			])*/, [
					'class' => 'phone-number-block'
				]), [
				'class' => 'left'
			]).
		Html::tag('div',
			Html::tag('div',
				Html::button(\Yii::t('shop', 'Заказать в 1 клик'), [
					'type' => 'submit',
					'name' => 'orderType',
					'value' => '1',
					'class' => 'button yellow-button-modal cart-button one-click-order',
					'disabled' => \Yii::$app->cart->cartRealSumm < \Yii::$app->params['domainInfo']['minimalOrderSum'] || \Yii::$app->cart->itemsCount < 1
				]).
				Html::button(\Yii::t('shop', 'Оформить заказ'), [
					'type' => 'submit',
					'name' => 'orderType',
					'value' => '0',
					'class' => 'button yellow-button-modal cart-button form-order',
					'disabled' => \Yii::$app->cart->cartRealSumm < \Yii::$app->params['domainInfo']['minimalOrderSum'] || \Yii::$app->cart->itemsCount < 1
				]), [
					'class' => 'cart-buttons'
				]), [
				'class' => 'right'
			]);
	$form->end();
}
echo Html::endTag('div').
	Html::endTag('div');