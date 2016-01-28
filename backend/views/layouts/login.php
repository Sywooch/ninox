<?php
use yii\helpers\Html;
use app\assets\AppAsset;

$js = <<<'JS'
var guestRefresh = function(){
    $.ajax({
        type: 'POST',
        url: '/login',
        success: function(data){
            if(data == 0){
                location.reload();
            }
        }
    });
};

setInterval(guestRefresh, 10000);
JS;

$this->registerJs($js);

$this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="https://<?=$_SERVER['SERVER_NAME']?>/favicon.ico">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<div class="wrap">
    <div class="container">
        <?= $content ?>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>