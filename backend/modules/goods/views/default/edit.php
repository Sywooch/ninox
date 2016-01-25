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
            'items' =>  [
                [
                    'label'     =>  'Основное',
                    'content'   =>  $this->render('_goodEdit/main', [
                        'model' =>  $goodMainForm
                    ])
                ],[
                    'label'     =>  'Параметры',
                    'content'   =>  $this->render('_goodEdit/params', [
                        'model' =>  $goodMainForm
                    ])
                ],[
                    'label'     =>  'Изображения',
                    'content'   =>  $this->render('_goodEdit/images', [
                        'model' =>  $goodMainForm
                    ])
                ],[
                    'label'     =>  'SEO',
                    'content'   =>  $this->render('_goodEdit/seo', [
                        'model' =>  $goodMainForm
                    ])
                ],[
                    'label'     =>  'Склад',
                    'content'   =>  $this->render('_goodEdit/store', [
                        'model' =>  $goodMainForm
                    ])
                ],
            ]
        ]);
        ?>
    </div>
    <div class="col-xs-12">
        <br>
        <br>
        <center>
            <?=\yii\helpers\Html::button('Сохранить и закончить', [
                'class' =>  'btn btn-success',
            ]), ' или ', \yii\helpers\Html::button('Сохранить и продолжить', [
                'class' =>  'btn btn-primary',
            ]);?>
        </center>
    </div>
</div>