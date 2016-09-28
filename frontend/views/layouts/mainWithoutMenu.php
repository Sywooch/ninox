<?php

/* @var $this \yii\web\View */
/* @var $content string */

use bobroid\remodal\Remodal;
use common\helpers\Formatter;
use frontend\assets\FrontEndAsset;
use frontend\widgets\Breadcrumbs;
// use frontend\assets\PerfectScrollbarAsset;
use frontend\models\BannersCategory;
use frontend\models\Category;
use common\components\SocialButtonWidget;
use frontend\widgets\CartWidget;
use frontend\widgets\LanguageDropdown;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\popover\PopoverX;
use yii\web\View;
use yii\widgets\Pjax;
use evgeniyrru\yii2slick\Slick;

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
		'id'    =>  'modal-cart',
		'class' =>  \Yii::$app->cart->itemsCount ? (\Yii::$app->cart->wholesale ? 'wholesale' : 'retail') : 'empty',
	],
	'id'	            =>	'modalCart',
	'addRandomToID'		=>	false,
	'events'			=>	[
		'opening'   =>	new \yii\web\JsExpression("getCart()")
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
		'class' =>  'callback-success-modal'
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
		'class' =>  'login-modal'
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
$sliderBanners = \frontend\helpers\SliderHelper::renderItems(BannersCategory::findOne(['alias' => 'slider_v3'])->banners);

$pjax = \yii\widgets\Pjax::begin([
	'id'            =>  'pjax-category',
	'linkSelector'  =>  '.sub-categories li > a, .breadcrumb li > a',
	'timeout'       =>  '5000'
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
		$('#modal-cart').remodal().open();
	});

	$('body').on(hasTouch ? 'touchend' : 'click', '.icon-quick-view, .item-navigation', function(e){
		if(hasTouch && isTouchMoved(e)){ return false; }
		e.preventDefault();
		var itemID = $(e.currentTarget).closest('.hovered').data('key') || $(e.currentTarget).data('key');
		if(!itemID){
			return false;
		}
		var item = $('.hovered[data-key="' + itemID + '"]');
		if(!item){
			return false;
		}
		$('#modal-quick-view .icon-circle-left').data('key', (item.prev().data('key') || $('.hovered').last().data('key')));
		$('#modal-quick-view .icon-circle-right').data('key', (item.next().data('key') || $('.hovered').first().data('key')));
		$('#modal-quick-view .item').empty().addClass('icon-loader');
		$('#modal-quick-view.remodal-is-closed').length > 0 ? $('#modal-quick-view.remodal-is-closed').remodal().open() : '';
		$.ajax({
			type: 'POST',
			url: item.find('a').attr('href'),
			success: function(data){
				$('#modal-quick-view .item').removeClass('icon-loader').html(data);
			}
		});
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

	$('body').on('complete', '#registrationForm #signupform-phone', function(){
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
	/*$(window).scroll(function(){
		$('.sticky-on-scroll, .left-side').css('left',-$(window).scrollLeft());
	});*/

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

    $('.up').click(function(){
        $('body, html, document').animate({ scrollTop: 0 }, 1000); //for all browser include safari
        return false;
    });

   /* $('.sticky-on-scroll')
    .on('sticky-start', function(){
        $('.up').addClass('visible');
    })
    .on('sticky-end', function(){
        $('.up').removeClass('visible');
	});*/

JS;

$GTM = <<<JS
	<!-- Google Tag Manager -->
	(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	    '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-PSQTC8');
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

//PerfectScrollbarAsset::register($this);
FrontEndAsset::register($this);

$logo = Html::tag('div', '', ['class' => 'logo']);

$this->beginPage()?>
	<!DOCTYPE html>
	<html lang="<?=Yii::$app->language?>">
	<head>
		<meta charset="UTF-8">
		<?=Html::csrfMetaTags()?>
		<title><?=Html::encode($this->title)?></title>
		<?php $this->head() ?>
	</head>
	<body>
	<?php $this->beginBody() ?>
	<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-PSQTC8" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<?php
	if(\Yii::$app->request->get("serviceMenu") == 'true' && \Yii::$app->request->get("secretKey") == \Yii::$app->params['secretAdminPanelKey']){
		echo $this->render('_admin_menu');
	}
	?>
	<div class="header clear-fix">
		<!--<div class="top-menu"> <!-- Использую уже отверстанное меню nav вместо этого-->
			<!--<div class="top-menu-content">
				<div class="items"><?=Html::a(\Yii::t('shop', 'О компании'), Url::to(['/o-nas']))?></div>
				<div class="items"><?=Html::a(\Yii::t('shop', 'Помощь'), Url::to(['/pomoshch']))?></div>
				<div class="items"><?=Html::a(\Yii::t('shop', 'Контакты'), Url::to(['/kontakty']))?></div>
				<!--<div class="blog items">Блог</div>
                <div class="items currency-rate">1 USD - 24.2 UAH</div>-->
				<!--					<div class="personal-account">
                                        <div class="items">РУС</div>-->
				<!--<div class="right-side">
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
							(['/account/orders']), ['class' => 'account']).
							Html::a(\Yii::t('shop', 'Выйти'), Url::to(['/logout']),
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
		</div>-->
		<nav class="navbar navbar-inverse navbar-fixed-top navbar-pages" role="navigation">
			<div class="container">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="/"><img src="/img/logo_color.png"></a>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<div class="tel"><span class="prefix">+38</span> (044) 466-60-44</div>
					<ul class="nav navbar-nav navbar-right">
						<li><?=Html::a(\Yii::t('shop', 'О нас'), Url::to(['/o-nas']))?></li>
						<li><?=Html::a(\Yii::t('shop', 'Сотрудничество'), Url::to(['/pomoshch']))?></li>
						<li><?=Html::a(\Yii::t('shop', 'Наши контакты'), Url::to(['/kontakty']))?></li>
					</ul>
				</div>
				<!-- /.navbar-collapse -->
			</div>
			<!-- /.container -->
		</nav>

		<div class="sticky-on-scroll">
			<!--<div class="under-menu">
				<div class="under-menu-content">
					<?=Url::to([Url::home()]) != Url::current() ?
						Html::a($logo, Url::to([Url::home()])) : $logo?>
					<div class="input-style-main">
						<?=\kartik\typeahead\Typeahead::widget([
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
										'url' => Url::to(['/search/%QUERY']),
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
						]);?>
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
									'href'	=>	Url::to(['/account/wish-list'])
								]).
							CartWidget::widget().
							Html::tag('div',
								Html::tag('div', '', ['class' => 'arrow']).
								Html::tag('span',
									\Yii::t('shop', '{username}в Вашей корзине ', [
										'username' => !\Yii::$app->user->isGuest ? \Yii::$app->user->identity->Company.', '
											: ''
									]).Html::a(\Yii::t('shop', '{n, plural, =0{# товаров} =1{# товар} few{#
									товара}	many{# товаров} other{# товар}}', [
										'n'	=>	\Yii::$app->cart->itemsCount
									]), '#modal-сart', [
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
			</div>-->
			<?=Html::tag('div', Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]), ['class' => 'content breadcrumbsCont'])?>
		</div>
	</div>
	<?=Html::tag('div', $content, ['class' => 'main-content']).
	Html::tag('div', Html::tag('span', Yii::t('shop', 'Вверх')), ['class' => 'up'])?>
	<!-- Footer -->
	<footer>
		<div class="container">
			<div class="row">
				<div class="col-md-4">
					<span class="copyright">ninox.com.ua © 2016</span>
					<div class="copyright-mid">Мы предлагаем большой выбор высококачественных креплений для изделий из стекла и табличек производства компании FORWERK (Германия)</div>
					<span class="de">citinox.de</span>
				</div>
				<div class="col-md-4">
					<section class="row">
						<div class="col-lg-12">
							<h2 class="page-header">Каталог товаров</h2>
						</div>
						<div class="col-md-6 col-sm-6 portfolio-item">
							<a href="/kreplenie-k-stene">
								<div class="portfolio-caption">
									<i class="fi fi-fw fi-type-1"></i>
									К стене
								</div>
							</a>
						</div>
						<div class="col-md-6 col-sm-6 portfolio-item">
							<a href="/flazhkovoe-kreplenie">
								<div class="portfolio-caption">
									<i class="fi fi-fw fi-type-2"></i>
									Флажковое
								</div>
							</a>
						</div>
						<div class="col-md-6 col-sm-6 portfolio-item">
							<a href="/sterzhnevoe-kreplenie">
								<div class="portfolio-caption">
									<i class="fi fi-fw fi-type-3"></i>
									Стержневое
								</div>
							</a>
						</div>
						<div class="col-md-6 col-sm-6 portfolio-item">
							<a href="/trosikovoe-kreplenie">
								<div class="portfolio-caption">
									<i class="fi fi-fw fi-type-4"></i>
									Тросиковое
								</div>
							</a>
						</div>
						<div class="col-md-6 col-sm-6 portfolio-item">
							<a href="/svobodnostoyashchie">
								<div class="portfolio-caption">
									<i class="fi fi-fw fi-type-5"></i>
									Свободностоящие
								</div>
							</a>
						</div>
						<div class="col-md-6 col-sm-6 portfolio-item">
							<a href="/dopolnitelnye-detali">
								<div class="portfolio-caption">
									<i class="fi fi-fw fi-type-6"></i>
									Детали
								</div>
							</a>
						</div>
						<div class="col-md-6 col-sm-6 portfolio-item">
							<a href="/alinox">
								<div class="portfolio-caption">
									<i class="fi fi-fw fi-type-7"></i>
									Alinox
								</div>
							</a>
						</div>
					</section>
				</div>
				<div class="col-md-4 adress">
					<span>г. Киев</span>
					<span>ул. Магнитогорская, 1Б, офис 212А</span>
					<span class="tel">тел.: +380 44 466 60 44</span>
				</div>
			</div>
		</div>
	</footer>
	<?=$cartModal->renderModal(),
	$callbackSuccessModal->renderModal(),
	$loginModal->renderModal(),
	$registrationModal->renderModal();?>
	<script>
		$('.carousel').carousel({
			interval: 5000 //changes the speed
		})
	</script>
	<?php $this->endBody() ?>
	</body>
</html>
<?=$this->endPage()?>
