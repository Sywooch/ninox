<?php use kop\y2sp\ScrollPager; ?>
<div class="goods-content-all">
    <?=\yii\widgets\ListView::widget([
        'dataProvider'	=>	$dataProvider,
        'itemView'	=>	function($model){
            return $this->render('good_card', ['good' => $model]);
        },
        'options'   =>  [
            'class' =>  'list-view listView-selector-'.\Yii::$app->request->get("type")
        ],
        'itemOptions'	=>	[
            'class'	=>	'goods-item'
        ],
        'pager' =>  [
            'class'             =>  ScrollPager::className(),
            'container'         =>  '.listView-selector-'.\Yii::$app->request->get("type"),
            'item'              =>  'div.goods-item',
            'enabledExtensions' =>  [
                ScrollPager::EXTENSION_TRIGGER,
                ScrollPager::EXTENSION_SPINNER,
                //ScrollPager::EXTENSION_NONE_LEFT,
                ScrollPager::EXTENSION_PAGING,
            ],
            'delay'    =>  0
        ],
        'summary'	=>	false
    ])?>
    <!--<div class="goods-item goods-item-style">
        <span><?=\Yii::t('shop', 'СМОТРЕТЬ ВСЕ ТОВАРЫ')?></span>
    </div>-->
</div>