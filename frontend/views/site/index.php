<?php
use common\helpers\Formatter;
use evgeniyrru\yii2slick\Slick;
use frontend\helpers\BannerHelper;
use frontend\models\BannersCategory;
use yii\helpers\Html;
$mainGalleryHtml = [];

$this->title = \Yii::t('shop', 'Бижутерия, заколки, серьги, бисер оптом с доставкой по Киеву и Украине от интернет-магазина "Krasota-Style.ua"');
$this->registerMetaTag(
	[
		'name' => 'description',
		'content' => \Yii::t('shop',
			'Купить бижутерию оптом со скалда в Киеве. Более 10000 товаров. Оптовые цены. Минимальный заказ {minOrder} Беспроблемный возврат. Скидки для оптовиков.',
			[
				'minOrder'  =>  Formatter::getFormattedPrice(\Yii::$app->params['domainInfo']['minimalOrderSum'])
			])
	],
	'description'
);
$this->registerMetaTag(
	[
		'name' => 'keywords',
		'content' => \Yii::t('shop', 'Бижутерия, товары для рукоделия, оптом, интернет-магазин, Украина, Krasota-Style')
	],
	'keywords'
);


$sliderBanners = \frontend\helpers\SliderHelper::renderItems($centralBanners);

$js = <<<'JS'
$(".arrow-bottom").on('click', function(){
	$('html, body').animate({
        scrollTop: $('.arrow-bottom').offset().top - 15
    }, 1000);
});

$(".goods-content-icons .main-icons").on('click', function(e){
	var url = '/?act=goodsRow&type=' + this.getAttribute('data-attribute-tab');

	$.pjax({url: url, container: '#goods_tabs', push: false, replace: false, timeout: 10000,scrollTo: true});
});

$('#subscribeForm').on('submit', function(e){
	e.preventDefault();   
	
	var form = $(this);
	
    $.ajax({
        type: 'POST',
        url: '/subscribe',
        data: form.serialize(),
        success: function(){
        	form.html("спасибо за подписку!");
        }
    });
});
JS;

$this->registerJs($js);
?>
<div class="main-content-slider">
	<?=!empty($sliderBanners) ? Slick::widget([
			'containerOptions' => [
				'class' => 'main-slider'
			],
			'items' =>  $sliderBanners,
			'clientOptions' => [
				'arrow'   => 1,
				'slidesToSws'	=> true,
				'fade'          => true,
				'arrows'		=> true,
				'slidesToScroll'=> 1,
				'autoplay' 		=> true,
				'autoplaySpeed'	=> 3000,



			]
	]) : '<div style="height: 370px;"></div>'
	?>
</div>
<div class="arrow-bottom"></div>
<div class="main-content-items">
	<div class="goods-content">
		<div class="goods-content-main">
			<div class="main-slider goods-items">
				<?=$this->render('index/main-slider', [
					'items' => BannerHelper::renderItems(BannersCategory::findOne(['alias' => '2x2'])->banners)
				])?>
			</div>
			<div class="goods-items">
				<div class="two-items content-items">
                    <?php
                    if(!empty(BannersCategory::findOne(['alias' => '1x1.1'])->banners)){
                        echo BannerHelper::renderItem(BannersCategory::findOne(['alias' => '1x1.1'])->banners[0]);
                    }else{
                        echo $this->render('index/banner');
                    }

                    if(!empty(BannersCategory::findOne(['alias' => '1x1.2'])->banners)){
                        echo BannerHelper::renderItem(BannersCategory::findOne(['alias' => '1x1.2'])->banners[0]);
                    }else{
                        echo $this->render('index/banner');
                    }
                    ?>
				</div>
				<div class="two-items content-items">
					<?php
					if(!empty(BannersCategory::findOne(['alias' => '1x1.3'])->banners)){
                        echo BannerHelper::renderItem(BannersCategory::findOne(['alias' => '1x1.3'], true)->banners[0]);
                    }else{
						echo $this->render('index/banner2');
					}

					if(!empty(BannersCategory::findOne(['alias' => '1x1.4'])->banners)){
                        echo BannerHelper::renderItem(BannersCategory::findOne(['alias' => '1x1.4'], true)->banners[0]);
                    }else{
						echo $this->render('index/banner3');
					}
					?>
				</div>
			</div>
			<?php
            if(!empty(BannersCategory::findOne(['alias' => '1x2'])->banners)){
                echo BannerHelper::renderItem(BannersCategory::findOne(['alias' => '1x2'])->banners[0]);
            }else{
                echo $this->render('index/banner4');
            }
			?>
		</div>
		<?=Html::tag('div',
			Html::tag('div', Html::tag('div', Html::tag('span', '', ['class' => 'icon-anim']), ['class' => 'main-icon
			icon-best']).
				Html::tag('span', \Yii::t('shop', 'Лучшее'), ['class' => 'icon-down']), ['class' => 'main-icons', 'data-attribute-tab' => 'best']).
			Html::tag('div', Html::tag('div', Html::tag('span', '', ['class' => 'icon-anim']), ['class' => 'main-icon icon-news']).
				Html::tag('span', \Yii::t('shop', 'Новинки'), ['class' => 'icon-down']), ['class' => 'main-icons', 'data-attribute-tab' => 'new']).
			Html::tag('div', Html::tag('div', Html::tag('span', '', ['class' => 'icon-anim']), ['class' => 'main-icon icon-sale']).
				Html::tag('span', \Yii::t('shop', 'Распродажа'), ['class' => 'icon-down']), ['class' => 'main-icons', 'data-attribute-tab' => 'sale']),

			[
				'class' => 'goods-content-icons'
			]);

		\yii\widgets\Pjax::begin([
			'id'	=>	'goods_tabs'
		]);

		echo $this->render('index/goods_row', [
			'dataProvider'	=>	$goodsDataProvider
		]);

		\yii\widgets\Pjax::end();

		?>
	</div>
</div>
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
				<?=$this->render('subscribeForm')?>
			</div>
			<div class="style banner-img"></div>
		</div>
		<div class="right-block style">
			<?=$this->render('reviewForm')?>
		</div>
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
<!--	<span class="about-main-content-open">Читать полностью</span>
--></div>