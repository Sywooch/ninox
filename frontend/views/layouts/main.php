<?php

/* @var $this \yii\web\View */
/* @var $content string */

use bobroid\remodal\Remodal;
use common\helpers\Formatter;
use frontend\models\Category;
use frontend\assets\RuLangAsset;
use common\components\SocialButtonWidget;
use frontend\widgets\CartWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\popover\PopoverX;

$this->registerMetaTag(['charset' => Yii::$app->charset]);
$this->registerMetaTag(['name' => 'MobileOptimized', 'content' => '1240']);
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width; initial-scale=1.0']);
$this->registerMetaTag(['name' => 'HandheldFriendly', 'content' => 'false']);

$this->registerLinkTag(['rel' => 'shortcut icon', 'type' => 'image/x-icon', 'href' => '/favicon.ico']);

/*
 * это нужно?
 */
$userCart = \common\models\Cart::findOne(['cartCode' => \Yii::$app->request->cookies->get('cartCode')]);

if(empty($userCart)){
	$userCart = new \common\models\Cart();
}

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

$loginModal = new \bobroid\remodal\Remodal([
	'cancelButton'		=>	false,
	'confirmButton'		=>	false,
	'closeButton'		=>	false,
	'addRandomToID'		=>	false,
	'content'			=>	$this->render('parts/_login_modal'),
	'id'				=>	'loginModal',
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

	$('body').on(hasTouch ? 'touchend' : 'click', '.item-wish', function(e){
		if(hasTouch && isTouchMoved(e)){ return false; }
		e.preventDefault();
		addToWishlist($(e.currentTarget));
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
});

JS;

$this->registerJs($js);
$typeaheadStyles = <<<'CSS'


.tt-scrollable-menu .tt-menu{
    max-height: none;
}

.tt-menu .typeahead-list-item{
    font-size: 11px;
}

.tt-menu *{
    color: #000 !important;
    font-family: "Open Sans"
}

.tt-menu h4.media-heading{
    font-size: 13px;
    white-space: nowrap;
    margin-bottom: 0;
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

.tt-menu .media-left img{
    max-width: 80px;
    max-height: 80px;
}

.tt-menu{
    border-radius: 5px !important;
    border: 1px solid #fff;
}

.tt-menu .tt-suggestion{
    border-bottom: none;
    padding: 2px;
    height: 56px;
    cursor: pointer !important;
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

CSS;

$this->registerCss($typeaheadStyles);

\frontend\assets\PerfectScrollbarAsset::register($this);

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
		<?php $this->beginBody();
		if(\Yii::$app->request->get("serviceMenu") == 'true' && \Yii::$app->request->get("secretKey") == \Yii::$app->params['secretAdminPanelKey']){
			echo $this->render('_admin_menu');
		}
		?>
		<div class="header">
			<div class="top-menu">
				<div class="top-menu-content">
					<div class="items"><a href="/o-nas">О компании</a></div>
					<div class="items"><a href="/pomoshch">Помощь</a></div>
					<div class="items"><a href="/kontakty">Контакты</a></div>
					<div class="blog items">Блог</div>
					<div class="items currency-rate">1 USD - 24.2 UAH</div>
					<div class="personal-account">
						<div class="items">РУС</div>
						<?php if(\Yii::$app->user->isGuest){
							echo Html::tag('div', Html::a(\Yii::t('shop', 'Войти'), '#loginModal'), ['class' => 'items']);
						}else{
							echo Html::tag('div', Html::a(\Yii::t('shop', 'Личный кабинет'), Url::to('account')).Html::a('Выйти', Url::to('/logout'), [
									'data-method'   =>  'post'
								]), ['class' => 'items account-icon']);
						} ?>
					</div>
				</div>
			</div>
			<div class="sticky-on-scroll">
				<div class="under-menu">
					<div class="under-menu-content">
						<a href="/"><div class="logo"></div></a>
						<div class="input-style-main">
							<?php
							$form = new \kartik\form\ActiveForm([
								'action'	=>	Url::to(['/search']),
								'method'	=>	'get'
							]);

							$form->begin();

							echo \kartik\typeahead\Typeahead::widget([
								'name'          => 'string',
								'options'       => ['placeholder' => 'Поиск'],
								'value'	=>	\Yii::$app->request->get("string"),
								'scrollable'    => true,
								'pluginOptions' => [
									'highlight'     =>  true
								],
								'dataset' => [
									[
										'remote' => [
											'url' => Url::to(['/search']).'?string=%QUERY',
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
							]),
							\yii\helpers\Html::button('Найти', [
								'type'  =>  'submit',
								'class' =>  'blue-button small-button ',
								'id'    =>  'submit'
							]);
							$form->end();
							?>
						</div>
						<div class="phone-number">
							<?=PopoverX::widget([
								'header'    => '',
								'placement' => PopoverX::ALIGN_BOTTOM,
								'content'   =>
									'<div class="call-back">
										<div>
											<div class="blue-white-phone"></div>
											<span class="semibold">0 800 508 208</span>
											<span class="free-call">бесплатно со стационарных</span>
										</div>
										<div class="city-number">
											<span class="city">Киев</span>
											<span>044 232 82 20</span>
											<span class="city">Одесса</span>
											<span>048 735 10 80</span>
											<span class="city">моб. МТС</span>
											<span>050 677 54 56</span>
											<span class="city">моб. Киевстар</span>
											<span>067 507 87 73</span>
											<span class="city">моб. Life</span>
											<span>063 334 49 15</span>
										</div>
										<div class="work-time">
											<span class="">
												Время работы call-центра:
											</span>
											<span class="">
												вт.-вс: с 9.00 до 18.00
											</span>
											<span class="">
												пн: с 9.00 до 15.00
											</span>
										</div>
									</div>',
								'footer' => Html::a(\Yii::t('shop', 'Перезвоните мне'), '#callbackModal', ['class'=>'button yellow-button middle-button']),
								'toggleButton' => ['tag' => 'span', 'label' => '(044) 257-45-54', 'class'=>'number'],
							])?>
						</div>
						<div class="desire-basket">
							<div class="desire">
								<div class="desire-icon"></div>
								<div class="count">5</div>
								<span>Желания</span>
							</div>
							<?=CartWidget::widget(['remodalInstance' => $cartModal])?>
							<div class="in-basket popover-arrow bottom">
								<div class="arrow"></div>
								<span>
									<?=\Yii::t('shop', '{username}в Вашей корзине ', [
										'username' => !\Yii::$app->user->isGuest ? \Yii::$app->user->identity->Company.', '
									: ''
									]).Html::a(\Yii::t('shop', '{n, plural, =0{# товаров} =1{# товар} few{#
									товара}	many{# товаров} other{# товар}}', [
										'n'	=>	\Yii::$app->cart->itemsCount
									]), '#modalCart', [
										'class' =>  'items-count-ext'
									])
									?>
								</span>
								<span>на сумму <?=Html::tag('span',
										Formatter::getFormattedPrice(\Yii::$app->cart->cartSumm), [
											'class' => 'amount-cart'
										])?>
								</span>
								<span class="price-info">Вы покупаете по оптовым ценам</span>
								<?=Html::a(\Yii::t('shop', 'Оформить заказ'), '#modalCart', [
									'class' =>  'button yellow-button middle-button'
								])?>
								<a onclick="this.parentNode.style.display = 'none'">Продолжить покупки</a>
							</div>
						</div>
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
					<span class="phone-numbers">044 123 45 67 • 067 123 45 67</span>
					<div class="hours">
						<span>
							Время работы call-центра:
						</span>
						<span>
							с 8.00 до 17:30, без выходных
						</span>
					</div>
					<?php	echo Remodal::widget([
						'cancelButton'		=>	false,
						'confirmButton'		=>	false,
						'addRandomToID'		=>	false,
						'id'           		=>  'callbackModal',
						'buttonOptions' =>  [
							'label' =>  'Заказать обратный звонок',
							'class' =>  'yellow-button large-button',
						],
						'content'   =>  $this->render('_callback'),

					])
					?>
				</div>
			</div>
			<div class="footer-menu">
				<div class="goods-item">
					<?=Html::tag('span', \Yii::t('shop', 'О компании'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/o-nas'
					]),
					Html::tag('span', \Yii::t('shop', 'Контакты'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/kontakty'
					]),
					Html::tag('span', \Yii::t('shop', 'Вакансии'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/vakansii'
					]),
					Html::tag('span', \Yii::t('shop', 'Блог'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/blog'
					]),
					Html::tag('span', \Yii::t('shop', 'Отзывы о магазине'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/otzyvy'
					]),
					Html::tag('a', \Yii::t('shop', 'Карта сайта'), [
						'class'		=>	'link-hide',
						'href'		=>	'/map'
					])?>
				</div>
				<div class="goods-item">
					<?=Html::tag('span', \Yii::t('shop', 'Услуги'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/status-zakaza'//хз
					]),
					Html::tag('span', \Yii::t('shop', 'Акции'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/akcii'
					]),
					Html::tag('span', \Yii::t('shop', 'Гарантии'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/garantii'
					]),
					Html::tag('span', \Yii::t('shop', 'Бонусная программа'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/kontakty'//хз
					])
					?>
				</div>
				<div class="goods-item">
					<?=Html::tag('span', \Yii::t('shop', 'Как заказать'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/pomoshch'
					]),
					Html::tag('span', \Yii::t('shop', 'Оплата'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/oplata'
					]),
					Html::tag('span', \Yii::t('shop', 'Доставка'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/dostavka'
					]),
					Html::tag('span', \Yii::t('shop', 'Возврат товара'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/vozvrat-i-obmen'
					])
					?>
				</div>
				<div class="goods-item feedback-link">
					<?=Html::tag('span', \Yii::t('shop', 'Обратная связь'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/vakansii'//хз
					]),
					Html::tag('span', \Yii::t('shop', 'Проблемы с заказом?'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/kontakty'//хз
					])
					?>
				</div>
			</div>
			<div class="feedback-block">
				<div class="footer-content">
					<div class="card">
						<img src="/img/site/visa-icon.png">
						<img src="/img/site/mastercard-icon.png">
						<img src="/img/site/privat24-icon.png">
					</div>
					<div class="socialNetworks">
						<?=SocialButtonWidget::widget([
														  'items' => [
															  ['linkTag' => 'a', 'link' => 'https://www.facebook.com/krasota.style.com.ua', 'type' => 'facebook'],
															  ['linkTag' => 'a', 'link' => 'http://vk.com/bizhuteria_optom_ua', 'type' => 'vkontakte'],
															  ['linkTag' => 'a', 'link' => 'https://plus.google.com/u/0/106125731561025796307?rel=author', 'type' => 'googleplus'],
															  ['linkTag' => 'a', 'link' => 'http://www.odnoklassniki.ru/krasotastyle2', 'type' => 'odnoklassniki'],
															  ['linkTag' => 'a', 'link' => 'https://twitter.com/krasota_style', 'type' => 'twitter'],
														  ]
													  ])?>
					</div>
				</div>
			</div>
			<div class="footer-content">
				<span class="left">© Интернет-магазин «krasota-style™» 2011–2015</span>
				<span class="right">Дизайн и разработка сайта “krasota-style.ua”</span>
			</div>
		</div>
		<?=$cartModal->renderModal(),
		$loginModal->renderModal(),
		$registrationModal->renderModal();

		RuLangAsset::register($this);

		$this->endBody() ?>
	</body>
</html>
<?php $this->endPage() ?>