<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\RuLangAsset;
use bobroid\yamm\Yamm;
use common\components\SocialButtonWidget;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$this->registerMetaTag(['charset' => Yii::$app->charset]);
$this->registerMetaTag(['name' => 'description', 'content' => '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => '']);
$this->registerMetaTag(['name' => 'MobileOptimized', 'content' => '1240']);
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width; initial-scale=1.0']);
$this->registerMetaTag(['name' => 'HandheldFriendly', 'content' => 'false']);

$this->registerLinkTag(['rel' => 'shortcut icon', 'type' => 'image/x-icon', 'href' => '/favicon.ico']);
$menu = \common\models\Category::getMenu();

$userCart = \common\models\Cart::findOne(['cartCode' => \Yii::$app->request->cookies->get('cartCode')]);

if(empty($userCart)){
	$userCart = new \common\models\Cart();
}

$cartItemsDataProvider = new \yii\data\ActiveDataProvider([
	'query' =>  \Yii::$app->cart->goodsQuery()
]);

$cartModal = new \bobroid\remodal\Remodal([
	'cancelButton'		=>	false,
	'confirmButton'		=>	false,
	'closeButton'		=>	false,
	'content'			=>	$this->render('../site/cart', [
		'dataProvider'	=>	$cartItemsDataProvider
	]),
	'id'	=>	'modalCart',
	'addRandomToID'		=>	false,
	'events'			=>	[
		'opening'	=>	new \yii\web\JsExpression("getCart(e)")
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


$js = <<<SCRIPT
	if(hasTouch){
		$('body').on('touchmove', function(e){
			e.target.isTouchMoved = true;
		});
	}

	$('body').on(hasTouch ? 'touchend' : 'click', '.counter .minus, .counter .plus', function(e){
		if(hasTouch && isTouchMoved(e)){ return false; }
		e.preventDefault();
		changeItemCount(e.currentTarget);
	});

	$('body').on(hasTouch ? 'touchend' : 'click', '.remove-item', function(e){
		if(hasTouch && isTouchMoved(e)){ return false; }
		e.preventDefault();
		removeFromCart(e.currentTarget);
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
SCRIPT;

$this->registerJs($js);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
	<head>
	    <?= Html::csrfMetaTags() ?>
	    <title><?= Html::encode($this->title) ?></title>
	    <?php $this->head() ?>
	</head>
	<body>
	<?php $this->beginBody() ?>
	<?php
	if(\Yii::$app->request->get("serviceMenu") == 'true' && \Yii::$app->request->get("secretKey") == \Yii::$app->params['secretAdminPanelKey']){
		echo $this->render('_admin_menu');
	}
	?>
	<div class="topCaption">
		<div class="topAction">
			<div class="top-action-center">
				<a href="/"><div class="top-action-text"></div></a>
			</div>
		</div>
		<nav class="topCaptionCenter">
			<ul class="left">
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
			</ul>
			<div class="logo mobileLogo" itemscope="" itemtype="http://schema.org/Organization">
				<img alt="Мобильный логотип интернет магазина Krasota-Style" pagespeed_url_hash="564228562" src="/img/logo/xnew_mobile_logo_ru.png,q1.pagespeed.ic.DFWm4U7hmZ.png">
				<noscript>
					<div class="contact_phone" itemprop="telephone">(044) 232 82 20</div>
					<div class="contact_phone" itemprop="email"><a class="__cf_email__" href="/cdn-cgi/l/email-protection" data-cfemail="e28b8c848da2899083918d9683cf91969b8e87cc9783">[email&#160;protected]</a></div>
					<span itemprop="name">Оптовый интернет-магазин Krasota-Style.ua</span>
					<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
						<span itemprop="postalCode">02217</span>, <span itemprop="addressLocality">Украина, г. Киев</span>, <span itemprop="streetAddress">ул. Электротехническая, 2</span>
					</div>
					<img itemprop="logo" src="/img/xlogo_ukraine.png.pagespeed.ic.cDhSQmq40P.png" alt="shop logo">
				</noscript>
			</div>
			<ul class="right">
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
					<?=\common\components\CartWidget::widget(['remodalInstance' => $cartModal])?>
				</li>
			</ul>
		</nav>
	</div>
	<?php
	Yamm::begin([
		'options' => [
			'headerOptions'   =>  [
				'class'   =>  'blue'
			]
		],
		'theme' =>  'blue',
		'items' =>  $menu
	]);
	?>
	<?=$content?>
	<?php Yamm::end(); ?>
	<footer>
		<div class="footerCenter">
			<div class="quickReference">
				<div class="reference1">
					<?=Html::tag('span', \Yii::t('shop', 'Статус заказа'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/status-zakaza'
					])?>
					<?=Html::tag('span', \Yii::t('shop', 'Подтвердить оплату'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/oplata'
					])?>
					<?=Html::tag('span', \Yii::t('shop', 'Возврат товара'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/vozvrat-i-obmen'
					])?>
					<?=Html::tag('span', \Yii::t('shop', 'Жалобы руководству'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/kontakty'
					])?>
					<?=Html::tag('a', \Yii::t('shop', 'Карта сайта'), [
						'class'		=>	'link-hide',
						'href'		=>	'/map'
					])?>
				</div>
				<div class="reference2">
					<?=Html::tag('span', \Yii::t('shop', 'Вакансии'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/vakansii'
					])?>
					<?=Html::tag('span', \Yii::t('shop', 'Контакты'), [
						'class'		=>	'link-hide',
						'data-href'	=>	'/kontakty'
					])?>
					<?=Html::tag('span', \Yii::t('shop', 'О нас'), [
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
	</footer>
	<?=$cartModal->renderModal()?>
	<?=$loginModal->renderModal()?>
	<?php
	RuLangAsset::register($this);
	?>
	<?php $this->endBody() ?>
	</body>
</html>
<?php $this->endPage() ?>