<?php

/* @var $this \yii\web\View */
/* @var $content string */

use bobroid\remodal\Remodal;
use common\helpers\Formatter;
use frontend\models\Category;
use frontend\assets\RuLangAsset;
use common\components\SocialButtonWidget;
use frontend\widgets\CartWidget;
use frontend\widgets\LanguageDropdown;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\popover\PopoverX;
use yii\web\View;
use yii\widgets\Pjax;

$this->registerMetaTag(['charset' => Yii::$app->charset]);
$this->registerMetaTag(['name' => 'MobileOptimized', 'content' => '1240']);
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width; initial-scale=1.0']);
$this->registerMetaTag(['name' => 'HandheldFriendly', 'content' => 'false']);

$this->registerLinkTag(['rel' => 'shortcut icon', 'type' => 'image/x-icon', 'href' => '/favicon.ico']);

$cartModal = new \bobroid\remodal\Remodal([
	'cancelButton'		=>	false,
	'confirmButton'		=>	false,
	'closeButton'		=>	false,
	'content'			=>	$this->render('../site/cart'),
	'options'           =>  [
		'id'            =>  'modal-cart',
		'class'         =>  \Yii::$app->cart->itemsCount ? (\Yii::$app->cart->wholesale ? 'wholesale' : 'retail') : 'empty'
	],
	'id'	            =>	'modalCart',
	'addRandomToID'		=>	false,
	'events'			=>	[
		'opening'	    =>	new \yii\web\JsExpression("getCart()")
	]
]);

$callbackSuccessModal = new \bobroid\remodal\Remodal([
	'cancelButton'		=>	false,
	'confirmButton'		=>	false,
	'closeButton'		=>	false,
	'addRandomToID'		=>	false,
	'content'			=>	$this->render('_callback_success'),
	'id'				=>	'callbackSuccessModal',
	'options'			=>  [
		'class'			=>  'callback-success-modal'
	]
]);

$loginModal = new \bobroid\remodal\Remodal([
	'cancelButton'		=>	false,
	'confirmButton'		=>	false,
	'closeButton'		=>	false,
	'addRandomToID'		=>	false,
	'content'			=>	$this->render('parts/_login_modal'),
	'id'				=>	'loginModal',
	'options'			=>  [
		'class'			=>  'login-modal'
	]
]);

$registrationModal = new \bobroid\remodal\Remodal([
	'cancelButton'		=>	false,
	'confirmButton'		=>	false,
	'closeButton'		=>	false,
	'addRandomToID'		=>	false,
	'content'			=>	$this->render('parts/_registration_modal'),
	'id'				=>	'registrationModal',
]);

