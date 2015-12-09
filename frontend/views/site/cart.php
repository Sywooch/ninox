<?php
use common\helpers\GoodHelper;
use common\helpers\PriceRuleHelper;
use yii\helpers\Html;
?>
<div class="block">
    <div class="caption">
        <div class="description">
            <span id="description">Корзина</span>
            <span id="description2">Ваша корзина пуста</span>
        </div>
        <div class="continue">
            <span class="close-cart" data-remodal-action="close">
                <span>Продолжить покупки</span>
                <span></span>
            </span>
        </div>
    </div>
    <div class="top-shadow"></div>
    <div class="cart-content">
	    <?php
	        $w = new \app\widgets\CartItemsCounterWidget();
	        $helper = new PriceRuleHelper();
	    ?>
        <?=\yii\grid\GridView::widget([
            'dataProvider'  =>  $dataProvider,
            'emptyText'     =>  \Yii::t('shop', 'Ваша корзина пуста!'),
	        'showHeader'    =>  false,
	        'columns'       =>  [
		        [
			        'class'             =>  \yii\grid\SerialColumn::className(),
			        'contentOptions'    =>  [
				        'class'         =>  'sequence-number',
			        ],
		        ],
		        [
			        'format'        =>  'html',
			        'value'         =>  function($model){
					        return Html::tag('img', '', ['src'  =>  \Yii::$app->params['cdn-link'].'/img/catalog/sm/'.$model->ico, 'alt'  =>  $model->Name.' '.\Yii::t('shop', 'от интернет магазина Krasota-Style.ua')]);
				        }
		        ],
		        [
			        'format'        =>  'html',
			        'value'         =>  function($model) use (&$helper){
					        $model = $helper->recalc($model);
					        return Html::tag('div', $model->Name, ['class'  =>  'item-name blue']).
						        Html::tag('div', \Yii::t('shop', 'Код').': '.$model->Code, ['class'   =>  'item-code']).
						        Html::tag('div', $model->wholesale_price, ['class'   =>  'item-price']).
						        Html::tag('div', $model->retail_price, ['class'   =>  'item-price']);
				        }
		        ],
		        [
			        'class'         =>  \yii\grid\ActionColumn::className(),
			        'buttons'       =>  [
				        'plus'  =>  function($url, $model, $key) use(&$w){
							$w->setOptions([
								'itemID'    =>  $model->ID,
								'value'     =>  $model->inCart ? $model->inCart : 1,
								'store'     =>  $model->isUnlimited ? 1000 : $model->count,
								'inCart'    =>  $model->inCart,
							]);
						    return $w->renderPlus();
					    },
				        'minus'  =>  function($url, $model, $key) use(&$w){
					        $w->setOptions([
						        'itemID'    =>  $model->ID,
						        'value'     =>  $model->inCart ? $model->inCart : 1,
						        'store'     =>  $model->isUnlimited ? 1000 : $model->count,
						        'inCart'    =>  $model->inCart,
					        ]);
						    return $w->renderMinus();
					    },
				        'counter'  =>  function($url, $model, $key) use(&$w){
						    $w->setOptions([
							    'itemID'    =>  $model->ID,
							    'value'     =>  $model->inCart ? $model->inCart : 1,
							    'store'     =>  $model->isUnlimited ? 1000 : $model->count,
							    'inCart'    =>  $model->inCart,
						    ]);

						    return $w->renderInput();
					    },
				        'delete'  =>  function($url, $model, $key) use(&$w){
						    $w->setOptions([
							    'itemID'    =>  $model->ID,
							    'value'     =>  $model->inCart ? $model->inCart : 1,
							    'store'     =>  $model->isUnlimited ? 1000 : $model->count,
							    'inCart'    =>  $model->inCart,
						    ]);

						    return $w->renderDelete();
					    },
			        ],
			        'template'      =>  Html::tag('div', '{minus}{counter}{plus}', ['class' => 'counter']).'{delete}'

		        ],
		        [
			        'format'        =>  'html',
			        'contentOptions'    =>  [
				        'class'         =>  'sums',
			        ],
			        'value'         =>  function($model){
					        return Html::tag('div', GoodHelper::getPriceFormat($model->retail_real_price * $model->inCart).' '.\Yii::$app->params['domainInfo']['currencyShortName'], [
						        'class' =>  'old-sum semi-bold blue'.($model->discountType == 0 ? ' disabled' : ''),
					        ]).
						    Html::tag('div', GoodHelper::getPriceFormat($model->retail_price * $model->inCart).' '.\Yii::$app->params['domainInfo']['currencyShortName'], [
							    'class' =>  'current-sum blue'
						    ]);
				        }
		        ]
	        ],
        ])?>
    </div>
    <div class="miniFooter">
        <div class="bottomShadow"></div>
		<?php $form = new \yii\bootstrap\ActiveForm([
			'action'	=>	'/order'
		]);
		$form->begin(); ?>
        <!--<form method="POST" action="/order" onkeypress="if(event.keyCode == 13) return false;" onsubmit="submitForm(this); return false;">-->
            <div class="row first">
                <span id="optSumm" class="semi-bold">Сумма заказа по оптовым ценам: 0 грн</span>
                <span>Сумма заказа <span id="totalSumm" class="blue">0 грн</span></span>
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
</div>