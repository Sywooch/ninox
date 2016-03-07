<?php
use evgeniyrru\yii2slick\Slick;
use yii\helpers\Html;


$mainGalleryHtml = [];

if($reviews){
	$mainGalleryHtml[] = $this->render('_review_item', [
		'reviews' =>  $reviews
	]);
}
if($questions){
	$mainGalleryHtml[] = $this->render('_question_item', [
		'questions' =>  $questions
	]);
}

?>
<div class="main-content">
	<div class="main-content-slider">
		<?=!empty($items) ? Slick::widget([
				'containerOptions' => [
					'id'    => 'sliderFor',
					'class' => 'first'
				],
				'items' =>  '',
				'clientOptions' => [
					'arrows'         => false,
					'fade'           => true,
					'slidesToShow'   => 1,
					'slidesToScroll' => 1,
					'asNavFor'       => '#sliderNav',
				]
		]) : '<div style="height: 370px;"></div>'
		?>
	</div>
	<div class="arrow-bottom">
	</div>
	<div class="main-content-items">
		<div class="goods-content">
			<div class="goods-content-main">
				<?=!empty($items) ? Slick::widget([
													  'containerOptions' => [
														  'id'    => 'sliderFor',
														  'class' => 'first'
													  ],
													  'items' =>  '',
													  'clientOptions' => [
														  'arrows'         => false,
														  'fade'           => true,
														  'slidesToShow'   => 1,
														  'slidesToScroll' => 1,
														  'asNavFor'       => '#sliderNav',
													  ]
												  ]) : '<div style="height: 450px;
																	width: 450px;
																	border-left: 1px solid rgb(236, 236, 236);
																	box-shadow: 0px 1px 0px rgb(236, 236, 236);
																	border-bottom: 1px solid rgb(222, 222, 222);
																	border-right: 1px solid rgb(236, 236, 236);
																	background: white;
																	border-radius: 4px;
																	float: left;
																	margin-right: 10px;
																	margin-left: 10px;">
														</div>'
				?>
				<div class="goods-items">
					<div class="two-items content-items">
						<div class="goods-item">
							<div>
								<span class="icons-fav-bask"></span>
								<span>СЕРЬГИ</span>
								<span class="price">17 грн </span>
							</div>
							<div></div>
						</div>
						<div class="goods-item">
							<div>
								<span class="icons-fav-bask"></span>
								<span>СЕРЬГИ</span>
								<span class="price">17 грн </span>
							</div>
							<div></div>
						</div>
					</div>
					<div class="two-items content-banners">
						<div class="goods-item goods-item-style">
							<span>ЯРМАРКА МАСТЕРОВ</span>
						</div>
						<div class="goods-item" style="background: url('/img/site/pram.png');">
						</div>
					</div>
				</div>
				<div class="goods-item right-item">
						<span class="price icons-fav-bask">4500 грн</span>
				</div>
			</div>
			<div class="goods-content-icons">
				<div class="main-icons">
					<div class="main-icon icon-best">
					</div>
					<span>Лучшее</span>
				</div>
				<div class="main-icons">
					<div class="main-icon icon-news">
					</div>
					<span>Новинки</span>
				</div>
				<div class="main-icons">
					<div class="main-icon icon-sale">
					</div>
					<span>Распродажа</span>
				</div>
			</div>
			<div class="goods-content-all">
				<div class="goods-item">
						<span class="item-id">2018074</span>
					<div class="item-image"></div>
					<span>Ножницы маникюрные, блистер (9041), 1 шт.</span>
					<div class="price-and-order">
						<span class="wholesale-price semi-bold">51.5 ГРН</span>
						<span class="retail-prce">66 ГРН</span>
						<div class="goods-basket"></div>
					</div>
				</div>
				<div class="goods-item">
					<span class="item-id">2018074</span>
					<div class="item-image"></div>
					<span>Ножницы маникюрные, блистер (9041), 1 шт.</span>
					<div class="price-and-order">
						<span class="wholesale-price semi-bold">51.5 ГРН</span>
						<span class="retail-prce">66 ГРН</span>
						<div class="goods-basket"></div>
					</div>
				</div>
				<div class="goods-item">
					<span class="item-id">2018074</span>
					<div class="item-image"></div>
					<span>Ножницы маникюрные, блистер (9041), 1 шт.</span>
					<div class="price-and-order">
						<span class="wholesale-price semi-bold">51.5 ГРН</span>
						<span class="retail-prce">66 ГРН</span>
						<div class="goods-basket"></div>
					</div>
				</div>
				<div class="goods-item">
					<span class="item-id">2018074</span>
					<div class="item-image"></div>
					<span>Ножницы маникюрные, блистер (9041), 1 шт.</span>
					<div class="price-and-order">
						<span class="wholesale-price semi-bold">51.5 ГРН</span>
						<span class="retail-prce">66 ГРН</span>
						<div class="goods-basket"></div>
					</div>
				</div>
				<div class="goods-item goods-item-style">
					<span>СМОТРЕТЬ ВСЕ ТОВАРЫ</span>
				</div>
			</div>
		</div>
	</div>
	<!--30 px gradiend -->

	<div class="news-and-events-content">
		<div class="news-and-events">
			<div class="news-events style">
				<span class="news-events-header">Новости и акции</span>
				<div class="news">
					<span class="news-header">
						Распродажа зонтов, расчесок и маникюрных наборов Zinger
					</span>
					<span>
						Каждый покупатель акционных ежедневников и планингов получает в подарок шариковую ручку BIC Atlantis!
					</span>
				</div>
				<div class="news">
					<span class="news-header">
						Распродажа зонтов, расчесок и маникюрных наборов Zinger
					</span>
					<span>
						Каждый покупатель акционных ежедневников и планингов получает в подарок шариковую ручку BIC Atlantis!
					</span>
				</div>
				<div class="news">
					<span class="news-header">
						Распродажа зонтов, расчесок и маникюрных наборов Zinger
					</span>
					<span>
						Каждый покупатель акционных ежедневников и планингов получает в подарок шариковую ручку BIC Atlantis!
					</span>
				</div>
				<span class="all-news-events">Все новости ></span>
			</div>
			<div class="subscribe style">
				<div class="style">
					<span>Подпишитесь на рассылку, не пропустите скидки</span>
					<div class="subscribe-email input-style-main">
						<input type="text" placeholder="Ваш эл. адрес">
						<?=\yii\helpers\Html::button('Подписаться', [
							'type'  =>  'submit',
							'class' =>  'blue-button small-button ',
							'id'    =>  'submit'
						])?>
					</div>
				</div>
				<div class="style"></div>
			</div>
			<div class="right-block style"></div>
			<div></div>
		</div>
	</div>
	<div class="about-main">
		<span class="about-main-title">Бижутерия и аксессуары в интернет-магазине Krasota-Style</span>
		<span class="about-main-content">
			Украшения всегда были любимы женщинами, которые увлекались красивыми аксессуарами сами и увлекали ими мужчин.
			Бижутерия – один из наиболее модных и ярких способов добавить образу блеска или загадочности.
			Krasota-style.com.ua - это тот самый интернет-магазин, который вы искали. Ведь здесь собраны многочисленные образцы бижутерии,
			которую больше не придется искать на разрозненных сайтах. Ведь именно здесь, в функциональном каталоге, собраны украшения,
			которые порадуют любую девушку и будут отличным подарком. Как часто предложения в интернете отличаются высокими ценами,
			ограничениями по сумме заказа, большим доплатам по доставке. Наш сайт работает для жителей Украины, чтобы в любом уголке страны покупатель
			мог легко и быстро сделать выбор, при этом существенно сэкономив на покупке.
			Наш виртуальный магазин всегда рад своим гостям и предлагает купить бижутерию и многи другие разнообразные товары онлайн в любое время суток.
			Интернет-магазин специализируется на продаже оптом, но для удобства покупателей опт разделен на мелкий и крупный.
			Стомость для мелкого опта позволит обычному покупателю приобрести товар в розницу по минимальной цене, а для крупных оптовиков цены будут еще ниже.
		</span>
		<span class="about-main-content-open">Читать полностью</span>
	</div>