$js = <<<JS
	if(hasTouch){
		$('body').on('touchmove', function(e){
			e.target.isTouchMoved = true;
		});
	}

	$('body').on(hasTouch ? 'touchend' : 'click', '.item-wish:not(.green)', function(e){
		if(hasTouch && isTouchMoved(e)){ return false; }
		e.preventDefault();
		addToWishlist($(e.currentTarget));
	});

	$('body').on(hasTouch ? 'touchend' : 'click', '.user-data-content .item-wish.green', function(e){
		if(hasTouch && isTouchMoved(e)){ return false; }
		e.preventDefault();
		removeFromWishList($(e.currentTarget));
	});

	$('body').on(hasTouch ? 'touchend' : 'click', '.rating .icon-star', function(e){
		if(hasTouch && isTouchMoved(e)){ return false; }
		e.preventDefault();
		setItemRate($(e.currentTarget));
	});

	$('body').on(hasTouch ? 'touchend' : 'click', '.item-counter .minus:not(.inhibit), .item-counter .plus:not(.inhibit), .remove-item', function(e){
		if(hasTouch && isTouchMoved(e)){ return false; }
		e.preventDefault();
		changeItemCount($(e.currentTarget));
	});

	$('body').on('keydown', '.count', function(e){
        // Allow: backspace, delete, tab, escape, enter and .
        if($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)){
	            if(keysdown[e.keyCode]){
	                return;
	            }
                keysdown[e.keyCode] = true;
                return;
        }
        // Ensure that it is a number and stop the keypress
        if((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)){
            e.preventDefault();
        }else{
            if(keysdown[e.keyCode]){
                e.preventDefault();
                return;
            }
            keysdown[e.keyCode] = true;
        }
	});

	$('body').on('keyup', '.count', function(e){
		if(keysdown[e.keyCode]){
			delete keysdown[e.keyCode];
			changeItemCount($(e.currentTarget));
        }
	});

	$('body').on(hasTouch ? 'touchend' : 'click', '.button.buy', function(e){
		if(hasTouch && isTouchMoved(e)){ return false; }
		e.preventDefault();
		addToCart(e.currentTarget);
	});

	$('body').on(hasTouch ? 'touchend' : 'click', '.button.open-cart', function(e){
		if(hasTouch && isTouchMoved(e)){ return false; }
		e.preventDefault();
		openCart();
	});

	$(document).on('pjax:complete', function(){
		cartScroll();
	});

	cartScroll();

	/* To initialize BS3 tooltips set this below */
	$(function () {
	    $("[data-toggle='tooltip']").tooltip();
	});
	/* To initialize BS3 popovers set this below */
	$(function () {
	    $("[data-toggle='popover']").popover();
	});

	$('body').on('click', function(e){
		if(!$(e.target).closest('.phone-number').length && $(e.target).parents('.popover').length == 0){
			$(".popover").popoverX('hide');
		}
	}).on('complete', '#registrationForm #signupform-phone', function(){
		$("#registrationForm #countryCode").val($(this).inputmask("getmetadata").cc);
	}).on('click', '#continueShopping', function(){
		$("#basketPopover").hide();
	}).on('mouseout', '#basketPopover', function(){
		$("#basketPopover").prop('style', '');
	}).on(hasTouch ? 'touchend' : 'click', '.link-hide', function(e){
	    if(hasTouch && isTouchMoved(e)){
	        return false;
	    }

	    if($(this).attr('data-href')){
	        e.preventDefault();
	        if($(this).attr('data-target') == '_blank'){
	            window.open($(this).attr('data-href'));
	        }else{
	            location.href = $(this).attr('data-href');
	        }
	        return false;
	    }
	});
/*горизонтальная прокрутка менюшки он*/
$(window).scroll(function(){
  $('.sticky-on-scroll, .left-side').css('left',-$(window).scrollLeft());
});


$('#callback-form').on('submit', function(e){
	e.preventDefault();

	var form = $(this);

    if(form.find("#callbackform-phone").val().length != 0){
		$.ajax({
			type: 'POST',
			url: '#callbackSuccessModal',
			data: form.serialize(),
			success: function(){
				 location.href = '#callbackSuccessModal';
			}
		});
    }
});

$('input[data-mask="phone"]').mask("+38(999)999-99-99");

JS;

$GTM = <<<JS
	<!-- Google Tag Manager -->
	(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	    '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-MLV949');
	<!-- End Google Tag Manager -->
JS;

$this->registerJs($js);
$this->registerJs($GTM, View::POS_BEGIN);
$typeaheadStyles = <<<'CSS'


.tt-scrollable-menu .tt-menu{
    max-height: none;
}

.tt-menu .typeahead-list-item{
    font-size: 11px;
}

.tt-menu *{
    color: #3e77aa !important;
    font-family: "Open Sans"
}

.tt-menu h4.media-heading{
    font-size: 14px;
    /*
    white-space: nowrap;
    */
    margin-bottom: 0;
    white-space: normal;
    margin-top: 15px;
    max-height: 32px;
    overflow: hidden;
}

.tt-menu .typeahead-list-item .name{
    color: #000 !important;
    font-size: 13px;
}

.tt-menu .typeahead-list-item .category{

}

.tt-menu .media-left{
    width: 60px !important;
    height: 40px !important;
    overflow: hidden;
}

.tt-menu .typeahead-list-item:last-child .media-left{
	display: none;
}

.tt-menu .typeahead-list-item:last-child .media-heading{
	margin-bottom: 15px;
	margin-left: 30px;
}

.tt-menu .media-left img{
/*    max-width: 80px;
    max-height: 80px;
    margin: auto;*/
    max-width: 120px;
    max-height: 82px;
    margin: auto;
    padding: 7px;
}

