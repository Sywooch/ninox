<?php
use yii\helpers\Html;

\bobroid\sweetalert\SweetalertAsset::register($this);

$js = <<<'SCRIPT'
    $("#changeCashboxType").on('click', function(e){
        swal({
            title: "Пересчитываем заказ...",
            allowEscapeKey: false,
            showConfirmButton: false
        });
        $.ajax({
            url:    '/cashbox/changecashboxtype',
            type:   'post',
            success: function(data){
                if(data.priceType == 1){
                    e.currentTarget.innerHTML = 'Опт';
                    e.currentTarget.setAttribute('class', 'btn btn-lg btn-success');

                    if($('.header .summary').length > 0){
                        $('.header .summary').toggleClass('bg-danger');
                        $('.header .summary').addClass('bg-success');
                    }
                }else{
                    e.currentTarget.innerHTML = 'Розница';
                    e.currentTarget.setAttribute('class', 'btn btn-lg btn-danger');

                    if($('.header .summary').length > 0){
                        $('.header .summary').toggleClass('bg-success');
                        $('.header .summary').addClass('bg-danger');
                    }
                }

                if($('#cashboxGrid-pjax').length > 0){
                    $.pjax.reload({container: '#cashboxGrid-pjax'});

                    $('.toPay')[0].innerHTML = data.orderToPay;
                    $('.summ')[0].innerHTML = data.orderSum;
                }

                swal.close();
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
