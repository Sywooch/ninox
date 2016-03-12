<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\models\Category;
use frontend\assets\RuLangAsset;
use common\components\SocialButtonWidget;
use frontend\widgets\CartWidget;
use yii\helpers\Html;
use yii\helpers\Url;

$this->registerMetaTag(['charset' => Yii::$app->charset]);
$this->registerMetaTag(['name' => 'description', 'content' => '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => '']);
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
	'content'			=>	$this->render('../site/cart', []),
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
	'content'			=>	$this->render('../site/login'),
	'id'				=>	'loginModal',
]);


$js = <<<JS
	$('img.svg').each(function(){
    	var img = jQuery(this),
    		imgID = img.attr('id'),
    		imgClass = img.attr('class'),
    		imgURL = img.attr('src');

    	$.get(imgURL, function(data) {
    	    // Get the SVG tag, ignore the rest
    	    var svg = jQuery(data).find('svg');

    	    // Add replaced image's ID to the new SVG
    	    if(typeof imgID !== 'undefined') {
    	        svg = svg.attr('id', imgID);
    	    }
    	    // Add replaced image's classes to the new SVG
    	    if(typeof imgClass !== 'undefined') {
    	        svg = svg.attr('class', imgClass+' replaced-svg');
    	    }

    	    // Remove any invalid XML tags as per http://validator.w3.org
    	    svg = svg.removeAttr('xmlns:a');

    	    // Replace image with new SVG
    	    img.replaceWith(svg);

    	}, 'xml');
    });

	if(hasTouch){
		$('body').on('touchmove', function(e){
			e.target.isTouchMoved = true;
		});
	}

	$('body').on(hasTouch ? 'touchend' : 'click', '.counter .minus:not(.prohibited), .counter .plus:not(.prohibited), .remove-item', function(e){
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

	$('body').on(hasTouch ? 'touchend' : 'click', '.yellow-button.buy', function(e){
		if(hasTouch && isTouchMoved(e)){ return false; }
		e.preventDefault();
		addToCart(e.currentTarget);
	});

	$('body').on(hasTouch ? 'touchend' : 'click', '.green-button.open-cart', function(e){
		if(hasTouch && isTouchMoved(e)){ return false; }
		e.preventDefault();
		openCart();
	});

	$(document).on('pjax:complete', function(){
		cartScroll();
	});

	cartScroll();
JS;

$this->registerJs($js);


$this->registerJsFile('/perfect-scrollbar/js/perfect-scrollbar.jquery.js', [
	'depends'   =>  'yii\web\JqueryAsset'
]);
$this->registerCssFile('/perfect-scrollbar/css/perfect-scrollbar.css');
?>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
	<script type="text/javascript" src="/js/jquery.sticky.js"></script>
	<script>
		$(document).ready(function(){
			$(".sticky-on-scroll").sticky({ topSpacing: 0, className:"sticky" });
		});
	</script>
<?php $this->beginPage() ?>
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
				<div class="items"><span>О компании</span></div>
				<div class="items"><span>Помощь</span></div>
				<div class="items"><span>Контакты</span></div>
				<div class="blog items">Блог</div>
				<div class="items currency-rate">1 USD - 24.2 UAH</div>
				<div class="personal-account">
					<div class="items">РУС</div>
					<div class="items account-icon">Личный кабинет</div>
				</div>
			</div>
		</div>
		<div class="sticky-on-scroll">
			<div class="under-menu">
				<div class="under-menu-content">
					<a href="/"><div class="logo"></div></a>
					<div class="input-style-main">
						<label class="icon-search" for=""></label>
						<input type="text" placeholder="Поиск..."/>
						<?=\yii\helpers\Html::button('Найти', [
							'type'  =>  'submit',
							'class' =>  'blue-button small-button ',
							'id'    =>  'submit'
						])?>
					</div>
					<div class="phone-number">
						<div class="phone"></div>
						<span class="number">(044)257-45-54</span>
					</div>
					<div class="desire-basket">
						<div class="desire">
							<div class="desire-icon"></div>
							<div class="count">5</div>
							<span>Желания</span>
						</div>
						<?=CartWidget::widget(['remodalInstance' => $cartModal])?>
					</div>
				</div>
				<!--<ul class="left">
					<li id="users"><span class="menuImage"></span>Покупателям<span class="arrow"></span>
						<ul>
							<li><a href="/dostavka">Доставка</a></li>
							<li><a href="/oplata">Оплата</a></li>
							<li><a href="/vozvrat-i-obmen">Возврат и обмен</a></li>
							<li><span class="link-hide" data-href="/status-zakaza">Статус заказа</span></li>
							<li><a href="/voprosy-i-otvety">Вопросы и ответы</a></li>
							<li><a href="/pomoshch">Как заказать</a></li>
							<li><a href="/akcii">Акции</a></li>
							<li><a href="/garantii">Гарантии</a></li>
						</ul>
					</li>
					<li id="about"><span class="menuImage"></span>О компании<span class="arrow"></span>
						<ul>
							<li><a href="/kontakty">Контакты</a></li>
							<li><a href="/otzyvy">Отзывы</a></li>
							<li><a href="/o-nas">О нас</a></li>
							<li><a href="/vakansii">Вакансии</a></li>
						</ul>
					</li>
					<li id="partners"><a href="/sotrudnichestvo"><span class="menuImage"></span>Сотрудничество</a></li>
					<li id="blog"><a target="_blank" href="/blog"><span class="menuImage"></span>Блог</a></li>
					<li id="allGoods">На сайте 9599 товаров</li>
				</ul>-->
				<!--<div class="logo mobileLogo" itemscope="" itemtype="http://schema.org/Organization">
					<img alt="Мобильный логотип интернет магазина Krasota-Style" src="/img/logo/xnew_mobile_logo_ru.png">
					<noscript>
						<div class="contact_phone" itemprop="telephone">(044) 232 82 20</div>
						<div class="contact_phone" itemprop="email"><a class="__cf_email__" href="/cdn-cgi/l/email-protection" data-cfemail="e28b8c848da2899083918d9683cf91969b8e87cc9783">[email&#160;protected]</a></div>
						<span itemprop="name">Оптовый интернет-магазин Krasota-Style.ua</span>
						<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
							<span itemprop="postalCode">02217</span>, <span itemprop="addressLocality">Украина, г. Киев</span>, <span itemprop="streetAddress">ул. Электротехническая, 2</span>
						</div>
						<img itemprop="logo" src="/img/xlogo_ukraine.png" alt="shop logo">
					</noscript>
				</div>-->
			<!--	<ul class="right">
					<li id="loginRegistration">
					<?php if(\Yii::$app->user->isGuest){ ?>
						<span id="login">
						<?=\bobroid\remodal\Remodal::widget([
							'confirmButton'	=>	false,
							'id'			=>	'login',
							'cancelButton'	=>	false,
							'addRandomToID'	=>	false,
							'content'		=>	$this->render('parts/_login_modal'),
							'buttonOptions'	=>	[
								'label'		=>	\Yii::t('shop', 'Войти')
							],
						])?>
						</span>
						&nbsp;/&nbsp;
						<span id="registration">
							<?=\bobroid\remodal\Remodal::widget([
								'confirmButton'	=>	false,
								'id'			=>	'registration',
								'cancelButton'	=>	false,
								'addRandomToID'	=>	false,
								'content'		=>	$this->render('parts/_registration_modal'),
								'buttonOptions'	=>	[
									'label'		=>	\Yii::t('shop', 'Регистрация')
								],
							])?>
						</span>
					<?php }else{ ?>
						<span>Здравствуйте, <?=Html::a(\Yii::$app->user->identity->Company, '/account')?>! <?=Html::a('Выйти', Url::to('/logout'), [
								'data-method'   =>  'post'
							])?></span>
					<?php }	?>
					</li>
					<li id="lang">РУС<span class="arrow"></span>
						<ul>
							<li><a href="/uk">УКР</a></li>
						</ul>
					</li>

					<li id="cart">
						<?=\frontend\widgets\CartWidget::widget(['remodalInstance' => $cartModal])?>
					</li>
				</ul>-->
			</div>
		<?=\frontend\widgets\MainMenuWidget::widget([
			'items'	=>	Category::getMenu()
		])?>
		</div>
	</div>
	<?=$content?>
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
				<?=\yii\helpers\Html::button('Заказать обратный звонок', [
					'type'  =>  'submit',
					'class' =>  'yellow-button large-button',
					'id'    =>  'submit'
				])?>
			</div>
		</div>
		<div class="footer-menu">
			<div class="goods-item">
				<?=Html::tag('span', \Yii::t('shop', 'О компании'), [
					'class'		=>	'link-hide',
					'data-href'	=>	'/status-zakaza'
				]),
				Html::tag('span', \Yii::t('shop', 'Контакты'), [
					'class'		=>	'link-hide',
					'data-href'	=>	'/oplata'
				]),
				Html::tag('span', \Yii::t('shop', 'Вакансии'), [
					'class'		=>	'link-hide',
					'data-href'	=>	'/vozvrat-i-obmen'
				]),
				Html::tag('span', \Yii::t('shop', 'Блог'), [
					'class'		=>	'link-hide',
					'data-href'	=>	'/kontakty'
				]),
				Html::tag('span', \Yii::t('shop', 'Отзывы о магазине'), [
					'class'		=>	'link-hide',
					'data-href'	=>	'/kontakty'
				]),
				Html::tag('a', \Yii::t('shop', 'Карта сайта'), [
					'class'		=>	'link-hide',
					'href'		=>	'/map'
				])?>
			</div>
			<div class="goods-item">
				<?=Html::tag('span', \Yii::t('shop', 'Услуги'), [
					'class'		=>	'link-hide',
					'data-href'	=>	'/status-zakaza'
				]),
				Html::tag('span', \Yii::t('shop', 'Акции'), [
					'class'		=>	'link-hide',
					'data-href'	=>	'/oplata'
				]),
				Html::tag('span', \Yii::t('shop', 'Гарантии'), [
					'class'		=>	'link-hide',
					'data-href'	=>	'/vozvrat-i-obmen'
				]),
				Html::tag('span', \Yii::t('shop', 'Бонусная программа'), [
					'class'		=>	'link-hide',
					'data-href'	=>	'/kontakty'
				])
				?>
			</div>
			<div class="goods-item">
				<?=Html::tag('span', \Yii::t('shop', 'Как заказать'), [
					'class'		=>	'link-hide',
					'data-href'	=>	'/status-zakaza'
				]),
				Html::tag('span', \Yii::t('shop', 'Оплата'), [
					'class'		=>	'link-hide',
					'data-href'	=>	'/oplata'
				]),
				Html::tag('span', \Yii::t('shop', 'Доставка'), [
					'class'		=>	'link-hide',
					'data-href'	=>	'/vozvrat-i-obmen'
				]),
				Html::tag('span', \Yii::t('shop', 'Возврат товара'), [
					'class'		=>	'link-hide',
					'data-href'	=>	'/kontakty'
				])
				?>
			</div>
			<div class="goods-item feedback-link">
				<?=Html::tag('span', \Yii::t('shop', 'Обратная связь'), [
					'class'		=>	'link-hide',
					'data-href'	=>	'/vakansii'
				]),
				Html::tag('span', \Yii::t('shop', 'Проблемы с заказом?'), [
					'class'		=>	'link-hide',
					'data-href'	=>	'/kontakty'
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
	</div>
	<!--<footer>
		<div class="footerCenter">
			<div class="quickReference">
				<div class="reference1">
					<?=Html::tag('span', \Yii::t('shop', 'Статус заказа'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/status-zakaza'
					]),
					Html::tag('span', \Yii::t('shop', 'Подтвердить оплату'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/oplata'
					]),
					Html::tag('span', \Yii::t('shop', 'Возврат товара'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/vozvrat-i-obmen'
					]),
					Html::tag('span', \Yii::t('shop', 'Жалобы руководству'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/kontakty'
					]),
					Html::tag('a', \Yii::t('shop', 'Карта сайта'), [
						'class'		=>	'link-hide',
						'href'		=>	'/map'
					])?>
				</div>
				<div class="reference2">
					<?=Html::tag('span', \Yii::t('shop', 'Вакансии'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/vakansii'
					]),
					Html::tag('span', \Yii::t('shop', 'Контакты'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/kontakty'
					]),
					Html::tag('span', \Yii::t('shop', 'О нас'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/o-nas'
					])?>
				</div>
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
			<div class="support">
				<span class="supportName"><?=\Yii::t('shop', 'Служба клиентской поддержки')?>:</span>
				<span class="primeNumber" ><?=(\Yii::$app->language == 'be' ? '+375 (29) 110 43 43' : '0 800 508 208')?></span>
				<?php if(\Yii::$app->language == 'be'){ ?>
					<span class="skype"><a class="call-to-skype" href="skype:krasota-style?call">позвонить на <i class="shop-skype"></i></a></span>
				<?php } ?>
				<span class="workTime"><?=\Yii::t('shop', 'Время работы call-центра')?>:</span>
				<span class="workTime">вт.-вс: с 9.00 до 18.00</span>
				<span class="workTime">пн: с 11.00 до 15.00</span>
				<div>
					<?php if(false){ //Что это за костыль?! Это сказали прибрать, но зная, что часто прибранное приходится возвращать, пришлось воспользоваться инкостыляцией?>
						<div><span><?=\Yii::t('shop', 'Киев')?></span><span>044 222 8 110</span></div>
					<?php } ?>
					<?php if(\Yii::$app->language != 'be'){ ?>
						<div><span><?=\Yii::t('shop', 'Киев')?></span><span>044 232 82 20</span></div>
						<div><span><?=\Yii::t('shop', 'Одесса')?></span><span>048 735 10 80</span></div>
						<div><span>моб. МТС</span><span>050 677 54 56</span></div>
						<div><span>моб. Киевстар</span><span>067 507 87 73</span></div>
						<div><span>моб. Life</span><span>063 334 49 15</span></div>
					<?php } ?>
				</div>
			</div>
			<div class="footerContacts">
				<div class="phone"></div>
				<div class="number">063 334 49 15 • 067 507 87 73</div>
			</div>
		</div>
	</footer>-->
	<?=$cartModal->renderModal()?>
	<?=$loginModal->renderModal()?>
	<?php
	RuLangAsset::register($this);
	?>
	<?php $this->endBody() ?>
	</body>
</html>
<?php $this->endPage() ?>