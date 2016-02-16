<?php use yii\bootstrap\Modal;?>
<div class="product-characteristics">
    <span class="tabTitle semi-bold">
        <?=_("Характеристика товара")?>
    </span>
    <div class="details">
        <div class="characteristics">
            <div class="characteristic">
                <span>
                    <?=!empty($good->num_opt) ? _("Количество в упаковке:") : ''?>
                </span>
            </div>
            <div>
                <span>
                    <?=$good->num_opt.' '.$good->Measure1?>
                </span>
            </div>
        </div>
        <div class="characteristics">
            <div class="characteristic">
                <?=_("Материал:")?>
            </div>
            <div>
                Ткань тканьевая
            </div>
        </div>
        <div class="characteristics">
            <div class="characteristic">
                <?=!empty($good->gabarity) ? _("Размеры:") : ''?>
            </div>
            <div>
                <?=$good->gabarity?>
            </div>
        </div>
    </div>
    <!--<div class="properties" itemprop="description"><?/*=$good->Description*/?></div>-->
</div>
<div class="customer-reviews">
    <div>
        <span class="semi-bold">
           <?=_("Отзывы покупателей")?>
        </span>
        <div class="write-review">
            <?php
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
            ?>
        </div>
        <a class="review-number">18</a>
    </div>
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
</div>