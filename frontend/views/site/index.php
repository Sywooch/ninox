<?php
use evgeniyrru\yii2slick\Slick;
use yii\helpers\Html;

$centralBannersHtml = [];
foreach($centralBanners as $banner){
	$centralBannersHtml[] = $this->render('_banner_item', [
		'model' =>  $banner
	]);
}

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
			<li id="minorder"><div><span><?=\Yii::t('shop', 'Минимальный заказ')?> <?=(100 * \Yii::$app->params['exchange']).' '.\Yii::$app->params['currencyShortName']?></span></div></li>
			<li id="ret"><div><span><?=\Yii::t('shop', 'Беcпроблемный возврат')?></span></div></li>
			<li id="disc"><div><span><?=\Yii::t('shop', 'Скидки для оптовиков')?></span></div></li>
		</ul>
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