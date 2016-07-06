<?php
use frontend\assets\RuLangAsset;
use yii\helpers\Html;

$this->registerMetaTag(['charset' => Yii::$app->charset]);
$this->registerMetaTag(['name' => 'description', 'content' => '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => '']);
$this->registerMetaTag(['name' => 'MobileOptimized', 'content' => '1240']);
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width; initial-scale=1.0']);
$this->registerMetaTag(['name' => 'HandheldFriendly', 'content' => 'false']);

$this->registerLinkTag(['rel' => 'shortcut icon', 'type' => 'image/x-icon', 'href' => '/favicon.ico']);

\frontend\assets\AppAsset::register($this);
\yii\bootstrap\BootstrapAsset::register($this);

$js = <<<JS
	if(hasTouch){
		$('body').on('touchmove', function(e){
			e.target.isTouchMoved = true;
		});
	}

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

	$('input[data-mask="phone"]').mask("+38(999)999-99-99");

JS;

\frontend\assets\PerfectScrollbarAsset::register($this);


// Register tooltip/popover initialization javascript
$this->registerJs($js);

$cartModal = new \bobroid\remodal\Remodal([
    'cancelButton'		=>	false,
    'confirmButton'		=>	false,
    'closeButton'		=>	false,
    'content'			=>	$this->render('../site/cart', ['order' => true]),
    'options'           =>  [
        'id'    =>  'modal-cart',
        'class' =>  \Yii::$app->cart->itemsCount ? (\Yii::$app->cart->wholesale ? 'wholesale' : 'retail') : 'empty',
    ],
    'id'	=>	'modalCart',
    'addRandomToID'		=>	false,
    'events'			=>	[
        'opening'	=>	new \yii\web\JsExpression("getCart(e)")
    ]
]);


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
    <?php $this->beginBody();  ?>
    <?=$content?>
    <?php
    RuLangAsset::register($this);
    ?>
    <?=$cartModal->renderModal()?>
    <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>