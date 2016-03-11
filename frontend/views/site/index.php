<?php
use evgeniyrru\yii2slick\Slick;
use frontend\models\Good;
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
<!--<script type="text/javascript">
	$(document).ready(function() {
		$('img[src$=".svg"]').each(function() {
			var $img = jQuery(this);
			var imgURL = $img.attr('src');
			var attributes = $img.prop("attributes");

			$.get(imgURL, function(data) {
				// Get the SVG tag, ignore the rest
				var $svg = jQuery(data).find('svg');

				// Remove any invalid XML tags
				$svg = $svg.removeAttr('xmlns:a');

				// Loop through IMG attributes and apply on SVG
				$.each(attributes, function() {
					$svg.attr(this.name, this.value);
				});

				// Replace IMG with SVG
				$img.replaceWith($svg);
			}, 'xml');
		});
	});
</script>-->
<div class="main-content">
	<div class="main-content-slider">
		<?=!empty($items) ? Slick::widget([
				'containerOptions' => [
					'id'    => 'sliderFor',
					'class' => 'first'
				],
				'items' =>  '',
				'clientOptions' => [
					'arrohow'   => 1,
					'slidesToSws\'         => false,
					\'fade\'           => true,
					\'slidesToScroll' => 1,
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
				<div class="main-slider goods-items">
					<?=\yii\widgets\ListView::widget([
														 'dataProvider'	=>	new \yii\data\ArrayDataProvider(['models'	=>	[
															 Good::findOne(16),

														 ]]),
														 'itemView'	=>	function($model){
															 return $this->render('index/main-slider', ['good' =>
																 $model]);
														 },
														 'itemOptions'	=>	[
															 'class'	=>	''
														 ],
														 'summary'	=>	false
													 ])?>
				</div>
				<div class="goods-items">
					<div class="two-items content-items">
						<?=\yii\widgets\ListView::widget([
							 'dataProvider'	=>	new \yii\data\ArrayDataProvider(['models'	=>	[
								 Good::findOne(16),
								 Good::findOne(16),
							]]),
							 'itemView'	=>	function($model){
								 return $this->render('index/banner', ['good' => $model]);
							 },
							 'itemOptions'	=>	[
								 'class'	=>	'goods-item'
							 ],
							 'summary'	=>	false
						 ])?>
					</div>
					<div class="two-items content-banners">
						<?php
						echo $this->render('index/banner2');
						?>
						<?php
						echo $this->render('index/banner3');
						?>
					</div>
				</div>
				<?php
				echo $this->render('index/banner4');
				?>
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
				<?=\yii\widgets\ListView::widget([
					'dataProvider'	=>	new \yii\data\ArrayDataProvider([
						'models'	=>	[
							Good::findOne(16),
							Good::findOne(16),
							Good::findOne(16),
							Good::findOne(16),
							Good::findOne(16),
							Good::findOne(16),
							Good::findOne(16),
							Good::findOne(16),
						]]),
						'itemView'	=>	function($model){
							 return $this->render('index/good_card', ['good' => $model]);
						},
						'itemOptions'	=>	[
							'class'	=>	'goods-item'
						],
					    'summary'	=>	false
					])?>
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