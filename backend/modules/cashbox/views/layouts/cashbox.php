<?php
use yii\helpers\Html;

\bobroid\sweetalert\SweetalertAsset::register($this);

$js = <<<'JS'
    changeCashboxType = function(){
        swal({
            title: "Пересчитываем заказ...",
            allowEscapeKey: false,
            showConfirmButton: false
        });
        $.ajax({
            url:    '/cashbox/changecashboxtype',
            type:   'post',
            success: function(data){
                var summary = $('.header .summary'),
                    button = $('#changeCashboxType');

                if(data.priceType == 1){
                    button[0].innerHTML = 'Опт';
                    button.removeClass('btn-danger');
                    button.addClass('btn-success');

                    if(summary.length > 0){
                        summary.removeClass('bg-danger');
                        summary.addClass('bg-success');
                    }
                }else{
                    button[0].innerHTML = 'Розница';
                    button.removeClass('btn-success');
                    button.addClass('btn-danger');

                    if(summary.length > 0){
                        summary.removeClass('bg-success');
                        summary.addClass('bg-danger');
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
    };

    var guestRefresh = function(){
        $.ajax({
            type: 'POST',
            url: '/login',
            success: function(data){
                if(data == 1){
                    location.reload();
                }
            }
        });
    };

setInterval(guestRefresh, 10000);

    $("#changeCashboxType").on('click', function(){
        changeCashboxType();
    });
JS;

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