.tt-menu{
    border-radius: 5px !important;
    border: 1px solid #fff;
}

.tt-menu .tt-suggestion{
    border-bottom: none;
    padding: 2px;
    /*
    height: 56px;
    */
    cursor: pointer !important;
    height: 85px;
border-bottom: 1px dashed #e0e0e0;
}

.tt-menu .tt-suggestion .price{
	font-size: 16px;
	margin-top: 5px;
	display: block;
	color: #3a3a3a !important;
}

.tt-menu .tt-suggestion .item-code{
    text-align: right;
    position: absolute;
    display: block;
    right: 7px;
    top: 0;
    font-size: 10px;
    color: #888 !important;
    /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#ffffff+0,ffffff+100&1+0,0+100;White+to+Transparent */
    /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#ffffff+0,ffffff+100&0+0,1+20,1+100 */
    background: -moz-linear-gradient(left, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 20%, rgba(255,255,255,1) 100%); /* FF3.6-15 */
    background: -webkit-linear-gradient(left, rgba(255,255,255,0) 0%,rgba(255,255,255,1) 20%,rgba(255,255,255,1) 100%); /* Chrome10-25,Safari5.1-6 */
    background: linear-gradient(to right, rgba(255,255,255,0) 0%,rgba(255,255,255,1) 20%,rgba(255,255,255,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00ffffff', endColorstr='#ffffff',GradientType=1 ); /* IE6-9 */
    padding-left: 30px;
    padding-top: 3px;
    padding-bottom: 2px;
    line-height: 14px;
}

.tt-menu .tt-suggestion:hover .item-code{
    /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#f5f5f5+0,f5f5f5+100&0+0,1+20,1+100 */
    background: -moz-linear-gradient(left, rgba(245,245,245,0) 0%, rgba(245,245,245,1) 20%, rgba(245,245,245,1) 100%); /* FF3.6-15 */
    background: -webkit-linear-gradient(left, rgba(245,245,245,0) 0%,rgba(245,245,245,1) 20%,rgba(245,245,245,1) 100%); /* Chrome10-25,Safari5.1-6 */
    background: linear-gradient(to right, rgba(245,245,245,0) 0%,rgba(245,245,245,1) 20%,rgba(245,245,245,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00f5f5f5', endColorstr='#f5f5f5',GradientType=1 ); /* IE6-9 */
}

.tt-menu .tt-suggestion.tt-cursor, .tt-menu .tt-suggestion.tt-selectable:hover{
    background-color: #fff;
}

.tt-suggestion.tt-selectable:hover .media-heading, .tt-suggestion.tt-selectable:hover .tt-highlight {
    color: #f6a218 !important;
    text-decoration: underline;
}

.tt-menu .typeahead-list-item:hover .media-heading{
    color: #f6a218 !important;
    text-decoration: underline;
}

CSS;

$this->registerCss($typeaheadStyles);

\frontend\assets\PerfectScrollbarAsset::register($this);

$logo = Html::tag('div', '', ['class' => 'logo']);

$this->beginPage();
?>
	<!DOCTYPE html>
	<html lang="<?=Yii::$app->language?>">
	<head>
		<?=Html::csrfMetaTags()?>
		<title><?=Html::encode($this->title)?></title>
		<?php $this->head() ?>
	</head>
	<body>
	<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-MLV949" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<?php $this->beginBody();
	if(\Yii::$app->request->get("serviceMenu") == 'true' && \Yii::$app->request->get("secretKey") == \Yii::$app->params['secretAdminPanelKey']){
		echo $this->render('_admin_menu');
	}
	?>
	<div class="header">
		<div class="top-menu">
			<div class="top-menu-content">
				<div class="items"><a href="/o-nas"><?=\Yii::t('shop', 'О компании')?></a></div>
				<div class="items"><a href="/pomoshch"><?=\Yii::t('shop', 'Помощь')?></a></div>
				<div class="items"><a href="/kontakty"><?=\Yii::t('shop', 'Контакты')?></a></div>
				<!--<div class="blog items">Блог</div>
                <div class="items currency-rate">1 USD - 24.2 UAH</div>-->
				<!--					<div class="personal-account">
                                        <div class="items">РУС</div>-->
				<div class="right-side">
					<?=LanguageDropdown::widget([
						'links'     =>  $this->params['languageLinks'],
						'params'    =>  ['class' => 'languages']
					])?>
					<?php if(\Yii::$app->user->isGuest){
						echo Html::tag('div',
							Html::a(\Yii::t('shop', 'Войти'), '#loginModal', ['class' => 'login']),
							['class' => 'personal-account']);
					}else{
						echo Html::tag('div',
							Html::a(\Yii::$app->user->identity->name.' '.\Yii::$app->user->identity->surname, Url::to
							(['/account/orders', 'language' => \Yii::$app->language]), ['class' => 'account']).
							Html::a(\Yii::t('shop', 'Выйти'), Url::to(['/logout', 'language' => \Yii::$app->language]),
								[
									'data-method'   =>  'post',
									'class'         =>  'logout'
								]
							),
							['class' => 'personal-account']
						);
					} ?>
				</div>
			</div>
		</div>
		<div class="sticky-on-scroll">
			<div class="under-menu">
				<div class="under-menu-content">
					<?=Url::to([Url::home(), 'language' => \Yii::$app->language]) != Url::current() ?
						Html::a($logo, Url::to([Url::home(), 'language' => \Yii::$app->language])) : $logo?>
					<div class="input-style-main">
						<?php
						$form = new \kartik\form\ActiveForm([
							'action'	=>	Url::to(['/search']),
							'method'	=>	'get'
						]);

						$form->begin();

						echo \kartik\typeahead\Typeahead::widget([
							'name'          => 'string',

							'options'       => ['placeholder' => \Yii::t('shop', 'Поиск...')],
							'value'	=>	\Yii::$app->request->get("string"),
							'scrollable'    => true,
							'pluginOptions' => [
								'highlight'     =>  true
							],
							'dataset' => [
								[
									'remote' => [
										'url' => Url::to(['/search']).'/%QUERY',
										'wildcard' => '%QUERY'
									],
									'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
									'display'   => 	'value',
									'limit'		=>	'6',
									'templates' => [
										'notFound'      => $this->render('search/notFound'),
										'footer'		=> new JsExpression("Handlebars.compile('".$this->render('search/footer')."')"),
										'suggestion'    => new JsExpression("Handlebars.compile('".$this->render('search/suggestion')."')")
									]
								]
							]
						]);
						$form->end();
						?>
					</div>
					<?=Html::tag('div',
						Html::tag('div',
							Html::tag('span', \Yii::$app->params['categoryPhoneNumber'], ['class' => 'number']).
							Html::tag('div',
								Html::tag('div',
									Html::tag('div', '', ['class' => 'arrow']).
									Html::tag('div',
										Html::tag('div',
											Html::tag('div', '', ['class' => 'blue-white-phone']).
											Html::tag('div', '0 800 508 208', ['class' => 'semibold']).
											Html::tag('div', '', ['class' => 'free-call'])
										).
										Html::tag('div',
											Html::tag('span', \Yii::t('shop', 'Киев'), ['class' => 'city']).
											Html::tag('span', '044 578 20 16').
											/*Html::tag('span', \Yii::t('shop', 'Одесса'), ['class' => 'city']).
                                            Html::tag('span', '048 735 10 80').*/
											Html::tag('span', \Yii::t('shop', 'моб. МТС'), ['class' => 'city']).
											Html::tag('span', '050 677 54 56').
											Html::tag('span', \Yii::t('shop', 'моб. Киевстар'), ['class' => 'city']).
											Html::tag('span', '067 507 87 73').
											Html::tag('span', \Yii::t('shop', 'моб. Life'), ['class' => 'city']).
											Html::tag('span', '063 578 20 16'),
											[
												'class'	=>	'city-number'
											]
										).
										Html::tag('div',
											Html::tag('span', \Yii::t('shop', 'Время работы call-центра:')).
											Html::tag('span', \Yii::t('shop', 'вт.-вс: с 8.30 до 17.30')).
											Html::tag('span', \Yii::t('shop', 'пн: с 09.00 до 15.00')),
											[
												'class'	=>	'work-time'
											]
										),
										[
											'class' => 'call-back'
										]
									).
									Html::a(\Yii::t('shop', 'Перезвоните мне'), '#callbackModal', ['class'=>'button yellow-button middle-button']),
									[
										'class'	=>	'popover-arrow bottom'
									]),
								[
									'class'	=>	'popover'
								]),
							[
								'class'	=>	'phone-number'
							]).
						Html::tag('div',
							Html::tag('a',
								Html::tag('div', '', ['class' => 'desire-icon']).
								Html::tag('div', \Yii::$app->user->isGuest ? 0 : \Yii::$app->user->identity->wishesCount, ['class' => 'count']).
								Html::tag('span', \Yii::t('shop', 'Желания')),
								[
									'class'	=>	'desire',
									'href'	=>	Url::to(['/account/wish-list', 'language' => \Yii::$app->language])
								]).
							CartWidget::widget(['remodalInstance' => $cartModal]).
							Html::tag('div',
								Html::tag('div', '', ['class' => 'arrow']).
								Html::tag('span',
									\Yii::t('shop', '{username}в Вашей корзине ', [
										'username' => !\Yii::$app->user->isGuest ? \Yii::$app->user->identity->Company.', '
											: ''
									]).Html::a(\Yii::t('shop', '{n, plural, =0{# товаров} =1{# товар} few{#
									товара}	many{# товаров} other{# товар}}', [
										'n'	=>	\Yii::$app->cart->itemsCount
									]), '#modalCart', [
										'class' =>  'items-count-ext'
									])
								).
								Html::tag('span',
									\Yii::t('shop', 'на сумму').'&nbsp;'.Html::tag('span',
										Formatter::getFormattedPrice(\Yii::$app->cart->cartSumm), [
											'class' => 'amount-cart'
										])
								).
								Html::tag('span', \Yii::t('shop', Html::tag('div',
										\Yii::t('shop', 'Вы покупаете по розничным ценам'),
										[
											'class' =>  'basket-retail hidden-text'
										]).
									Html::tag('div', \Yii::t('shop', 'Вы покупаете по оптовым ценам'), [
										'class' =>  'basket-wholesale hidden-text'
									]).
									Html::tag('div', \Yii::t('shop', 'Ваша корзина пуста'), [
										'class' =>  'basket-empty hidden-text'
									])./*'Вы покупаете по оптовым ценам '*/ ''),
									[
										'class' => 'price-info'
									]).
								Html::a(\Yii::t('shop', 'Оформить заказ'), '#modalCart', [
									'class' =>  'button yellow-button middle-button'
								]).
								Html::button(\Yii::t('shop', 'Продолжить покупки'), ['id' => 'continueShopping']),
								[
									'class'	=>	\Yii::$app->cart->itemsCount ? (\Yii::$app->cart->wholesale ? 'in-basket popover-arrow bottom wholesale' :
										'in-basket popover-arrow bottom retail') : 'in-basket popover-arrow bottom empty',
									'id'	=>	'basketPopover'
								]),
							[
								'class'	=>	'desire-basket'
							]),
						[
							'class'	=>	'right-side'
						])?>
				</div>
			</div>
			<?=\frontend\widgets\MainMenuWidget::widget([
				'items'	=>	Category::getMenu()
			])?>
		</div>
	</div>
	<?=Html::tag('div', $content, ['class' => 'main-content'])?>
	<div class="footer">
		<div class="blue-line">
			<div class="footer-content">
				<span class="phone-numbers"> 044 578 20 16 • 067 507 87 73</span>
				<?=Html::tag('div',
					Html::tag('span', \Yii::t('shop', 'Время работы call-центра:')).
					Html::tag('span', \Yii::t('shop', 'с 8.30 до 17:30, без выходных')),
					[
						'class'	=>	'hours'
					]),
				Remodal::widget([
					'cancelButton'		=>	false,
					'confirmButton'		=>	false,
					'addRandomToID'		=>	false,
					'closeButton'		=>  false,
					'id'           		=>  'callbackModal',
					'buttonOptions' =>  [
						'label' =>  'Заказать обратный звонок',
						'class' =>  'yellow-button-new large-button',
					],
					'content'   =>  $this->render('_callback'),
					'options'			=>  [
						'class'			=>  'callback-modal'
					]

				]);
				?>
			</div>
		</div>
		<?=Html::tag('div',
			Html::tag('div',
				Html::tag('span', \Yii::t('shop', 'О компании'), [
					'class'		=>	'link-hide',
					'data-href'	=>	'/o-nas'
				]).
				Html::tag('span', \Yii::t('shop', 'Контакты'), [
					'class'		=>	'link-hide',
					'data-href'	=>	'/kontakty'
				]).
				Html::tag('span', \Yii::t('shop', 'Отзывы о магазине'), [
					'class'		=>	'link-hide',
					'data-href'	=>	'/otzyvy'
				]).
				Html::tag('a', \Yii::t('shop', 'Карта сайта'), [
					'class'		=>	'link-hide',
					'href'		=>	'/map'
				]),
				[
					'class'	=>	'goods-item'
				]).
			Html::tag('div',
				Html::tag('span', \Yii::t('shop', 'Оплата и доставка'), [
					'class'		=>	'link-hide',
					'data-href'	=>	'/o-nas#about-delivery-payment-header',
				]).
				Html::tag('span', \Yii::t('shop', 'Гарантии и возврат'), [
					'class'		=>	'link-hide',
					'data-href'	=>	'/o-nas#about-return-header'
				]),
				[
					'class'	=>	'goods-item'
				]).
			Html::tag('div',
				Html::tag('span', \Yii::t('shop', 'Проблемы с заказом?'), [
					'class'		=>	'link-hide',
					'data-href'	=>	'/kontakty'//хз
				]),
				[
					'class'	=>	'goods-item feedback-link'
				]),
			[
				'class'	=>	'footer-menu'
			]).
		Html::tag('div',
			Html::tag('div',
				Html::tag('div',
					Html::img('/img/site/visa-icon.png').
					Html::img('/img/site/mastercard-icon.png').
					Html::img('/img/site/privat24-icon.png'),
					[
						'class'	=>	'card'
					]).
				Html::tag('div', Html::tag('span', 'Мы в соцсетях:',[]).
					SocialButtonWidget::widget([
						'items' => [
							['linkTag' => 'a', 'link' => 'https://www.facebook.com/krasota.style.com.ua', 'type' => 'facebook'],
							['linkTag' => 'a', 'link' => 'http://vk.com/bizhuteria_optom_ua', 'type' => 'vkontakte'],
							['linkTag' => 'a', 'link' => 'https://twitter.com/krasota_style', 'type' => 'twitter'],
							['linkTag' => 'a', 'link' => 'http://www.odnoklassniki.ru/krasotastyle2', 'type' => 'odnoklassniki'],
							['linkTag' => 'a', 'link' => 'https://plus.google.com/u/0/106125731561025796307?rel=author', 'type' => 'googleplus'],
							['linkTag' => 'a', 'link' => 'https://www.youtube.com/channel/UCuWGRaxroJabTiecC9jAqow/featured', 'type' => 'youtube'],
							['linkTag' => 'a', 'link' => 'https://www.instagram.com/krasota_style.ua/', 'type' => 'instagram'],
						]
					]),
					[
						'class'	=>	'socialNetworks'
					]),
				[
					'class'	=>	'footer-content'
				]),
			[
				'class'	=>	'feedback-block'
			]).
		Html::tag('div',
			Html::tag('span',
				\Yii::t('shop', '© Интернет-магазин «krasota-style™» 2011–{year}', ['year' => date('Y')]),
				[
					'class' => 'left'
				]
			).'&nbsp;'.
			Html::tag('span',
				\Yii::t('shop', 'Дизайн и разработка сайта “krasota-style.ua”',
					[
						'class' => 'right'
					]
				)
			),
			[
				'class'	=>	'footer-content'
			]
		)?>
	</div>
	<?=$cartModal->renderModal(),
	$callbackSuccessModal->renderModal(),
	$loginModal->renderModal(),
	$registrationModal->renderModal();

	RuLangAsset::register($this);

	$this->endBody() ?>
	</body>
	</html>
<?php $this->endPage() ?>