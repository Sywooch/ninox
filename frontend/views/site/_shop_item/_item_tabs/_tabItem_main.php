<?php
use yii\bootstrap\Html;
use yii\widgets\ListView;


$js = <<<'JS'
    $(document).ready(function(){
        $(".textarea-review").click(function(){
            $(".add-review-info").toggleClass("main");
        });
        $( ".textarea-review" ).trigger( "click" );
    });

JS;

$this->registerJs($js);

?>
<div class="product-characteristics">
    <span class="tabTitle semi-bold">
        <?=\Yii::t('shop', 'Характеристики товара')?>
    </span>
    <div class="details">
        <?php foreach($good->options as $option => $value){
            echo Html::tag('div', Html::tag('div', $option, ['class' => 'characteristic']).Html::tag('div', $value),[
                'class' =>  'characteristics'
            ]);
        }?>
    </div>
</div>
<div class="customer-reviews">
<!--    <?/*
    echo ListView::widget([
        'dataProvider'  =>  new \yii\data\ArrayDataProvider(['models' => $good->reviews]),
        'summary'   =>  Html::tag('div',
            Html::tag('span', \Yii::t('shop', 'Отзывы покупателей')).
            Html::a(sizeof($good->reviews), '#sendReview'),
            ['class' => 'customer-reviews-title']),
        'itemView'  =>  '_comment'
    ]);
    */?>
    <div class="add-review">
        <textarea class="textarea-review" placeholder="введите ваш отзыв" type="text"></textarea>
        <div class="add-review-info">
            <span class="review">Оставить отзыв</span>
            <span>Имя и Фамилия</span>
            <input id="input" type="text" value="" name="" placeholder="Имя и Фамилия">
            <span>Ваш Email</span>
            <input type="text" value="" name="" placeholder="Ваш Email">
            <?/*
            echo \yii\helpers\Html::button('Отправить', [
                'type'  =>  'submit',
                'class' =>  'button yellow-button large-button',
                'id'    =>  'submit'
            ]);
            */?>
        </div>
    </div>-->
</div>