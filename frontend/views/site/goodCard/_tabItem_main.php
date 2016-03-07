<?php
use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use bobroid\remodal\Remodal;

$reviewModal = new Remodal([
    'cancelButton'		=>	false,
    'confirmButton'		=>	false,
    'closeButton'		=>	true,
    'addRandomToID'		=>	false,
    'content'			=>	$this->render('_write_review'),
]);

?>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $(".textarea-review").click(function(){
                $(".add-review-info").toggleClass("main");
            });
            $( ".textarea-review" ).trigger( "click" );
        });

    </script>
</head>
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
    <!--<div class="properties" itemprop="description"><?/*=$good->Description*/?></div>-->
</div>
<div class="customer-reviews">
    <div class="customer-reviews-title">
        <span class="semi-bold">
           <?=_("Отзывы покупателей")?>
        </span>
       <!-- <div class="write-review">
            <?/*=\bobroid\remodal\Remodal::widget([
                                                    'confirmButton'	=>	false,
                                                    'id'			=>	'review',
                                                    'cancelButton'	=>	false,
                                                    'addRandomToID'	=>	false,
                                                    'content'		=>	$this->render('_write_review'),
                                                    'buttonOptions'	=>	[
                                                        'label'		=>	\Yii::t('shop', 'Напишите отзыв'),
                                                        'class'     =>  'btn btn-lg btn-block btn-info'
                                                    ],
                                                ])*/?>
            <?/*php
            Modal::begin([
                             'header' => $this->render('_write_review', [
                                 'good'  =>  $good
                             ]),
                             'toggleButton' => [
                                 'tag' => 'button',
                                 'class' => 'btn btn-lg btn-block btn-info',
                                 'label' => 'Напишите отзыв',
                             ]
                         ]);
            echo \yii\helpers\Html::button('Отправить', [
                'type'  =>  'submit',
                'class' =>  'yellow-button large-button ',
                'id'    =>  'submit'
            ]);
            Modal::end();
          */  ?>
        </div>-->
        <a class="review-number">18</a>
    </div>
    <div class="all-reviews">+16 отзывов</div>
    <div class="customer-review">
        <div class="reviewer-name semi-bold">Валентина Блондинка</div>
        <div class="review-data">21 декабря 2015 г.</div>
        <span>Познакомьтесь с Axure RP Pro - программой для создания прототипов ваших веб-сайтов,
            их отладки и последующей публикации. Все действия в программе наглядны и удобны, т.к.
            проектирование не требует от вас знаний по веб-программированию, вам достаточно перетаскивать
            и компоновать элементы мышкой, назначая на них различные действия и редактируя атрибуты.
        </span>
        <div class="review-answer">Ответить</div>
    </div>
    <div class="customer-review">
        <div class="reviewer-name semi-bold">Валентина Блондинка</div>
        <div class="review-data">21 декабря 2015 г.</div>
        <span>Познакомьтесь с Axure RP Pro - программой для создания прототипов ваших веб-сайтов,
            их отладки и последующей публикации. Все действия в программе наглядны и удобны, т.к.
            проектирование не требует от вас знаний по веб-программированию, вам достаточно перетаскивать
            и компоновать элементы мышкой, назначая на них различные действия и редактируя атрибуты.
        </span>
        <div class="review-answer">1 ответ</div>
    </div>
    <div class="add-review">
        <textarea class="textarea-review" placeholder="введите ваш отзыв" type="text"></textarea>
        <div class="add-review-info">
            <span class="review">Оставить отзыв</span>
            <span>Имя и Фамилия</span>
            <input id="input" type="text" value="" name="" placeholder="Имя и Фамилия">
            <span>Ваш Email</span>
            <input type="text" value="" name="" placeholder="Ваш Email">
            <?
            echo \yii\helpers\Html::button('Отправить', [
                'type'  =>  'submit',
                'class' =>  'yellow-button large-button ',
                'id'    =>  'submit'
            ]);
            ?>
        </div>
    </div>
</div>