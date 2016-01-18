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

$js = <<< 'SCRIPT'
/* To initialize BS3 tooltips set this below */
$(function () {
    $("[data-toggle='tooltip']").tooltip();
});;
/* To initialize BS3 popovers set this below */
$(function () {
    $("[data-toggle='popover']").popover();
});
SCRIPT;
// Register tooltip/popover initialization javascript
$this->registerJs($js);


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