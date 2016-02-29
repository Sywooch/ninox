<?php
$js = <<<'JS'
var sendForm = function(data){
    console.log(data);
}

$("#saveGoodBtn").on('click', function(){
    $(".tab-content .active .goodEditForm").submit();
});

$(".tab-content form").on('submit', function(e){
    console.log(e);
    //e.preventDefault();
});
JS;

$this->registerJs($js);

?>
<h1><?=$good->Name?> <small>редактирование</small></h1>
<div class="row">
    <div class="col-xs-4">
        <div class="well well-sm">
            <img src="http://krasota-style.com.ua/img/catalog/<?=$good->ico?>" alt="<?=$good->Name?>" class="col-xs-12">
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="col-xs-8">
        <?=\kartik\tabs\TabsX::widget([
            'bordered'  =>  true,
            'id'    =>  'goodEditTabs',
            'items' =>  [
                [
                    'label'     =>  'Основное',
                    'content'   =>  $this->render('_goodEdit/main', [
                        'model' =>  $goodMainForm
                    ])
                ],[
                    'label'     =>  'Аттрибуты',
                    'content'   =>  $this->render('_goodEdit/params', [
                        'model' =>  $goodMainForm
                    ])
                ],[
                    'label'     =>  'Изображения',
                    'content'   =>  $this->render('_goodEdit/images', [
                        'good'  =>  $good
                    ])
                ],[
                    'label'     =>  'Експорт',
                    'content'   =>  $this->render('_goodEdit/export', [
                        'model' =>  $goodExportForm
                    ])
                ],[
                    'label'     =>  'Склад',
                    'content'   =>  $this->render('_goodEdit/store', [
                        'model' =>  $goodMainForm
                    ])
                ],
            ]
        ]);?>
    </div>
    <div class="col-xs-12">
        <br>
        <br>
        <center>
            <?=\yii\helpers\Html::button('Сохранить', [
                'class' =>  'btn btn-success',
                'id'    =>  'saveGoodBtn'
            ]);?>
        </center>
        <br>
        <br>
    </div>
</div>