<?php
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 10.05.16
 * Time: 17:05
 */
use yii\bootstrap\Html;

$js = <<<JS

var lastId,
    topMenu = $(".menu-link"),
    topMenuHeight = topMenu.outerHeight()-50,
    // All list items
    menuItems = topMenu.find("a"),
    // Anchors corresponding to menu items
    scrollItems = menuItems.map(function(){
      var item = $($(this).attr("href"));
      if (item.length) { return item; }
    });

// Bind click handler to menu items
// so we can get a fancy scroll animation
menuItems.click(function(e){
  var href = $(this).attr("href"),
      offsetTop = href === "#" ? 0 : $(href).offset().top-topMenuHeight+1;
  $('html, body').stop().animate({
      scrollTop: offsetTop
  }, 300);
  e.preventDefault();
});

$(document).ready(function(){
    $(window).scroll(function() {
        var top = $(document).scrollTop();
        if (top < 100) $(".left-side").css({top: '0', position: 'relative'});
        else $(".left-side").css({top: '130px', position: 'fixed'});
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
         .removeClass("active")
         .filter("[href='#"+id+"']").addClass("active");
   }
    });
});
/*
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

// Cache selectors


/*$(function(){
    $(window).scroll(function() {
        var top = $(document).scrollTop();
        if (top < 100) $(".left-side").css({top: '0', position: 'relative'});
        else $(".left-side").css({top: '130px', position: 'fixed'});
    });
});*/

JS;
$this->registerJs($js);
?>
<div class="left-side-menu">
    <?=Html::tag('div',
        \frontend\widgets\ListGroupMenu::widget([
            'items'    => [
                [
                    'label' =>  \Yii::t('shop', 'Как мы работаем'),
                    'href'  =>  '#about-work-header'
                ],
                [
                    'label' =>  \Yii::t('shop', 'Доставка и оплата'),
                    'href'  =>  '#about-delivery-payment-header'
                ],
                [
                    'label' =>  \Yii::t('shop', 'Гарантии и возврат'),
                    'href'  =>  '#about-return-header'
                ],
                [
                    'label' =>  \Yii::t('shop', 'Условия исп. сайта'),
                    'href'  =>  '#about-TermOfUse-header'
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
