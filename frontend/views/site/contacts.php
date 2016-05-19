<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 04.05.16
 * Time: 15:41
 */
use yii\bootstrap\Html;
use yii\jui\Accordion;

?>
<div class="content">
    <div class="left-side contact">
        <?=Html::tag('div',
            \frontend\widgets\ListGroupMenu::widget([
                'items'    => [
                    [
                        'label' =>  \Yii::t('shop', 'Как мы работаем'),
                        'href'  =>  'o-nas#about-work-header'
                    ],
                    [
                        'label' =>  \Yii::t('shop', 'Доставка и оплата'),
                        'href'  =>  'o-nas#about-delivery-payment-header'
                    ],
                    [
                        'label' =>  \Yii::t('shop', 'Гарантии и возврат'),
                        'href'  =>  'o-nas#about-return-header'
                    ],
                    [
                        'label' =>  \Yii::t('shop', 'Условия исп. сайта'),
                        'href'  =>  'o-nas#about-TermOfUse-header'
                    ],
                ]
            ]),
            [
                'class' =>  'menu',
            ]),
        $this->render('_left_menu')
        ?>
    </div>
<div class="about-contacts padding-bottom">
            <span class="about-header semi-bold">
                <a name="about-contacts-header"><h1>Контакты</h1></a>
            </span>
    <div class="contacts-inform">
        <div class="inform">
                    <span>
                        02217, Украина, г. Киев
                        ул.Электротехническая, 2
                    </span>
                    <span>
                        Электронная почта:
                        info@krasota-style.ua
                    </span>
        </div>
        <div class="inform numbers">
            <div>
                        <span>
                            0 800 508 208
                        </span>
                Бесплатно с мобильных и стационарных телефонов по Украине
                        <span class="home-number">
                        044 578 20 16
                        </span>
            </div>
            <!--<span>
                044 232 82 20
            </span>-->
        </div>
        <div class="inform">
            <div>
                        <span class="operator">
                            Vodafone
                        </span>
                        <span class="right">
                            050 677 54 56
                        </span>
            </div>
            <div>
                        <span class="operator">
                            Киевстар
                        </span>
                        <span class="right">
                            067 507 87 73
                        </span>
            </div>
            <div>
                        <span class="operator">
                            Lifecell
                        </span>
                        <span class="right">
                            063 578 20 16
                        </span>
            </div>
        </div>
    </div>
    <div class="map">
        <span class="content-data-first_1">Как к нам добраться</span>
        <script type="text/javascript" charset="utf-8" src="https://api-maps.yandex.ru/services/constructor/1
.0/js/?sid=4hFYumeZNU3DOUuSwFOHsj9YvHKV9fH0&width=880&height=600&scroll=true&lang=ru_UA&sourceType=constructor
"></script>
    </div>
    </div>
</div>