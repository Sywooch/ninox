<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 10.05.16
 * Time: 17:05
 */
use yii\bootstrap\Html;

$js = <<<JS

$(function(){
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

    var lastId,
    topMenu = $(".list-"),
    topMenuHeight = topMenu.outerHeight()+15,
    // All list items
    menuItems = topMenu.find("a"),
    // Anchors corresponding to menu items
    scrollItems = menuItems.map(function(){
      var item = $($(this).attr("href"));
      if (item.length) { return item; }
    });


// Bind to scroll
$(window).scroll(function(){
   // Get container scroll position
   var fromTop = $(this).scrollTop()+topMenuHeight;

   // Get id of current scroll item
   var cur = scrollItems.map(function(){
     if ($(this).offset().top < fromTop)
       return this;
   });
   // Get the id of the current element
   cur = cur[cur.length-1];
   var id = cur && cur.length ? cur[0].id : "";

   if (lastId !== id) {
       lastId = id;
       // Set/remove active class
       menuItems
         .parent().removeClass("active")
         .end().filter("[href='#"+id+"']").parent().addClass("active");
   }
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
