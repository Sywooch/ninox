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
								<span>СЕРЬГИ</span>
								<span class="price">17 грн </span>
								<span class="icons-fav-bask"></span>
							</div>
							<div></div>
						</div>
						<div class="goods-item">
							<div>
								<span>СЕРЬГИ</span>
								<span class="price">17 грн </span>
								<span class="icons-fav-bask"></span>
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
	<div class="all-banners">
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
	</div>
</div>
<div class="info">
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
</div>