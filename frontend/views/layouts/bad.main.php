<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\FrontEndAsset;
use app\assets\RuLangAsset;
use app\widgets\WLang;


/* @var $this \yii\web\View */
/* @var $content string */





$this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
<!--        <?php
/*        if(isset($this->file) && $this->file == 'tickets'){ */?>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <?php /*} */?>

        <?php /*if($_SERVER['SERVER_NAME'] == 'ua.krasota-style.com.ua' || $_SERVER['SERVER_NAME'] == 'krasota-style.com.ua'){
            if(true/*$canonical){ */?>
                <link rel="canonical" href="https://<?/*=$_SERVER['SERVER_NAME'].$_SESSION['linkLang'].($cUri != '' ? '/'.$cUri : '')*/?>">
            <?php
/*            }
            if($this->prev){
            */?>
                <link rel="prev" href="https://<?/*=$_SERVER['SERVER_NAME'].$_SESSION['linkLang'].'/'.$uri.($nowOrder != 'date' ? '/order-'.$nowOrder : '').($nowPage != '2' ? '/page-'.($nowPage - 1) : '')*/?>">
            <?php
/*            }
            if($this->next){
            */?>
                <link rel="next" href="https://<?/*=$_SERVER['SERVER_NAME'].$_SESSION['linkLang'].'/'.$uri.($nowOrder != 'date' ? '/order-'.$nowOrder : '').'/page-'.($nowPage + 1)*/?>">
            <?php
