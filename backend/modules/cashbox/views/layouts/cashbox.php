<?php
use yii\helpers\Html;

$js = <<<'SCRIPT'
    $("#changeCashboxType").on('click', function(e){
        $.ajax({
            url:    '/cashbox/changecashboxtype',
            type:   'post',
            success: function(data){
                if(data == 1){
                    e.currentTarget.innerHTML = 'Опт';
                    e.currentTarget.setAttribute('class', 'btn btn-lg btn-success');
                }else{
                    e.currentTarget.innerHTML = 'Розница';
                    e.currentTarget.setAttribute('class', 'btn btn-lg btn-danger');
                }
            }
        });
    });
SCRIPT;

$this->beginPage();
\backend\assets\CashboxAsset::register($this);
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width; initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="https://<?=$_SERVER['SERVER_NAME']?>/favicon.ico">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?php
$this->registerJs($js);
?>
<div class="wrap">
    <?=$content?>
</div>
<footer class="footer">
    <div class="container">
    </div>
</footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
