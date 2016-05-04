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
<div class="about-contacts padding-bottom">
            <span class="about-header semi-bold">
                <a name="about-contacts-header">Контакты</a>
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
                по Украине со
                стационарных бесплатно
                        <span>
                        044 232 82 20
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
                            063 334 49 15
                        </span>
            </div>
        </div>
    </div>
    <div class="map">
        <span class="content-data-first_1">Как к нам добраться</span>
        <script type="text/javascript" charset="utf-8" src="https://api-maps.yandex.ru/services/constructor/1
.0/js/?sid=4hFYumeZNU3DOUuSwFOHsj9YvHKV9fH0&width=880&height=600&scroll=true&lang=ru_UA&sourceType=constructor
"></script>
       <!-- --><?/*=Accordion::widget([
            'items' => [
                [
                    'header' => Html::tag('span', 'Как к нам добраться', ['class' => 'content-data-first_1']),
                    'content' =>  '<script type="text/javascript" charset="utf-8" src="https://api-maps.yandex.ru/services/constructor/1
.0/js/?sid=4hFYumeZNU3DOUuSwFOHsj9YvHKV9fH0&width=880&height=600&scroll=true&lang=ru_UA&sourceType=constructor
"></script>',
                ],
            ],
            'clientOptions' => ['collapsible' => true, 'active' => false, 'heightStyle' => 'content'],
        ]);*/?>
    </div>
    </div>
</div>