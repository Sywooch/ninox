<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 10.05.16
 * Time: 17:05
 */
use yii\bootstrap\Html;

$js = <<<JS

/*$(document).ready(function(){
    $(window).scroll(function() {
        var top = $(document).scrollTop();
        if (top < 100) $(".left-side").css({top: '0', position: 'relative'});
        else $(".left-side").css({top: '130px', position: 'fixed'});
    });
});

function scrollToAnchor(aid){
    $('html,body').animate({scrollTop: ($("a[name='"+ aid +"']").offset().top - 120)},2000);
}

$("body").on('click', '.left-menu-links .menu-link .list-group-item', function(e){
    e.preventDefault();
    scrollToAnchor($(this).prop('href').replace(/(.*)\#/, ''));
});

$(document).ready(function(){
    scrollToAnchor('about-work-header');
});*/

var menu_selector = ".list-group"; // Переменная должна содержать название класса или идентификатора, обертки нашего меню.

function onScroll(){
    var scroll_top = $(document).scrollTop();
    $(menu_selector + " a").each(function(){
        var hash = $(this).attr("href");
        var target = $(hash);
        if (target.position().top <= scroll_top && target.position().top + target.outerHeight() > scroll_top) {
            $(menu_selector + " a.active").removeClass("active");
            $(this).addClass("active");
        } else {
            $(this).removeClass("active");
        }
    });
}

$(document).ready(function () {

    $(document).on("scroll", onScroll);

    $("a[href^=#]").click(function(e){
        e.preventDefault();

        $(document).off("scroll");
        $(menu_selector + " a.active").removeClass("active");
        $(this).addClass("active");
        var hash = $(this).prop("href").replace(/(.*)\#/, '');
        var target = $(hash);

        $("html, body").animate({
            scrollTop: target.offset().top
        }, 500, function(){
            window.location.hash = hash;
            $(document).on("scroll", onScroll);
        });

    });

});
JS;
$this->registerJs($js);
?>
<div class="left-side-menu">
    <?=Html::tag('div',
        \frontend\widgets\ListGroupMenu::widget([
            'items'    => [
                [
                    'label' =>  \Yii::t('shop', 'Как мы работаем',[ 'class' =>  'menu']),
                    'href'  =>  '/o-nas#about-work-header'
                ],
                [
                    'label' =>  \Yii::t('shop', 'Доставка и оплата'),
                    'href'  =>  '/o-nas#about-delivery-payment-header'
                ],
                [
                    'label' =>  \Yii::t('shop', 'Гарантии и возврат'),
                    'href'  =>  '/o-nas#about-return-header'
                ],
                [
                    'label' =>  \Yii::t('shop', 'Условия исп. сайта'),
                    'href'  =>  '/o-nas#about-TermOfUse-header'
                ],
            ]
        ]),
        [
            'class' =>  'menu menu-link',
        ]).
    Html::tag('div',
        \frontend\widgets\ListGroupMenu::widget([
            'items'    => [
                [
                    'label' =>  \Yii::t('shop', 'Контакты'),
                    'href'  =>  '/kontakty',
                ],
                [
                    'label' =>  \Yii::t('shop', 'Вопросы и ответы'),
                    'href'  =>  '/pomoshch'
                ],
            ]
        ]),
        [
            'class' =>  'menu'
        ])?>
</div>
