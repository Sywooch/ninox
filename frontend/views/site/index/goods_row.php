<div class="goods-content-all">
    <?=\yii\widgets\ListView::widget([
        'dataProvider'	=>	$dataProvider,
        'itemView'	=>	function($model){
            return $this->render('good_card', ['good' => $model]);
        },
        'itemOptions'	=>	[
            'class'	=>	'goods-item'
        ],
        'summary'	=>	false
    ])?>
    <!--<div class="goods-item goods-item-style">
        <span><?=\Yii::t('shop', 'СМОТРЕТЬ ВСЕ ТОВАРЫ')?></span>
    </div>-->
</div>