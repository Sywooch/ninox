<?php
use yii\bootstrap\Html;

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

if(!$good->isNewRecord){
    echo Html::tag('h1', $good->Name.' '.Html::tag('small', 'редактирование'), ['data-itemID' => $good->ID]);
}else{
    echo Html::tag('h1', 'Добавление товара '.(!empty($nowCategory) ? Html::tag('small', $nowCategory->Name) : ''));
}

?>
<div class="row">
    <div class="col-xs-4">
        <div class="well well-sm">
            <img src="<?=\Yii::$app->params['cdn-link']?>/img/catalog/<?=$good->photo?>" id="goodMainPhoto" alt="<?=$good->name?>" class="col-xs-12">
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="col-xs-8">
        <?=\kartik\tabs\TabsX::widget([
            'bordered'  =>  true,
            'id'    =>  'goodEditTabs',
            'enableStickyTabs'    =>  true,
            'items' =>  [
                [
                    'label'     =>  'Основное',
                    'content'   =>  $this->render('_goodEdit/main', [
                        'model'     =>  $goodMainForm,
                        'category'  =>  $good->category,
                        'good'      =>  $good
                    ])
                ],[
                    'label'     =>  'Аттрибуты',
                    'content'   =>  $this->render('_goodEdit/params', [
                        'options' =>  $good->options
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
                        'good'  =>  $good
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