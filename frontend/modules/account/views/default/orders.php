<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 12/9/2015
 * Time: 2:06 PM
 */
?>
<div class="content">
    <div class="menu">
        <?=\frontend\widgets\ListGroupMenu::widget([
            'items'    => [
                [
                    'label' =>  'Личные данные',
                    'href'  =>  '/account'
                ],
                [
                    'label' =>  'Мои заказы',
                    'href'  =>  '/account/orders'
                ],
                [
                    'label' =>  'Моя скидка',
                    'href'  =>  '/account/discount'
                ],
                [
                    'label' =>  'Список желаний',
                    'href'  =>  '/account/wish-list'
                ],
                [
                    'label' =>  'Мои отзывы',
                    'href'  =>  '/account/reviews'
                ],
                [
                    'label' =>  'Возвраты',
                    'href'  =>  '/account/123'
                ],
                [
                    'label' =>  'Ярмарка мастеров',
                    'href'  =>  '/account/mas'
                ],
            ]
        ])?>
    </div>
    <div class="user-data-content">
        <div class="user-account box myriad">
            <i class="icon icon-box"></i> Мои заказы
        </div>
        <div class="orders">
            <?php
            /*
            ListView::widget([
                'dataProvider'  =>  $ordersDataProvider,
                'view'          =>  function($model){
                    return $this->render('_order', [
                        'order'     =>  $model
                }
            ]);
            */
            ?>
            <?=$this->render('_order', [

                'windowClass'   =>  'order-waiting',
                'order' =>  [
                    'number'    =>  123,
                    'date'      =>  '01.02.03',
                    'status'    =>  'Ожидается оплата',
                    'summ'      =>  '100500',
                ]
            ])?>
            <?=$this->render('_order', [
                'windowClass'   =>  'order-canceled',
                'order' =>  [
                    'number'    =>  123,
                    'date'      =>  '01.02.03',
                    'status'    =>  'canceled',
                    'summ'      =>  '100500',
                ]
            ])?>
            <?=$this->render('_order', [
                'windowClass'   =>  'order-complete',
                'order' =>  [
                    'number'    =>  123,
                    'date'      =>  '01.02.03',
                    'status'    =>  'Complete',
                    'summ'      =>  '100500',
                ]
            ])?>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js">
</script>
<script type="text/javascript">
            $(".spoiler-title").click(function(){
                if ($(this).parent().hasClass("showw")) {
                    $(this).parent().toggleClass("showw").children(".spoiler-body").slideToggle("medium");
                }
                else {
                    $(this).parent().toggleClass("showw").children(".spoiler-body").slideToggle("medium");
                }
            });
</script>
