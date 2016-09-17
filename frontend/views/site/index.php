<?php
use common\helpers\Formatter;
use evgeniyrru\yii2slick\Slick;
use frontend\helpers\BannerHelper;
use frontend\models\BannersCategory;
use yii\helpers\Html;
$mainGalleryHtml = [];

$this->title = \Yii::t('shop', 'Дистанционные держатели, подвесные системы -"Ninox.com.ua"');
$this->registerMetaTag(
	[
		'name' => 'description',
		'content' => \Yii::t('shop',
			'Дистанционные держатели, подвесные системы - "Ninox.com.ua", официальный диллер "Citinox.de"',
			[
				'minOrder'  =>  Formatter::getFormattedPrice(\Yii::$app->params['domainInfo']['minimalOrderSum'])
			])
	],
	'description'
);
$this->registerMetaTag(
	[
		'name' => 'keywords',
		'content' => \Yii::t('shop', 'Дистанционные держатели, подвесные системы - "Ninox.com.ua", официальный диллер "Citinox.de"')
	],
	'keywords'
);


$sliderBanners = \frontend\helpers\SliderHelper::renderItems($centralBanners);

$reviewModal = new \bobroid\remodal\Remodal([
	'cancelButton'		=>	false,
	'confirmButton'		=>	false,
	'closeButton'		=>	true,
	'addRandomToID'		=>	false,
	'content'			=>	$this->render('_review_modal'),
	'id'				=>	'reviewModal',
	'options'			=>  [
		'class'			=>  'review-modal'
	]

]);

$quickViewModal = new \bobroid\remodal\Remodal([
	'cancelButton'		=>	false,
	'confirmButton'		=>	false,
	'closeButton'		=>	true,
	'addRandomToID'		=>	false,
	'id'				=>	'quickView',
	'content'           =>  Html::tag('div', '', ['class' => 'item-navigation icon-circle-left'])
		.Html::tag('div', '', ['class' => 'item item-main clear-fix'])
		.Html::tag('div', '', ['class' => 'item-navigation icon-circle-right']),
	'options'			=>  [
		'id'        =>  'modal-quick-view',
		'class'     =>  'quick-view-modal',
		'hashTracking'  =>  false
	],
]);

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

	    if(form.find("#subscribeform-email").val().length != 0){
			$.ajax({
				type: 'POST',
				url: '/subscribe',
				data: form.serialize(),
				success: function(){
					form.html("спасибо за подписку!");
				}
			});
	    }
	});

    $('body').on(hasTouch ? 'touchend' : 'click', '.ias-trigger', function(e){
        if(hasTouch && isTouchMoved(e)){ return false; }
        e.preventDefault();
        $('.grid-view').infinitescroll('start').scroll();
    });

    $('body').on('.items-grid infinitescroll:afterRetrieve', function(){
        $('.grid-view').infinitescroll('stop');
    });

JS;

$this->registerJs($js);
?>
<!--<div class="main-content-slider">
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
				'autoplaySpeed'	=> 6000,
			]
	]) : '<div style="height: 370px;"></div>'
	?>
</div>-->
<!--<div class="arrow-bottom"></div>-->
<div class="main-content-items">
	<div class="goods-content">
		<div class="goods-content-main">
			<div id="features" class="row">
				<div class="col-md-6">
					<img class="img-responsive" src="/img/bakery.png" alt="">
				</div>
				<div class="col-md-6">
					<h2 class="page-header"><i class="fi fi-fw fi-type-1"></i>
						Крепление к стене</h2>
					<p>Мы предлагаем широкий ассортимент дистанционных держателей производства компании FORWERK (Германия)</p>
					<p>Большое разнообразие и оригинальный эргономичный дизайн креплений позволяет использовать их  при конструировании наружной рекламы различного типа и сложности. Для защиты от злоумышленников в большинстве креплений предусмотрены фиксаторы на резьбе, при помощи которых подвижная часть надежно фиксируется, и снять ее можно только с помощью специального инструмента.  Крепления идеально подойдут для удержания фасадных табличек, вывесок, фоторамок и т.д, причем как внутри, так и снаружи помещения.</p>
					<p>Все изделия изготавливаются из высококачественной нержавеющей стали, что гарантирует их высокую надежность и долговечность</p>
					<br>
					<a href="/o-nas"><span class="full-text">Читать полностью</span></a>
				</div>
			</div>
			<div class="row">
				<div class="well">
					<div class="row">
						<div class="col-md-4 cta-1">
							<span class="cta-text">Звоните или оставляйте заявки</span>
						</div>
						<div class="col-md-4 cta-2">
							<span class="cta-text">(044) 466-60-40</span>
						</div>
						<div class="col-md-4 cta-3">
							<span class="cta-text"><a class="btn btn-lg btn-default btn-block consult" href="#">Заказать консультацию</a></span>
						</div>
					</div>
				</div>
			</div>
	</div>
</div>

<?=
$reviewModal->renderModal().
$quickViewModal->renderModal();
?>