/*            }
            if($this->noindex_follow == true){ */?>
                <meta name="robots" content="noindex, follow" >
            <?php /*}elseif($this->noindex_nofollow == true){ */?>
                <meta name="robots" content="noindex, nofollow" >
            <?php /*}
        }else{ */?>
            <link rel="canonical" href="https://<?/*=$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']*/?>">
        <?php /*} */?>
	    <link rel="alternate" hreflang="ru" href="https://<?/*=$_SERVER['SERVER_NAME'].($url['1'] == 'home' ? '' : ($langLinks['link'] ? '/'.$langLinks['link'] : implode('/', $url))).($nowOrder != 'date' ? '/order-'.$nowOrder : '').(($nowPage != '' && $nowPage > 1) ? '/page-'.($nowPage) : '')*/?>">
	    <link rel="alternate" hreflang="uk" href="https://<?/*=$_SERVER['SERVER_NAME'].'/uk'.($url['1'] == 'home' ? '' : ($langLinks['link_uk'] ? '/'.$langLinks['link_uk'] : implode('/', $url))).($nowOrder != 'date' ? '/order-'.$nowOrder : '').(($nowPage != '' && $nowPage > 1) ? '/page-'.($nowPage) : '')*/?>">
	    <link rel="alternate" hreflang="ru-BY" href="https://krasota-style.by<?/*=($url['1'] == 'home' ? '' : ($langLinks['link_be'] ? '/'.$langLinks['link_be'] : implode('/', $url))).($nowOrder != 'date' ? '/order-'.$nowOrder : '').(($nowPage != '' && $nowPage > 1) ? '/page-'.($nowPage) : '')*/?>">-->
	    <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
	<body>
	<?php $this->beginBody() ?>
	    <div class="contentContainer">
		    <div class="menuMobileContainer">
			    <ul>
				    <!--<li><span class="link-hide" data-href="<?=$_SESSION['linkLang']?>/dostavka"><?=_("Доставка")?></span></li>
					<li><span class="link-hide" data-href="<?=$_SESSION['linkLang']?>/oplata"><?=_("Оплата")?></span></li>
					<li><span class="link-hide" data-href="<?=$_SESSION['linkLang']?>/vozvrat-i-obmen"><?=_("Возврат и обмен")?></span></li>
					<li><span class="link-hide" data-href="<?=$_SESSION['linkLang']?>/status-zakaza"><?=_("Статус заказа")?></span></li>
					<li><span class="link-hide" data-href="<?=$_SESSION['linkLang']?>/voprosy-i-otvety"><?=_("Вопросы и ответы")?></span></li>-->
				    <li><span class="link-hide" data-href="<?=$_SESSION['linkLang']?>/pomoshch"><?=_("Как заказать")?></span></li>
				    <li><span class="link-hide" data-href="<?=$_SESSION['linkLang']?>/akcii"><?=_("Акции")?></span></li>
				    <li><span class="link-hide" data-href="<?=$_SESSION['linkLang']?>/garantii"><?=_("Гарантии")?></span></li>
				    <li><span class="link-hide" data-href="<?=$_SESSION['linkLang']?>/kontakty"><?=_("Контакты")?></span></li>
				    <li><span class="link-hide" data-href="<?=$_SESSION['linkLang']?>/otzyvy"><?=_("Отзывы")?></span></li>
				    <li><span class="link-hide" data-href="<?=$_SESSION['linkLang']?>/o-nas"><?=_("О нас")?></span></li>
				    <li><span class="link-hide" data-href="<?=$_SESSION['linkLang']?>/vakansii"><?=_("Вакансии")?></span></li>
				    <li><span class="link-hide" data-href="/blog" data-target="_blank"><?=_("Блог")?></span></li>
				    <li><span class="link-hide" data-href="<?=$_SESSION['linkLang']?>/sotrudnichestvo"><?=_("Сотрудничество")?></span></li>
			    </ul>
		    </div>
		    <div class="topCaption">
			    <div class="topAction">
				    <div class="top-action-center">
					    <a href="/akcii" target="_blank"><div class="top-action-text"></div></a>
				    </div>
			    </div>
			    <nav class="topCaptionCenter">
				    <ul class="left">
					    <li id="users"><span class="menuImage"></span><?=_("Покупателям")?><span class="arrow"></span>
						    <ul>
							    <li><a href="<?=$_SESSION['linkLang']?>/dostavka"><?=_("Доставка")?></a></li>
							    <li><a href="<?=$_SESSION['linkLang']?>/oplata"><?=_("Оплата")?></a></li>
							    <li><a href="<?=$_SESSION['linkLang']?>/vozvrat-i-obmen"><?=_("Возврат и обмен")?></a></li>
							    <li><span class="link-hide" data-href="<?=$_SESSION['linkLang']?>/status-zakaza"><?=_("Статус заказа")?></span></li>
							    <li><a href="<?=$_SESSION['linkLang']?>/voprosy-i-otvety"><?=_("Вопросы и ответы")?></a></li>
							    <li><a href="<?=$_SESSION['linkLang']?>/pomoshch"><?=_("Как заказать")?></a></li>
							    <li><a href="<?=$_SESSION['linkLang']?>/akcii"><?=_("Акции")?></a></li>
							    <li><a href="<?=$_SESSION['linkLang']?>/garantii"><?=_("Гарантии")?></a></li>
						    </ul>
					    </li>
					    <li id="about"><span class="menuImage"></span><?=_("О компании")?><span class="arrow"></span>
						    <ul>
							    <li><a href="<?=$_SESSION['linkLang']?>/kontakty"><?=_("Контакты")?></a></li>
							    <li><a href="<?=$_SESSION['linkLang']?>/otzyvy"><?=_("Отзывы")?></a></li>
							    <li><a href="<?=$_SESSION['linkLang']?>/o-nas"><?=_("О нас")?></a></li>
							    <li><a href="<?=$_SESSION['linkLang']?>/vakansii"><?=_("Вакансии")?></a></li>
						    </ul>
					    </li>
					    <li id="partners"><a href="<?=$_SESSION['linkLang']?>/sotrudnichestvo"><span class="menuImage"></span><?=_("Сотрудничество")?></a></li>
					    <li id="blog"><a target="_blank" href="/blog"><span class="menuImage"></span><?=_("Блог")?></a></li>
					    <li id="allGoods"><?=_("На сайте")." "./*$goodsOnSite.core::plural($goodsOnSite, _(" товар"), _(" товара"), _(" товаров"))*/''?></li>
				    </ul>
				    <?= ''//WLang::widget();?>
				    <div class="logo mobileLogo" <?php if($url['1'] != 'kontakty'){ ?>itemscope itemtype="http://schema.org/Organization"<?php } ?>>
					    <?php
					    if($url['1'] != 'home'){
						    echo '<a href="'.$_SESSION['linkRoot'].'"></a>';
					    } ?>
					    <img src="/img/logo/new_mobile_logo_<?=$_SESSION['lang']?>.png?1" alt="Мобильный логотип интернет магазина Krasota-Style">
					    <?php   if($url['1'] != 'kontakty'){ ?>
						    <noscript>
							    <div class="contact_phone" itemprop="telephone">(044) 232 82 20</div>
							    <div class="contact_phone" itemprop="email"><?=/*DatabaseInterface::$fromInfoEmail*/''?></div>
							    <span itemprop="name">Оптовый интернет-магазин Krasota-Style.ua</span>
							    <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
								    <span itemprop="postalCode">02217</span>, <span itemprop="addressLocality">Украина, г. Киев</span>, <span itemprop="streetAddress">ул. Электротехническая, 2</span>
							    </div>
							    <img itemprop="logo" src="/img/logo_ukraine.png" alt="shop logo">
						    </noscript>
					    <?php } ?>
				    </div>

				    <ul class="right">
					    <?php if($_SESSION['userID'] != '' && $_SESSION['cabinet'] == '1'){ ?>
						    <li id="enter">
							    <span class="logoutText"><?=_("Здравствуйте")?>, </span><span class="link-hide logoutText" data-href="<?=$_SESSION['linkLang']?>/cabinet"><?=$userInfo['Name']?></span> <span class="logoutBottom"><a onclick="logout()"><?=_("Выход")?></a></span>
						    </li>
					    <?php }else{ ?>
						    <li id="loginRegistration">
							    <span id="login"><span class="link-hide"><?=_("Вход")?></span></span><span id="registration">&nbsp;/&nbsp;<span class="link-hide" data-href="<?=$_SESSION['linkLang']?>/cabinet/registration"><?=_("Регистрация")?></span></span>
						    </li>
					    <?php } ?>
					    <li id="lang"><?=$_SESSION['lang'] == 'uk' ? 'УКР' : 'РУС'?><span class="arrow"></span>
						    <ul>
							    <?php if($_SESSION['lang'] == 'uk'){?>
								    <li><a href="<?=($url['1'] == 'home' ? '/' : ($langLinks['link'] ? '/'.$langLinks['link'] : implode('/', $url))).($_GET ? '?'.urldecode(http_build_query($_GET)) : '')?>">РУС</a></li>
							    <?php }else{ ?>
								    <li><a href="/uk<?=($url['1'] == 'home' ? '' : ($langLinks['link_uk'] ? '/'.$langLinks['link_uk'] : implode('/', $url))).($_GET ? '?'.urldecode(http_build_query($_GET)) : '')?>">УКР</a></li>
							    <?php } ?>
						    </ul>
					    </li>
					    <li id="cart"><a class="openCart"><span></span><span class="summ"><?=/*$cartData['summ'].*/' '.$_SESSION['domainInfo']['currencyShortName']?></span></a>&nbsp;
						    <ul>
							    <li><?=_("В вашей корзине")?> <div><span class="inCartItemsCount"><?=/*$cartData['count'].core::plural($cartData['count'], _(" товар"), _(" товара"), _(" товаров"))*/''?></span></div></li>
							    <li><a class="openCart"><?=_("Оформить заказ")?></a></li>
						    </ul>
					    </li>
				    </ul>
			    </nav>
		    </div>
		    <div class="topCaptionShadow"></div>
	    </div>
		<div class="footer">
			<div class="footerCenter">
				<div class="quickReference">
					<div class="reference1">
						<span class="link-hide" data-href="<?=$_SESSION['linkLang']?>/status-zakaza"><?=_("Статус заказа")?></span>
						<span class="link-hide" data-href="<?=$_SESSION['linkLang']?>/oplata"><?=_("Подтвердить оплату")?></span>
						<span class="link-hide" data-href="<?=$_SESSION['linkLang']?>/vozvrat-i-obmen"><?=_("Возврат товара")?></span>
						<span class="link-hide" data-href="<?=$_SESSION['linkLang']?>/kontakty"><?=_("Жалобы руководству")?></span>
						<a class="link-hide" href="<?=$_SESSION['linkLang']?>/map"><?=_("Карта сайта")?></a>
					</div>
					<div class="reference2">
						<span class="link-hide" data-href="<?=$_SESSION['linkLang']?>/vakansii"><?=_("Вакансии")?></span>
						<span class="link-hide" data-href="<?=$_SESSION['linkLang']?>/kontakty"><?=_("Контакты")?></span>
						<span class="link-hide" data-href="<?=$_SESSION['linkLang']?>/o-nas"><?=_("О нас")?></span>
					</div>
				</div>
				<div class="socialNetworks">
					<<?php if($file == 'home'){ ?>a target="_blank" <?php }else{ ?>span class="link-hide" data-target="_blank" data-<?php } ?>href="https://www.facebook.com/krasota.style.com.ua">
						<i class="shop-facebook"></i>
						</<?php if($file == 'home'){ ?>a<?php }else{ ?>span<?php } ?>>
						<<?php if($file == 'home'){ ?>a target="_blank" <?php }else{ ?>span class="link-hide" data-target="_blank" data-<?php } ?>href="http://vk.com/bizhuteria_optom_ua">
							<i class="shop-vkontakte"></i>
							</<?php if($file == 'home'){ ?>a<?php }else{ ?>span<?php } ?>>
							<<?php if($file == 'home'){ ?>a target="_blank" <?php }else{ ?>span class="link-hide" data-target="_blank" data-<?php } ?>href="https://plus.google.com/u/0/106125731561025796307?rel=author">
								<i class="shop-googleplus"></i>
								</<?php if($file == 'home'){ ?>a<?php }else{ ?>span<?php } ?>>
								<<?php if($file == 'home'){ ?>a target="_blank" <?php }else{ ?>span class="link-hide" data-target="_blank" data-<?php } ?>href="http://www.odnoklassniki.ru/krasotastyle2">
									<i class="shop-odnoklassniki-rect"></i>
									</<?php if($file == 'home'){ ?>a<?php }else{ ?>span<?php } ?>>
									<<?php if($file == 'home'){ ?>a target="_blank" <?php }else{ ?>span class="link-hide" data-target="_blank" data-<?php } ?>href="https://twitter.com/krasota_style">
										<i class="shop-twitter-squared"></i>
										</<?php if($file == 'home'){ ?>a<?php }else{ ?>span<?php } ?>>
				</div>
				<div class="support">
					<span class="supportName"><?=_("Служба клиентской поддержки")?>:</span>
					<span class="primeNumber"><?=($_SESSION['lang'] == 'be' ? '+375 (29) 110 43 43' : '0 800 508 208')?></span>
					<?php if($_SESSION['lang'] == 'be'){ ?>
						<span class="skype"><a class="call-to-skype" href="skype:krasota-style?call">позвонить на <i class="shop-skype"></i></a></span>
					<?php } ?>
					<span class="workTime"><?=_("Время работы call-центра")?>:</span>
					<span class="workTime">вт.-вс: с 9.00 до 18.00</span>
					<span class="workTime">пн: с 11.00 до 15.00</span>
					<div>
						<?php if(false){ ?>
							<div><span><?=_("Киев")?></span><span>044 222 8 110</span></div>
						<?php } ?>
						<?php if($_SESSION['lang'] != 'be'){ ?>
							<div><span><?=_("Киев")?></span><span>044 232 82 20</span></div>
							<div><span><?=_("Одесса")?></span><span>048 735 10 80</span></div>
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
		</div>

		<div class="modal" id="modalCallback">
			<span class="close"></span>
			<div class="block">
				<div class="form_content" id="callback">
					<span><?=_("Запрос на перезвон")?></span>
					<div class="row">
						<div class="fio">
							<span class="title"><?=_("Имя и фамилия")?></span><input type="text" name="fio">
						</div>
					</div>
					<div class="row">
						<div class="phone">
							<span class="title"><?=_("Ваш телефон")?></span><span class="flag"></span><input class="input_phone" name="phone" value="" type="text">
						</div>
					</div>
					<div class="row">
						<?=_("Сообщение")?><br>
						<textarea name="callback"></textarea>
					</div>
					<div class="row">
						<div class="captcha">
							<span class="title"><?=_("Введите код с картинки")?></span><input type="text" name="captcha" pattern="[0-9]{4}"><img src="/captcha.png" width="80" height="40" alt="captcha image">
							<input class="reloadCaptchaz" type="button" onclick="updateCaptcha(this)" value="1">
						</div>
					</div>
					<div class="line"></div>
					<div class="row">
						<input type="button" class="yellowButton largeButton" value="<?=_("Отправить")?>" onclick="sendCallback(this)">
					</div>
				</div>
				<div class="answer" id="answer_callback">
					<div><span class="first"><?=_("Спасибо. В ближайшее время с вами")?><br><?=_("свяжется наш менеджер")?></span></div>
					<div></div>
					<div><a href="<?=$_SESSION['linkRoot']?>"><?=_("Вернутся на главную")?></a></div>
				</div>
				<div class="answer" id="bad_answer_callback">
					<div><span class="first"><?=_("Ой-ой! Что-то произошло!")?></span></div>
					<div><span class="second"><?=_("О нет! При отправке отзыва произошла какая-то ошибка, и отзыв не был отправлен! Попробуйте заполнить все поля в форме, или напишите нам на support@krasota-style.com.ua")?></span></div>
					<div><a href="<?=$_SESSION['linkRoot']?>"><?=_("Вернутся на главную")?></a></div>
				</div>
			</div>
		</div>

		<div class="flatModal" id="modalSupport">
			<span class="close"></span>
			<div class="block">
				<span class="label"><?=_("Служба клиентской поддержки")?>:</span>
				<div class="left">
					<span class="primeNumber"><?=($_SESSION['lang'] == 'be' ? '' : '0 800 508 208')?></span>
					<span class="workTime"><?=_("Время работы call-центра")?>:</span>
					<span class="workTime">вт.-вс: с 9.00 до 18.00</span>
					<span class="workTime">пн: с 9.00 до 15.00</span>
				</div>
				<div class="right">
					<?php if(false){ ?>
						<div><span><?=_("Киев")?></span><span>044 222 8 110</span></div>
					<?php } ?>
					<?php if($_SESSION['lang'] != 'be'){ ?>
						<div><span><?=_("Киев")?></span><span>044 232 82 20</span></div>
						<div><span><?=_("Одесса")?></span><span>048 735 10 80</span></div>
						<div><span>моб. МТС</span><span>050 677 54 56</span></div>
						<div><span>моб. Киевстар</span><span>067 507 87 73</span></div>
						<div><span>моб. Life</span><span>063 334 49 15</span></div>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php if($_SESSION['cabinet'] != '1'){ ?>
			<div class="flatModal" id="modalLogin">
				<span class="close"></span>
				<div class="block">
					<span class="label"><?=_("Вход в личный кабинет")?>:</span>
					<div class="cap">
						<?=_("Пользователь с указанным e-mail или телефоном уже зарегистрирован.")?>
					</div>
					<div class="row">
						<div class="phone">
							<span class="title"><?=_("Ваш телефон")?></span>
							<span class="flag"></span>
							<input class="input_phone" name="phone" onchange="iWannaLogin(this)" onkeyup="iWannaLogin(this)" onkeydown="iWannaLogin(this)" value="" type="text">
						</div>
					</div>
					<div class="row">
						<div class="password">
							<span class="title"><?=_("Пароль")?></span><input onchange="iWannaLogin(this)" onkeyup="iWannaLogin(this)" onkeydown="iWannaLogin(this)" type="password" id="input_password" name="passwd">
						</div>
					</div>
					<div class="line"></div>
					<div class="row">
						<input type="button" class="yellowButton largeButton" disabled="disabled" id="loginMeButton" value="<?=_("Войти")?>" name="login" onclick="loginMe(this)">
					</div>
					<div class="row center">
						<span class="recovery link-hide blue" data-href="<?=$_SESSION['linkLang']?>/cabinet/recovery"><?=_("Восстановить пароль")?></span><span> | </span><span class="registration link-hide blue" data-href="<?=$_SESSION['linkLang']?>/cabinet/registration"><?=_("Регистрация")?></span>
					</div>
				</div>
			</div>
		<?php } ?>
		<div class="modal" id="modalCart">
			<div class="block">
				<div class="caption">
					<div class="description">
						<span id="description"><?=_("Корзина")?></span>
						<span id="description2"></span>
					</div>
					<div class="continue">
							<span class="closeCart">
								<span><?=_("Продолжить покупки")?></span>
								<span></span>
							</span>
					</div>
				</div>
				<div class="topShadow"></div>
				<div class="windowContent"></div>
				<div class="miniFooter">
					<div class="bottomShadow"></div>
					<form method="POST" action="<?=$_SESSION['linkLang']?>/order" onkeypress="if(event.keyCode == 13) return false;" onsubmit="submitForm(this); return false;">
						<div class="row first">
							<span><?=_("Сумма заказа")?> <span id="totalSumm" class="blue"></span></span>
						</div>
						<div class="row second">
							<span id="optSumm"></span>
	                            <span class="extended-info">
	                                <span id="totalSummWithoutDiscount"></span> <span id="totalDiscount" class="orange"></span>
	                            </span>
						</div>
						<div class="row third">
							<div class="phone">
								<?php if($_SESSION['userID'] == '' || $_SESSION['cabinet'] != '1'){ ?><span class="flag"></span><?php } ?><input class="input_phone" name="phone" <?php if($_SESSION['userID'] != '' && $_SESSION['cabinet'] == '1'){ ?> value="<?=$userInfo['Phone']?>" type="hidden" <?php }else{ ?> value="" type="text"<?php } ?>>
							</div>
							<input id="one_click" type="submit" class="yellowButton largeButton" value="<?=_("Заказать в 1 клик")?>" disabled="disabled" data-disabled="true" onclick="this.form.oneClickOrder = true">
							<input id="checkout" type="submit" class="yellowButton largeButton" value="<?=_("Оформить заказ")?>" disabled="disabled" data-disabled="true" onclick="this.form.oneClickOrder = false">
							<input type="hidden" value="true" name="doOrder">
						</div>
					</form>
				</div>
			</div>
			<div class="submodal" id="cartSubModal">
				<span class="closesubmodal">×</span>
				<div class="subblock">
					Картинка, лол
				</div>
			</div>
		</div>
		<div class="modal" id="modalItem">
			<span class="close"></span>
			<span class="prev"></span>
			<span class="next"></span>
			<div class="block"></div>
		</div>
		<div id="outdated"></div>
		<?php if($file == 'tickets'){ ?>
			<!--<link href='http://fonts.googleapis.com/css?family=Open+Sans:700,400,600&subset=latin,cyrillic' rel='stylesheet' type='text/css'>-->
			<link href="/template/styles/tickets/shoot.css" rel="stylesheet" type="text/css">
			<!--[if lte IE 8]> <link href= "/template/styles/tickets/ie6-8.css" rel= "stylesheet" media= "all"> <![endif]-->
		<?php } ?>
		<?php if($needleWork || $needleWorkFront || $quickaddneedlework){ ?>
			<link rel="stylesheet" type="text/css" href="/template/styles/handmade.css?ver=11">
			<link rel="stylesheet" type="text/css" href="/template/styles/sweetalert.css">
		<?php } ?>
		<?php if($_COOKIE['useLocalJQUERY']){ ?>
			<script type="text/javascript" src="/template/js/jquery-1.11.1.min.js"></script>
		<?php }else{ ?>
			<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
			<script>
				if(!window.jQuery){
					document.cookie = 'useLocalJQUERY="true"';
					location.reload();
				}
			</script>
		<?php } ?>
		<script type="text/javascript" src="/template/js/ion.rangeSlider.min.js?ver=1"></script>
		<script type="text/javascript" src="/template/js/outdatedbrowser/outdatedbrowser.min.js"></script>
		<!--<script type="text/javascript" src="/template/js/jquery.sticky.js"></script>-->
		<script type="text/javascript" src="/template/js/jquery.the-modal.js?ver=1"></script>
		<!--<script type="text/javascript" src="/template/js/jquery.maskedinput.min.js"></script>-->
		<script type="text/javascript" src="/template/js/maskedphone/jquery.inputmask.js?ver=2"></script>
		<script type="text/javascript" src="/template/js/maskedphone/jquery.bind-first-0.2.3.min.js"></script>
		<script type="text/javascript" src="/template/js/maskedphone/jquery.inputmask-multi.js?ver=16"></script>
		<script type="text/javascript" src="/template/js/selector.js?ver=2"></script>
		<script type="text/javascript" src="/template/js/tooltipsy.min.js"></script>
		<script type="text/javascript" src="/template/js/visibilityjs/visibility.core.js"></script>
		<script type="text/javascript" src="/template/js/visibilityjs/visibility.timers.js?ver=2"></script>
		<script type="text/javascript" src="/template/js/visibilityjs/visibility.fallback.js"></script>
		<script type="text/javascript" src="/template/js/shortcut.js"></script>
		<script type="text/javascript" src="/template/js/translations/<?=$_SESSION['lang'] == 'uk' ? 'uk_UA' : ($_SESSION['lang'] == 'be' ? 'ru_BY' : 'ru_RU')?>.js?ver=6"></script>
		<script type="text/javascript" src="/template/js/new.main.js?ver=78"></script>
		<script type="text/javascript" src="/template/js/counter.js?ver=1"></script>
		<?php if($file == 'order'){ ?>
			<script src="//api-maps.yandex.ru/1.1/index.xml" type="text/javascript"></script>
			<script type="text/javascript" src="/template/js/new.processorder.js?ver=13"></script>
		<?php } ?>
		<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?v=3.exp&amp;libraries=places&amp;sensor=false&amp;language=<?=$_SESSION['lang']?>"></script>
		<script>
			var options = {
				types: ['(cities)'],
				componentRestrictions: {country: '<?=($_SESSION['lang'] == 'be' ? 'by' : 'ua')?>'}
			};
		</script>
		<script type="text/javascript" src="/template/js/new.google.city.autocomplete.js?ver=2"></script>
		<?php if($needleWork || $needleWorkFront || $quickaddneedlework){ ?>
			<script type="text/javascript" src="/template/js/bobroidsTabs.js"></script>
			<script type="text/javascript" src="/template/js/handmade.js?ver=16"></script>
			<script type="text/javascript" src="/template/js/min.sweetalert.js"></script>
		<?php } ?>

		<?php if($file == 'goodscard'){?>
			<script>
				Share = {
					purl: document.URL,
					vk: function(ptitle, pimg, text){
						url  = 'http://vkontakte.ru/share.php?';
						url += 'url='          + encodeURIComponent(Share.purl);
						url += '&title='       + encodeURIComponent(ptitle);
						url += '&description=' + encodeURIComponent(text);
						url += '&image='       + encodeURIComponent(pimg);
						url += '&noparse=true';
						Share.popup(url, 'vk');
					},
					ok: function(text){
						url  = 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1';
						url += '&st.comments=' + encodeURIComponent(text);
						url += '&st._surl='    + encodeURIComponent(Share.purl);
						Share.popup(url, 'ok');
					},
					fb: function(ptitle, pimg, text){
						url  = 'http://www.facebook.com/sharer.php?s=100';
						url += '&p[title]='     + encodeURIComponent(ptitle);
						url += '&p[summary]='   + encodeURIComponent(text);
						url += '&p[url]='       + encodeURIComponent(Share.purl);
						url += '&p[images][0]=' + encodeURIComponent(pimg);
						Share.popup(url, 'fb');
					},
					twitter: function(ptitle){
						url  = 'http://twitter.com/share?';
						url += 'text='      + encodeURIComponent(ptitle);
						url += '&url='      + encodeURIComponent(Share.purl);
						url += '&counturl=' + encodeURIComponent(Share.purl);
						Share.popup(url, 'twitter');
					},
					gplus: function(){
						url  = 'https://plusone.google.com/_/+1/confirm?hl=ru';
						url += '&url='+ encodeURIComponent(Share.purl);
						Share.popup(url, 'gplus')
					},
					popup: function(url, soc) {
						window.open(url,'','toolbar=0,status=0,width=626,height=436');
						$.ajax({
							type: 'POST',
							data: {
								do: 'shareRecord',
								social: soc,
								type: 'shop',
								id: '<?=$good['Code']?>'
							},
							success: function (data) {
								$('#share_'+soc).text(parseInt($('#share_'+soc).text())+1);
							}
						});
					}
				};
			</script>
		<?php } ?>

		<script>
		var cdn = '<?=$GLOBALS['CDN_LINK']?>';
		var iLinkLang = '<?=$_SESSION['linkLang']?>';
		var iUser = '<?=$_SESSION['userID']?>';
		var resized = false;
		changeImgOld = 2;

		function resizeChanges(resized){
			$('.topCaptionShadow').css({'position':'fixed', 'top' : $('.topCaption').outerHeight()});
			leftMenuClick();
			if((document.location.pathname.split('/')['1'] != '' && document.location.pathname.split('/')['1'] != 'uk' && document.location.pathname.split('/')['1'] != 'tovar') || (document.location.pathname.split('/')['1'] == 'uk' && document.location.pathname.split('/')['2'] != 'tovar')){
				if($(window).width() < 621){
					$('.mainMenu').css('display', 'none');
				}else{
					$('.mainMenu').css('display', 'block');
				}
			}else if(document.location.pathname.split('/')['1'] == 'tovar' || (document.location.pathname.split('/')['1'] == 'uk' && document.location.pathname.split('/')['2'] == 'tovar')){
				if($(window).width() < 460){
					$('body,html').animate({scrollTop: 250}, 400);
				}
			}

			if($(window).width() < 1200){
				$('.mainMenuCenter > ul > li').find('a:first').each(function(){
					if(this.getAttribute('href') && $(this).next()[0]){
						this.setAttribute('data-href', this.getAttribute('href'));
						this.removeAttribute('href');
					}
				});
			}else{
				$('.mainMenuCenter > ul > li').find('a:first').each(function(){
					if(this.getAttribute('data-href') && $(this).next()[0]){
						this.setAttribute('href', this.getAttribute('data-href'));
						this.removeAttribute('data-href');
					}
				});
			}
		}

		function leftMenuClick(){
			$(".menuMobile").on(hasTouch ? 'touchend' : 'click', function(e){
				if(hasTouch && isTouchMoved(e)){
					return false;
				}
				$(".menuMobileContainer").animate({left:"0"}, 150);
				window.left = $(".menuMobileContainer").css("left");
			});

			$(":not(.menuMobileContainer)").on(hasTouch ? 'touchend' : 'click', function(e){
				if(hasTouch && isTouchMoved(e)){
					return false;
				}
				var left = $(".menuMobileContainer").css("left");
				if(left == '0px'){
					$(".menuMobileContainer").animate({left:"-250px"}, 150);}
			});
		}

		function tableResize(){
			if($(window).width() < 1200){
				$("td").replaceWith(function(index, oldHTML){
					return $("<tr>").html(oldHTML);
				});
				$(".categoryDescription table").css({'width': 'auto', 'margin': '0 auto'});
				$(".categoryDescription img").css({'padding-top': '20px'});
			}else{
				$("tr > tr").replaceWith(function(index, oldHTML){
					return $("<td>").html(oldHTML);
				});
				$(".categoryDescription table").css({'width': tableCategoryDescriptionWidth, 'margin': '0 auto'});
			}
		}

		function buttonTouch(){
			$('.mainMenuCenter > ul > li').on(hasTouch ? 'touchend' : 'click', function(e){
				if(hasTouch && isTouchMoved(e)){
					return false;
				}

				if($(window).width() < 621){
					$(this).find('div').toggle(400);
					$(this).find('a:first').toggleClass('mobileLinksNone mobileLinks');
				}
			});
		}

		function mainMenuDisplay(){
			if(document.location.pathname.split('/')['1'] != ''/* && document.location.pathname.split('/')['1'] != 'tovar'*/){
				if($(window).width() < 621){
					$('.mainMenu').css('display', 'none');
				}else{
					$('.mainMenu').css('display', 'block');
				}
			}
		}

		$('document').ready(function(){
			var isFlashEnabled = false;
			var oldSize = $(window).width();
			tableCategoryDescriptionWidth = $(".categoryDescription table").width();

			outdatedBrowser({
				bgColor: '#f25648',
				lowerThan: 'transform',
				color: '#ffffff',
				languagePath: '/template/js/outdatedbrowser/lang/<?=$_SESSION['lang']?>.html'
			});

			$(window).resize(function(){
				$('.topCaptionShadow').css({'position':'fixed', 'top' : $('.topCaption').outerHeight()});
				if($(window).width() < 621 && oldSize != $(window).width()){
					$('.mainMenu ul > li > div').css({'display': 'none'});
					oldSize = $(window).width();
					resized = true;
				}else if($(window).width() > 621 && oldSize != $(window).width()){
					$('.mainMenu ul > li > div').css({'display': 'block'});
					$('.mainMenu ul > li').find('a:first').addClass('mobileLinksNone');
					oldSize = $(window).width();
					resized = false;
				}

				if($(window).width() >= 751){
					if($(document).scrollTop() > $('.subInfo').outerHeight()){
						$('.mainMenu').css({'position': 'fixed', 'top': $('.topCaption').outerHeight()});
					}
				}

				resizeChanges(resized);
				tableResize();
			});

			mainMenuDisplay();
			resizeChanges(resized);
			tableResize();
			buttonTouch();

			/*	            $('.mainMenuCenter > ul > li > a[class="mobileLinksNone"]').on(hasTouch ? 'touchend' : 'click', function(e){
			 if(hasTouch && isTouchMoved(e)){
			 return false;
			 }
			 if($(window).width() < 621){
			 e.preventDefault();
			 }
			 });*/


			oldHeight = $('#subInfoScroll').height()+3;
			$(document).scroll(function(){
				if($(document).scrollTop()>0){
					$('#subInfoScroll').height(0);
				}else{
					$('#subInfoScroll').height(oldHeight);
				}
				if($(document).scrollTop() > $('#subInfoScroll').outerHeight()){
					if(!$("div").is(".menuEmulator")){
						$("<div class='menuEmulator'></div>").insertAfter($("#subInfoScroll"));
						if($( window ).width() >= 751){
							$('.topAction').css({'height': 0});
							$('.topAction').hide();
							$('.topCaptionShadow').css({'position':'fixed', 'top' : $('.topCaption').outerHeight()});
							$('.mainMenu').css({'position': 'fixed', 'top': $('.topCaption').outerHeight()});
						}
						$('.up').addClass('visible');
					}
				}else{
					$('.mainMenu').css({'position':'relative', 'top':'0'});
					if($( window ).width() >= 751){
						$('.topAction').css({'height': '20px'});
						$('.topAction').show();
					}
					$(".menuEmulator").detach();
					$('.up').removeClass('visible');
				}
			});

			$('.mainMenu').on('sticky-start', function(){$('.up').addClass('visible');});
			$('.mainMenu').on('sticky-end', function(){$('.up').removeClass('visible');});

			$('.up').click(function(){
				$('body, html, document').animate({ scrollTop: 0 }, 1000); //for all browser include safari
				return false;
			});

			$('.search-example').on(hasTouch ? 'touchend' : 'click', function(e){
				if(hasTouch && isTouchMoved(e)){ return false; }
				this.parentNode.parentNode.querySelector('#whatSearch').value = this.textContent;
			});

			$('select').each(function(){ this.replaceSelector(); });

			$('.event-mess').each(function(){ this.counter(); });

			// Проверка для всех браузеров, кроме IE
			if(typeof(navigator.plugins)!="undefined"
				&& typeof(navigator.plugins["Shockwave Flash"]) == "object"){
				isFlashEnabled = true;
			}else if(typeof  window.ActiveXObject != "undefined"){
				// Проверка для IE
				try{
					if(new ActiveXObject("ShockwaveFlash.ShockwaveFlash")){
						isFlashEnabled = true;
					}
				}catch(e){};
			}

			//if(false/*isFlashEnabled*/){
			//  $('.logo').append('<object type=application/x-shockwave-flash data=http://krasota-style.com.ua/flv/ks_logo_198x56_03.swf width="198" height="56"><param name=movie value=http://krasota-style.com.ua/flv/KS_logo_198x56_03.swf><param name=wmode value=opaque><param name=quality value=high></object>');
			//}else{
			// $('.logo').append('<img src="/img/new_logo.png?1">');
			//}
			addaptiveLogo();
			$(window).resize(function(){
				addaptiveLogo();
			});

			function addaptiveLogo(){
				if($(window).width()>900){
					changeImgNew = 0;
				}else{
					changeImgNew = 1;
				}
				if(changeImgNew != changeImgOld){
					if(changeImgNew == 0){
						$("#logoImg").attr('src', '/img/logo/new_logo_<?=$_SESSION['lang']?>.png');
					}else{
						$("#logoImg").attr('src', '/img/logo/new_mobile_logo_<?=$_SESSION['lang']?>.png');
					}
					changeImgOld = changeImgNew;
				}
			}

			var maskList = $.masksSort($.masksLoad("/template/js/maskedphone/phone-codes.json").concat($.masksLoad("/template/js/maskedphone/phones-us.json")), ['#'], /[0-9]|#/, "mask");
			var maskOpts = {
				inputmask: {
					definitions: {
						'#': {
							validator: "[0-9]",
							cardinality: 1
						}
					},
					showMaskOnHover: false,
					autoUnmask: true,
					defaultCode: "<?=($_SESSION['lang'] == 'be' ? '375' : '38')?>"
				},
				match: /[0-9]/,
				replace: '#',
				list: maskList,
				listKey: "mask",
				onMaskChange: function(maskObj, completed){
					var flag = this.parentNode.querySelector('.flag');
					if(flag && completed){
						flag.setAttribute('class', 'flag flag-' + maskObj.cc.toLowerCase());
					}else if(flag){
						flag.setAttribute('class', 'flag');
					}
					$(this).attr("placeholder", $(this).inputmask("getemptymask").join(''));
				},
				onMaskBlur: function(maskObj, completed){
					var flag = this.parentNode.querySelector('.flag');
					if(flag && !completed){
						this.value = '';
						flag.setAttribute('class', 'flag');
					}
					$(this).attr("placeholder", $(this).inputmask("getemptymask").join(''));
				}
			};
			$('.input_phone').each(function(){
				$(this).inputmasks(maskOpts);
			});
			//maskPhones();


			<?php if($GLOBALS['string']['1'] == 'thanks' && $_SESSION['orderType'] == 'oneClick'){ ?>
			$('#modalOneClickOrder').modal().open();
			<?php } ?>

			<?php if($file == 'home'){ ?>
			if(window.addEventListener){
				var spans = document.querySelectorAll('.advantages li span');
				if(spans.length >= 1){
					for(i = 0; i < spans.length; i++){
						if(spans[i].addEventListener){
							spans[i].addEventListener('mouseover', function(e){ e.currentTarget.classList.add('shakeble'); var self = this; function fun(){ self.classList.remove('shakeble'); } setTimeout(fun, parseFloat(window.getComputedStyle(self).getPropertyValue('animation-duration') ? window.getComputedStyle(self).getPropertyValue('animation-duration') : (window.getComputedStyle(self).getPropertyValue('-webkit-animation-duration') ? window.getComputedStyle(self).getPropertyValue('-webkit-animation-duration') : 1)) * 1000);}, false);
						}
					}
				}

				var bannerOptions = {
					startSlideNumber: 1,
					interval: 5000,
					autostart: false,
					enableArrowControl: false,	//ставится автоматически true, параметр необходим для отключения
					//управления с помощью стрелок "назад" и "вперед" установлением
					//значения в false
					enableVerticalDrag: false,	//ставится автоматически true, параметр необходим для отключения
					//вертикальной прокрутки установлением значения в false
					enableHorizontalDrag: true,	//ставится автоматически true, параметр необходим для отключения
					//горизонтальной прокрутки установлением значения в false
					//enableMenuControl: true, //ставится автоматически true, параметр необходим для отключения
					//управления через меню установлением значения в false
					makeMobileSlide: true,

					menuPosition: 'bottom'
				};
				var infoOptions = {
					startSlideNumber: 2,
					interval: 10000,
					autostart: false,
					enableArrowControl: false,	//ставится автоматически true, параметр необходим для отключения
					//управления с помощью стрелок "назад" и "вперед" установлением
					//значения в false
					enableVerticalDrag: false,	//ставится автоматически true, параметр необходим для отключения
					//вертикальной прокрутки установлением значения в false
					enableHorizontalDrag: true,	//ставится автоматически true, параметр необходим для отключения
					//горизонтальной прокрутки установлением значения в false
					//enableMenuControl: true, //ставится автоматически true, параметр необходим для отключения
					//управления через меню установлением значения в false
					makeMobileSlide: false,
					menuPosition: 'top'
				};

				document.querySelector('.bannerSlider').sliderObject(bannerOptions);
				document.querySelector('.info').sliderObject(infoOptions);
			}
			<?php } ?>

			$('[data-width]').each(function(){
				$(this).css('width', $(this).attr('data-width'));
			});

			<?php if($file != 'goodscard' && $file != 'handmadecategory'){ ?>

			$('#modalItem .prev, #modalItem .next').on(hasTouch ? 'touchend' : 'click', function(e){
				if(hasTouch && isTouchMoved(e)){ return false; }
				e.preventDefault();
				buildModalItem(e.currentTarget);
			});

			shortcut.add("Ctrl+Left", function(){
				buildModalItem(document.querySelector('#modalItem .prev'));
			});

			shortcut.add("Ctrl+Right", function(){
				buildModalItem(document.querySelector('#modalItem .next'));
			});
			<?php } ?>

			saveUsersCart();

			Visibility.every(60000, function(){
				updateMinicartInfo();
			});
			<?php if($file == 'order'){ ?>
			setOrderMoney();
			Visibility.every(60000, function(){
				setOrderMoney();
			});
			<?php } ?>
			<?php if($file == 'withLeft_cabinet' && $cabinetPage == 'orderhistory'){ ?>
			getMyOrders();
			$('body').on('click', '#orderContent', function(e){
				e.preventDefault();
				if(e.currentTarget.getAttribute('data-index') && ordersArray[e.currentTarget.getAttribute('data-index')]){
					$('#modalOrder').modal().open();
					buildContent(ordersArray[e.currentTarget.getAttribute('data-index')], '#modalOrder');
				}
			});
			<?php } ?>
			<?php if($file == 'tickets'){ ?>
			var galileoProject = "https://krasota-style.galileo.com.ua";
			function galileoIni(){
				var galileoIframe = document.createElement('iframe');
				galileoIframe.src = galileoProject + "?_origin=" + location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '');
				galileoIframe.width = "100%";
				galileoIframe.height = "540px";
				galileoIframe.scrolling = "no";
				galileoIframe.frameBorder = 0;

				document.getElementById('galileoForm').appendChild(galileoIframe);

				function galileoListener(event){
					if (event.origin != galileoProject) return;
					galileoIframe.style.height = event.data.height + "px";
					if (event.data.reload) window.scrollTo(0, 0);
				}
				if (window.addEventListener) window.addEventListener("message", galileoListener, false);
				else window.attachEvent("onmessage", galileoListener);
			}
			galileoIni();
			<?php } ?>

			<?php if($file == 'search'){ ?>
			var path = document.location.pathname.split('/');
			var index = (path[1] == 'search' ? 2 : (path[2] == 'search' ? 3 : 0));
			if(index){
				var currentPage = path[index + 1] ? path[index + 1].replace(/\D+/, '') : '1';
				findMeSome(path[index], currentPage);
			}
			<?php } ?>

			if(!(hasTouch || $(window).width() < 460)){
				_shcp = []; _shcp.push({widget_id : 585603, widget : "Chat"}); (function() { var hcc = document.createElement("script"); hcc.type = "text/javascript"; hcc.async = true; hcc.src = "https://widget.siteheart.com/apps/js/sh.js"; var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(hcc, s.nextSibling); })();
			}
		});

		$('body').on(hasTouch ? 'touchend' : 'click', '.link-hide', function(e){
			if(hasTouch && isTouchMoved(e)){ return false; }
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

		$('body').on(hasTouch ? 'touchend' : 'click', '.menuMobileContainer li', function(e){
			if(hasTouch && isTouchMoved(e)){ return false; }
			var dataHref = $(this).find('.link-hide').attr('data-href');
			if(dataHref){
				e.preventDefault();
				if($(this).find('.link-hide').attr('data-target') == '_blank'){
					window.open(dataHref);
				}else{
					location.href = dataHref;
				}
				return false;
			}
		});

		$('body').on(hasTouch ? 'touchend' : 'click', '#login .link-hide', function(e){
			if(hasTouch && isTouchMoved(e)){ return false; }
			e.preventDefault();
			$('#modalLogin').modal().open();
			maskPhones();
		});

		$('body').on(hasTouch ? 'touchend' : 'click', '.counter .minus', function(e){
			if(hasTouch && isTouchMoved(e)){ return false; }
			e.preventDefault();
			decrementItem(e.currentTarget);
		});

		$('body').on(hasTouch ? 'touchend' : 'click', '.counter .plus', function(e){
			if(hasTouch && isTouchMoved(e)){ return false; }
			e.preventDefault();
			incrementItem(e.currentTarget);
		});

		$('body').on(hasTouch ? 'touchend' : 'click', '.yellowButton.buy', function(e){
			if(hasTouch && isTouchMoved(e)){ return false; }
			e.preventDefault();
			addToCart(e.currentTarget);
		});

		$('body').on(hasTouch ? 'touchend' : 'click', '.openCart', function(e){
			if(hasTouch && isTouchMoved(e)){ return false; }
			e.preventDefault();
			openCart();
		});

		function changePhoto(index){
			function toggleVisibility(index){
				$('.modal .itemPhoto img').removeClass('visible').addClass('hidden');
				$('.modal .itemPhoto #modalPhotoId_' + index).removeClass('hidden').addClass('visible');
			}
			$('.modal .itemPhoto .visible').removeClass('visible');
			setTimeout(function(){ toggleVisibility(index); }, 300);
			$('.modal .dopItemPhoto div div').removeClass('act');
			$('.modal .dopItemPhoto #dopPhoto' + index).addClass('act');
			if(typeof(updateShortcut) === 'function'){
				updateShortcut(index);
			}
		}

		$('body').on(hasTouch ? 'touchend' : 'click', '.dopItemPhoto img', function(e){
			if(hasTouch && isTouchMoved(e)){ return false; }
			e.preventDefault();
			changePhoto(parseInt($(this).attr('data-modal-index')));
		});
		<?php if($file == 'category'){ ?>
		var data = <?=$dataGoods?>;
		itemsArray = itemsArray.concat(data.goods);
		if(data.pages.count > 1){
			buildPagination(data.pages);
			if(data.pages.current != data.pages.count){
				var triangle = document.createElement('div');
				var triangleInner = document.createElement('div');
				triangleInner.setAttribute('class', 'triangle-orange-inner');
				triangle.setAttribute('class', 'triangle-orange');
				triangle.appendChild(triangleInner);
				var btn = document.createElement('div');
				btn.appendChild(triangle);
				btn.setAttribute('class', 'loaded');
				btn.setAttribute('data-startPage', data.pages.start);
				btn.setAttribute('data-nextPage', data.pages.current + 1);
				btn.addEventListener ? btn.addEventListener('click', function(e){ e.currentTarget.setAttribute('class', 'loading'); e.currentTarget.setAttribute('disabled', true); getMoreGoods(e.currentTarget); }, false) : btn.attachEvent && btn.attachEvent('click', function(e){ e.currentTarget.setAttribute('class', 'loading'); e.currentTarget.setAttribute('disabled', true); getMoreGoods(e.currentTarget); });
				document.querySelector('.catalog').insertBefore(btn, document.querySelector('.catalog').lastElementChild);
			}
		}

		var params = getSearchParameters();

		for(var key in params){
			if(params[key]['0'] == 'var'){
				for(var i = 1; i < params[key].length; i++){
					var _var = document.querySelector('#var_' + params[key][i]);
					if(_var){
						_var.checked = true;
					}
				}
			}
		}

		$(".filter-row input[type=checkbox]").on('change', function(){
			var optId = this.getAttribute('data-opt-id');
			if(optId && this.value){
				if(params['lastpage']){
					delete params['lastpage'];
				}
				if(params['filter'] != 'goods'){
					params['filter'] = ['goods'];
					params[optId] = [];
					params[optId].push('var');
					params[optId].push(this.value);
				}else{
					if(this.checked){
						if(params[optId]){
							params[optId].push(this.value);
						}else{
							params[optId] = [];
							params[optId].push('var');
							params[optId].push(this.value);
						}
					}else{
						if(params[optId]){
							var index = params[optId].indexOf(this.value);
							if(index > 0 && params[optId].length > 2){
								params[optId].splice(index, 1);
							}else if(index > 0 && params[optId].length <= 2){
								delete params[optId];
							}
						}
					}
				}
			}
			location.href = buildLinkFromParams(params, true);
		});

		$('#priceMin, #priceMax').bind({
			cut: function(){ priceChanged(); },
			paste: function(){ priceChanged(); },
			input: function(){ priceChanged(); }
		});

		function priceChanged(){
			var prMin = parseFloat(document.querySelector('#priceMin').value);
			var prMax = parseFloat(document.querySelector('#priceMax').value);
			if(!prMin || !prMax){ return false; }
			var slider = $("#priceSlider").data("ionRangeSlider");
			slider.update({from:prMin, to:prMax});
		}

		function updateFilter(data){
			if(params['lastpage']){
				delete params['lastpage'];
			}
			if(params['filter'] != 'goods'){
				params['filter'] = ['goods'];
				params['prmin'] = [];
				params['prmin'].push(data.from);
				params['prmax'] = [];
				params['prmax'].push(data.to);
			}else{
				if(params['prmin'] && params['prmax']){
					params['prmin']['0'] = data.from;
					params['prmax']['0'] = data.to;
				}else{
					params['prmin'] = [];
					params['prmin'].push(data.from);
					params['prmax'] = [];
					params['prmax'].push(data.to);
				}
			}
		}

		$("#priceSlider").ionRangeSlider({
			type: "double",
			min: <?=$prices['mini'] ? $prices['mini'] : 0?>,
			max: <?=$prices['maxi'] ? $prices['maxi'] : 0?>,
			from: <?=$_GET['prmin'] ? $_GET['prmin'] : ($prices['mini'] ? $prices['mini'] : 0)?>,
			to: <?=$_GET['prmax'] ? $_GET['prmax'] : ($prices['maxi'] ? $prices['maxi'] : 0)?>,
			keyboard: true,
			step: <?=($_SESSION['domainInfo']['coins'] ? 0.01 : 1)?>,
			onStart: function(data){
				$("#priceMin").val(data.from);
				$("#priceMax").val(data.to);
			},
			onChange: function(data){
				$("#priceMin").val(data.from);
				$("#priceMax").val(data.to);
			},
			onFinish: function(data){
				updateFilter(data);
			},
			onUpdate: function(data){
				updateFilter(data);
			}
		});

		$('#updatePriceFilter').on(hasTouch ? 'touchend' : 'click', function(e){
			if(hasTouch && isTouchMoved(e)){ return false; }
			e.preventDefault();
			location.href = buildLinkFromParams(params, true);
		});

		$('body').on(hasTouch ? 'touchend' : 'click', '.filter-head', function(e){
			if(hasTouch && isTouchMoved(e)){ return false; }
			e.preventDefault();
			var fr = $(this).parent().children('.filter-rows');
			if(fr.hasClass('hidden')){
				fr.show(350, function(){ fr.toggleClass('hidden')});
			}else{
				fr.hide(350, function(){ fr.toggleClass('hidden')});
			}
		});
		<?php } ?>

		<?php if($file == 'handmadecategory'){ ?>
		var data = <?=$dataGoods?>;
		itemsArray = itemsArray.concat(data.goods);
		if(data.pages.count > 1){
			buildPagination(data.pages);
			if(data.pages.current != data.pages.count){
				var triangle = document.createElement('div');
				var triangleInner = document.createElement('div');
				triangleInner.setAttribute('class', 'triangle-orange-inner');
				triangle.setAttribute('class', 'triangle-orange');
				triangle.appendChild(triangleInner);
				var btn = document.createElement('div');
				btn.appendChild(triangle);
				btn.setAttribute('class', 'loaded');
				btn.setAttribute('data-startPage', data.pages.start);
				btn.setAttribute('data-nextPage', data.pages.current + 1);
				btn.addEventListener ? btn.addEventListener('click', function(e){ e.currentTarget.setAttribute('class', 'loading'); e.currentTarget.setAttribute('disabled', true); getMoreHandMadeGoods(e.currentTarget); }, false) : btn.attachEvent && btn.attachEvent('click', function(e){ e.currentTarget.setAttribute('class', 'loading'); e.currentTarget.setAttribute('disabled', true); getMoreHandMadeGoods(e.currentTarget); });
				document.querySelector('.catalog').insertBefore(btn, document.querySelector('.catalog').lastElementChild);
			}
		}
		<?php } ?>

		<?php if($file == 'quiz'){ ?>

		$('body').on(hasTouch ? 'touchend' : 'click', '.quizRate', function(e){
			if(hasTouch && isTouchMoved(e)){ return false; }
			e.preventDefault();
			var target = e.currentTarget;
			var currentRating = target.parentNode.querySelector('.currentRating');
			var facts = {
				id: target.getAttribute('data-id'),
				rate: target.getAttribute('data-rate'),
				type: currentRating.getAttribute('data-type')
			};
			if(facts['id'] && facts['rate']){
				$.ajax({
					type: "POST",
					data: {
						'do': 'setQuizRate',
						'facts': JSON.stringify(facts)
					},
					success: function(data){
						currentRating.style.width = (data / 5 * 100) + '%';
						if(data < 4){
							$('.block-content-middle textarea[data-type="'+facts['type']+'"]').css('display', 'block');
						}else
							$('.block-content-middle textarea[data-type="'+facts['type']+'"]').css('display', 'none');
					}
				});
			}
		});

		function sendRecall(id){
			var facts = {id: id};
			$('.contentCenterMiddle textarea').each(function(){
				facts['text'+this.getAttribute('data-type')] = $(this).val();
			});
			$.ajax({
				type: "POST",
				data: {
					'do': 'setQuizRecall',
					'facts': JSON.stringify(facts)
				},
				success: function(data){
					var content = $('.contentCenterMiddle');
					content.html('Мы благодарим Вас за уделенное время!<br>Ваши замечания будут учтены и исправлены.');
					$(content).css({
						'font-size': '24px',
						'color': 'rgb(3, 167, 212)',
						'line-height': '1.3',
						'text-align': 'center'
					});
				}
			});
		}
		<? } ?>

		<?php if($file == 'goodscard' || $file == 'category' || $file == 'search'){ ?>
		$('body').on(hasTouch ? 'touchend' : 'click', '.shop-star', function(e){
			if(hasTouch && isTouchMoved(e)){ return false; }
			e.preventDefault();
			var target = e.currentTarget;
			var item = target.getAttribute('data-item');
			var rate = target.getAttribute('data-rate');
			if(item && rate){
				$.ajax({
					type: "POST",
					data: {
						"do": "setItemRate",
						"itemID": item,
						"rate": rate
					},
					success: function(data){
						if(data){
							data = parseInt(data);
							var stars = target.parentNode.querySelectorAll('.shop-star');
							if(stars.length > 0){
								var current = false;
								for(var i = 0; i < stars.length; i++){
									if(data >= parseInt(stars[i].getAttribute('data-rate')) && !current){
										stars[i].setAttribute('class', 'shop-star current');
										current = true;
									}else{
										stars[i].setAttribute('class', 'shop-star');
									}
								}
							}
						}
					}
				});
			}
		});

		<?php } ?>

		<?php if($file == 'goodscard'){ ?>
		$('body').on(hasTouch ? 'touchend' : 'click', '.rews .rewsAnswer', function(e){
			if(hasTouch && isTouchMoved(e)){ return false; }
			e.preventDefault();
			var rewsType = e.target.getAttribute('data-rews-type');
			var rewsTarget = e.target.getAttribute('data-rews-target');
			$('#modalRews').modal({onOpen: function(el, options){
				$(el).find('input[name="type"]').val(rewsType);
				$(el).find('input[name="target"]').val(rewsTarget);
			} }).open();
		});

		$('body').on(hasTouch ? 'touchend' : 'click', '.answerControl', function(e){
			if(hasTouch && isTouchMoved(e)){ return false; }
			e.preventDefault();
			$('.tabControl[data-tab-label="reviews"]').click();
		});

		$('body').on(hasTouch ? 'touchend' : 'click', '.tabControl', function(e){
			if(hasTouch && isTouchMoved(e)){ return false; }
			e.preventDefault();
			if($(this).attr('data-tab-label')){
				$(this).parent().children().removeClass('active');
				$(this).addClass('active');
				if($(this).attr('data-tab-label') == 'characteristics'){
					$('.tab').removeClass('active');
					$('.tab[data-tab-label=main]').addClass('active');
					$('.tab[data-tab-label=main] .rews').hide();;
					$('.tab[data-tab-label=main] .chars').show();
					$('.tab[data-tab-label=main] .itemDescription').show();
					$('.RewsToRewsWrap').hide();
				}else if($(this).attr('data-tab-label') == 'main'){
					$('.tab').removeClass('active');
					$('.tab[data-tab-label=main]').addClass('active');
					$('.tab[data-tab-label=main] .rews').show();
					$('.tab[data-tab-label=main] .chars').show();
					$('.tab[data-tab-label=main] .itemDescription').hide();;
					$('.RewsToRewsWrap').hide();
				}else if($(this).attr('data-tab-label') == 'reviews'){
					$('.tab').removeClass('active');
					$('.tab[data-tab-label=main]').addClass('active');
					$('.tab[data-tab-label=main] .rews').show();
					$('.tab[data-tab-label=main] .itemDescription').hide();;
					$('.tab[data-tab-label=main] .chars').hide();;
					$('.RewsToRewsWrap').show();
				}else{
					$('.tab').removeClass('active');
					$('.tab[data-tab-label='+$(this).attr('data-tab-label')+']').addClass('active');
				}
			}
		});


		if(location.href.match(/(.*)#tab-/)){
			var tab = location.href.replace(/(.*)#tab-/, '');
			if($('.tabControl[data-tab-label="' + tab + '"]')){
				$('.tabControl[data-tab-label="' + tab + '"]').click();
				$('html, body').animate({
					scrollTop: $('.tabControl[data-tab-label="' + tab + '"]').offset().top - 200
				}, 1500);
			}
		}

		$('body').on(hasTouch ? 'touchend' : 'click', '.itemPhotos img', function(e){
			if(hasTouch && isTouchMoved(e)){ return false; }
			e.preventDefault();
			$('#modalImage').modal().open();
			var index = parseInt($(this).attr('data-modal-index'));
			$('#modalImage .itemPhoto img').removeClass('visible');
			$('#modalImage .itemPhoto img').addClass('hidden');
			$('#modalImage .itemPhoto #modalPhotoId_' + index).removeClass('hidden').addClass('visible');
			<?php if($dopPhotosSize >= 1){ ?>
			$('#modalImage .dopItemPhoto div div').removeClass('act');
			$('#modalImage .dopItemPhoto #dopPhoto' + index).addClass('act');
			$('#modalImage .prev').attr('data-index', (index > 0 ? index - 1 : <?=$dopPhotosSize?>));
			$('#modalImage .next').attr('data-index', (index < <?=$dopPhotosSize?> ? index + 1 : 0));
		});

		var dopPhotoOptions = {
			startSlideNumber: 1,
			autostart: false,
			enableArrowControl: true,	//ставится автоматически true, параметр необходим для отключения
			//управления с помощью стрелок "назад" и "вперед" установлением
			//значения в false
			enableVerticalDrag: false,	//ставится автоматически true, параметр необходим для отключения
			//вертикальной прокрутки установлением значения в false
			enableHorizontalDrag: true,	//ставится автоматически true, параметр необходим для отключения
			//горизонтальной прокрутки установлением значения в false
			enableMenuControl: false, //ставится автоматически true, параметр необходим для отключения
			//управления через меню установлением значения в false
			makeMobileSlide: false,
			inCircle: false //отключаем круговое движение слайдов
		};

		document.querySelector('.itemImageSlider').sliderObject(dopPhotoOptions);

		$('#modalImage .prev, #modalImage .next').on(hasTouch ? 'touchend' : 'click', function(){ changePhoto(parseInt($(this).attr('data-index'))); });

		shortcut.add("Ctrl+Left", function(){ changePhoto(parseInt($('#modalImage .prev').attr('data-index'))); });

		shortcut.add("Ctrl+Right", function(){ changePhoto(parseInt($('#modalImage .next').attr('data-index'))); });

		function updateShortcut(index){
			$('#modalImage .prev').attr('data-index', (index > 0 ? index - 1 : <?=$dopPhotosSize?>));
			$('#modalImage .next').attr('data-index', (index < <?=$dopPhotosSize?> ? index + 1 : 0));
		}
		<?php }else{ ?>
		});
		<?php } } ?>

		<?php if($file == 'handmadegoodscard'){ ?>
		$('body').on(hasTouch ? 'touchend' : 'click', '.tabControl', function(e){
			if(hasTouch && isTouchMoved(e)){ return false; }
			e.preventDefault();
			if($(this).attr('data-tab-label')){
				$(this).parent().children().removeClass('active');
				$(this).addClass('active');
				if($(this).attr('data-tab-label') == 'characteristics'){
					$('.tab').removeClass('active');
					$('.tab[data-tab-label=main]').addClass('active');
					$('.tab[data-tab-label=main] .rews').hide();;
					$('.tab[data-tab-label=main] .chars').show();
					$('.tab[data-tab-label=main] .itemDescription').show();
					$('.RewsToRewsWrap').hide();
				}else if($(this).attr('data-tab-label') == 'main'){
					$('.tab').removeClass('active');
					$('.tab[data-tab-label=main]').addClass('active');
					$('.tab[data-tab-label=main] .rews').show();
					$('.tab[data-tab-label=main] .chars').show();
					$('.tab[data-tab-label=main] .itemDescription').hide();;
					$('.RewsToRewsWrap').hide();
				}else if($(this).attr('data-tab-label') == 'reviews'){
					$('.tab').removeClass('active');
					$('.tab[data-tab-label=main]').addClass('active');
					$('.tab[data-tab-label=main] .rews').show();
					$('.tab[data-tab-label=main] .itemDescription').hide();;
					$('.tab[data-tab-label=main] .chars').hide();;
					$('.RewsToRewsWrap').show();
				}else{
					$('.tab').removeClass('active');
					$('.tab[data-tab-label='+$(this).attr('data-tab-label')+']').addClass('active');
				}
			}
		});

		$('body').on(hasTouch ? 'touchend' : 'click', '.itemPhotos img', function(e){
			if(hasTouch && isTouchMoved(e)){ return false; }
			e.preventDefault();
			$('#modalImage').modal().open();
			var index = parseInt($(this).attr('data-modal-index'));
			$('#modalImage .itemPhoto img').removeClass('visible');
			$('#modalImage .itemPhoto img').addClass('hidden');
			$('#modalImage .itemPhoto #modalPhotoId_' + index).removeClass('hidden').addClass('visible');
			<?php if($dopPhotosSize >= 1){ ?>
			$('#modalImage .dopItemPhoto div div').removeClass('act');
			$('#modalImage .dopItemPhoto #dopPhoto' + index).addClass('act');
			$('#modalImage .prev').attr('data-index', (index > 0 ? index - 1 : <?=$dopPhotosSize?>));
			$('#modalImage .next').attr('data-index', (index < <?=$dopPhotosSize?> ? index + 1 : 0));
		});

		var dopPhotoOptions = {
			startSlideNumber: 1,
			autostart: false,
			enableArrowControl: true,	//ставится автоматически true, параметр необходим для отключения
			//управления с помощью стрелок "назад" и "вперед" установлением
			//значения в false
			enableVerticalDrag: false,	//ставится автоматически true, параметр необходим для отключения
			//вертикальной прокрутки установлением значения в false
			enableHorizontalDrag: true,	//ставится автоматически true, параметр необходим для отключения
			//горизонтальной прокрутки установлением значения в false
			enableMenuControl: false, //ставится автоматически true, параметр необходим для отключения
			//управления через меню установлением значения в false
			makeMobileSlide: false,
			inCircle: false //отключаем круговое движение слайдов
		};

		document.querySelector('.itemImageSlider').sliderObject(dopPhotoOptions);

		$('#modalImage .prev, #modalImage .next').on(hasTouch ? 'touchend' : 'click', function(){ changePhoto(parseInt($(this).attr('data-index'))); });

		shortcut.add("Ctrl+Left", function(){ changePhoto(parseInt($('#modalImage .prev').attr('data-index'))); });

		shortcut.add("Ctrl+Right", function(){ changePhoto(parseInt($('#modalImage .next').attr('data-index'))); });

		function updateShortcut(index){
			$('#modalImage .prev').attr('data-index', (index > 0 ? index - 1 : <?=$dopPhotosSize?>));
			$('#modalImage .next').attr('data-index', (index < <?=$dopPhotosSize?> ? index + 1 : 0));
		}
		<?php }else{ ?>
		});
		<?php } } ?>


		$('#notcall').on(hasTouch ? 'touchend' : 'click', function(e){
			if(hasTouch && isTouchMoved(e)){ return false; }
			e.preventDefault();
			$('#modalCallback').modal({onBeforeClose: function(el, options){ $('input[name=captcha]').data('tooltipsy').hide();} }).open();
			maskPhones();
			updateCaptcha(document.querySelector(".reloadCaptchaz"));
		});

		$('#addQuestion').on(hasTouch ? 'touchend' : 'click', function(e){
			if(hasTouch && isTouchMoved(e)){ return false; }
			e.preventDefault();
			$('#modalQuestion').modal({onBeforeClose: function(el, options){ $('input[name=captcha]').data('tooltipsy').hide();} }).open();
			maskPhones();
			updateCaptcha(document.querySelector(".reloadCaptchaz"));
		});

		$('body').on(hasTouch ? 'touchend' : 'click', '.openModalItem', function(e){
			if(hasTouch && isTouchMoved(e)){ return false; }
			e.preventDefault();
			if(buildModalItem(e.currentTarget)){
				$('#modalItem').modal().open();
			}
		});

		$('#sendReview').on(hasTouch ? 'touchend' : 'click', function(e){
			if(hasTouch && isTouchMoved(e)){ return false; }
			e.preventDefault();
			$('#modalReview').modal().open();
		});

		$('#otherPhones').on(hasTouch ? 'touchend' : 'click', function(e){
			if(hasTouch && isTouchMoved(e)){ return false; }
			e.preventDefault();
			$('#modalSupport').modal().open();
		});

		if(hasTouch){
			$('body').on('touchmove', function(e){
				e.target.isTouchMoved = true;
			});
		}

		$('.closeCart, .close').on(hasTouch ? 'touchend' : 'click', function(e){
			if(hasTouch && isTouchMoved(e)){ return false; }
			e.preventDefault();
			$.modal().close();
		});

		$("#modalLogin").keyup(function(e){
			if(e.keyCode == 13) {
				$('#loginMeButton').click();
			}
		});

		$('#loginMeButton').on('click', function(e){
			if(!e.target.disabled){
				loginMe(e.target);
			}
		});

		/* 			$('.submodal .closesubmodal').on('click', function(e){
		 e.preventDefault();
		 $('.submodal').css('display', 'none');
		 });

		 function openSubmodal(src) {
		 $('.subblock').innerHTML = '<img src="'+src+'">';
		 $('.submodal').css('display', 'block');
		 }

		 function openModal(src) {
		 document.querySelector(".zakazBlock").innerHTML = '<img src="'+src+'">';
		 $('#zakazModal').modal().open();
		 } */

		$('input[name=captcha]').tooltipsy({
			alignTo: 'element',
			offset: [0, 10],
			className: 'captchaTooltip',
			content: texts['alertText']['notValidCaptcha'],
			showEvent: '',
			hideEvent: 'focus',
			show: function(e, $el){
				$el.fadeIn(100);
			},
			hide: function(e, $el){
				$el.fadeOut(100);
			}
		});
		<?php if($_GET['utm_source'] == 'cart' && $_GET['utm_medium'] == 'email'){?>
		openCart();
		<?php } ?>
		</script>

		<!-- Rating@Mail.ru counter -->
		<script type="text/javascript">//<![CDATA[
			var _tmr = _tmr || [];
			_tmr.push({id: "2413910", type: "pageView", start: (new Date()).getTime()});
			(function (d, w) {
				var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true;
				ts.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//top-fwz1.mail.ru/js/code.js";
				var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
				if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
			})(document, window);
			//]]>
		</script>

		<noscript>
			<div style="position:absolute;left:-10000px;">
				<img src="//top-fwz1.mail.ru/counter?id=2413910;js=na" style="border:0;" height="1" width="1" alt="Рейтинг @Mail.ru">
			</div>
		</noscript>
		<!-- //Rating@Mail.ru counter -->

		<!-- VK retargeting -->
		<img src="//vk.com/rtrg?r=LXS6KIMlu*0qeBY1uk3C4iwTU0FDzgYvZjG132B7CndC/U9gi5Oz8kwErJyVgvaYWa/2A/LiTCuJF7*9aOb6APB4LtKz4riZhlTDeNVybJWWRqohEp52rv3HaxqjNIckw/fhtouLqPmEbcY5noseYx39u/Pt1PxTHoseiL5efxw-" width="1" height="1" alt="vkretarget image">
		<!-- //VK retargeting -->

		<!-- MarketGid Sensor -->
		<script type="text/javascript">
			(function(){
				var d = document, w = window; w.MgSensorData = {cid:197715,lng:"ru",
					nosafari:true}; var n = d.getElementsByTagName("script")[0]; var s =
					d.createElement("script"); s.type = "text/javascript"; s.async = true;
				var dt = !Date.now?new Date().valueOf():Date.now(); s.src =
					"//a.marketgid.com/mgsensor.js?d=" + dt; n.parentNode.insertBefore(s,
					n);
			})();
		</script> <!-- /MarketGid Sensor -->

		<!-- Admixer retargeting -->
		<script src='//cdn.admixer.net/scriptlib/retarg.js'></script>
		<script>window.admixerBh.retarg(null,["-303461128"], []);</script>
		<!-- //Admixer retargeting -->

	<?php if($url['1'] == 'bizhuteriya'){ ?>
		<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=prP*mrHBt*D2E95PWgeUP1gfQldgrYWTe0HoaJaPicUJISoRP4jO0HlWJFQa*218PL8qNolDsPVj9uFvGkcXrOp0U2EY0dJQLfnaEPC9mQB9gx5NggyOBqDCtf99vigaFI7NHSqxl*QPFySwghcnYjJrPDfgszVUtgN3PBNg3Vk-';</script>
	<?php } ?>

	<?php if($url['1'] == 'tovar'){ ?>
		<script type='text/javascript'><!--
			window['rnt_aud_params'] = window['rnt_aud_params'] || [];
			window['rnt_aud_params'].push({ key: 'pId|6641_69e2ff83-64a7-487b-9c90-54b43a31b2f2|91', val: '<?=$good['ID']?>'});
			//--></script>

		<script type='text/javascript'><!--
			(function (w, d, n) {
				w[n] = w[n] || [];
				w[n].push({id:'6641_69e2ff83-64a7-487b-9c90-54b43a31b2f2', url:'//uaadcodedsp.rontar.com/'});
				var a = document.createElement('script');
				a.type = 'text/javascript';
				a.async = true;
				a.src = '//uaadcodedsp.rontar.com/rontar_aud_async.js';
				var b = document.body;
				d.body.appendChild(a);
			})(window, document, 'rontar_aud');
			//--></script>
	<?php } ?>

	<?php $this->endBody() ?>
	</body>
</html>
<?php $this->endPage() ?>
