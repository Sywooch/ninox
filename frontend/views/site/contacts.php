<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 04.05.16
 * Time: 15:41
 */
use yii\bootstrap\Html;
use yii\jui\Accordion;

$this->title = 'Контакты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content">
    <!-- <div class="left-side contact">
        <div class="left-side-menu">
        <?=Html::tag('div',
            \frontend\widgets\ListGroupMenu::widget([
                'items'    => [
                    [
                        'label' =>  \Yii::t('shop', 'О нас'),
                        'href'  =>  'o-nas#about-work-header'
                    ],
                    [
                        'label' =>  \Yii::t('shop', 'Сотрудничество'),
                        'href'  =>  'o-nas#about-delivery-payment-header'
                    ],
                    [
                        'label' =>  \Yii::t('shop', 'Наши контакты'),
                        'href'  =>  'kontakty'
                    ],
                    [
                        'label' =>  \Yii::t('shop', 'Каталог продукции'),
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
    </div> -->
<div class="about-contacts padding-bottom">
            <span class="about-header semi-bold">
                <a name="about-contacts-header"><h1>Контакты</h1></a>
            </span>
    <div class="contacts-inform">
        <div class="inform">
                    <span>
                        Украина, г. Киев
                        ул.Магнитогорская, 1Б
                    </span>
                    <span>
                        Электронная почта:
                        info@ninox.com.ua
                    </span>
        </div>
        <div class="inform numbers">
            <div>
                        <span class="home-number">
                        044 466 60 44
                        </span>
            </div>
        </div>
        <!--<div class="inform">
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
        </div>-->
    </div>
    <div class="map">
        <span class="content-data-first_1">Как к нам добраться</span>
        <!--<script type="text/javascript" charset="utf-8" src="https://api-maps.yandex.ru/services/constructor/1
.0/js/?sid=4hFYumeZNU3DOUuSwFOHsj9YvHKV9fH0&width=880&height=600&scroll=true&lang=ru_UA&sourceType=constructor
"></script>-->
	    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2540.326731581133!2d30.63849315179235!3d50.45364017937499!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40d4daabfa619dbd%3A0x35574247f3ebaeca!2z0LLRg9C70LjRhtGPINCc0LDQs9C90ZbRgtC-0LPQvtGA0YHRjNC60LAsIDHQkSwg0JrQuNGX0LI!5e0!3m2!1sru!2sua!4v1473757340293" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
    </div>
    </div>
</div>