<!--	<div class="all-banners">
		<div class="all-banners-center-line"></div>
		<div class="all-banners-center">
			<div class="single-banner left-banner">
				<?=$this->render('_banner_item', [
					'model' =>  $leftBanner
				])?>
			</div>
			<div class="central-banners">
				<?=!empty($centralBannersHtml) ? Slick::widget([
					'items' =>  $centralBannersHtml,
					'clientOptions' => [
						'autoplay'          =>  true,
						'dots'              =>  true,
						'autoplaySpeed'     =>  5000,
						'arrows'            =>  false,
						'customPaging'      =>  new \yii\web\JsExpression('function(slider, i) {
					return \'<span>\' + (i + 1) + \'</span>\';
				}')
					],
				]) : ''?>
			</div>
			<div class="single-banner right-banner">
				<?=$this->render('_banner_item', [
					'model' =>  $rightBanner
				])?>
			</div>
		</div>
	</div>
	<div class="advantages">
		<div class="advantagesCenter">
			<ul>
				<li id="label"></li>
				<li id="opt"><div><span><?=\Yii::t('shop', 'Оптовые цены на все товары')?></span></div></li>
				<li id="minorder"><div><span><?=\Yii::t('shop', 'Минимальный заказ')?> <?=(100 * \Yii::$app->params['domainInfo']['currencyExchange']).' '.\Yii::$app->params['domainInfo']['currencyShortName']?></span></div></li>
				<li id="ret"><div><span><?=\Yii::t('shop', 'Беспроблемный возврат')?></span></div></li>
				<li id="disc"><div><span><?=\Yii::t('shop', 'Скидки для оптовиков')?></span></div></li>
			</ul>
		</div>
	</div>-->
</div>
<!--<div class="info">
	<div class="info-center">
		<?=!empty($mainGalleryHtml) ? Slick::widget([
			'items' =>  $mainGalleryHtml,
			'clientOptions' => [
				'autoplay'          =>  false,
				'dots'              =>  true,
				'autoplaySpeed'     =>  5000,
				'arrows'            =>  false,
				'customPaging'      =>  new \yii\web\JsExpression('function(slider, i) {
                return \'<span>\' + $(slider.$slides[i]).children().data(\'label\') + \'</span>\';
            }')
			],
		]) : ''?>
	</div>
</div>-->