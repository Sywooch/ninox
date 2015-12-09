<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 12/9/2015
 * Time: 2:06 PM
 */

?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.spoiler-title').click(function(){
            $(this).parent().children('.spoiler-body').slideToggle(500);
            return false;
        });
    });
</script>
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



           <div class="order order-waiting">
                error
                <b class="spoiler-title">показать / скрыть</b>
                <div class="spoiler-body" style="display: none;">
                    Нельзя добавлять комментарии, которые:
                    <ul>
                        <li>Не относятся к тематике сайта и самой записи</li>
                        <li>Содержат в тексте исключительно заглавные буквы</li>
                        <li>Содержат нецензурные слова, идиоматические выражения, призывы к межнациональной и межконфессиональной розни</li>
                        <li>Содержат обсуждения наркотических веществ и способов их применения</li>
                        <li>Содержат не нормированное количество знаков препинания и смайликов</li>
                        <li>Содержат постоянные обращения к конкретным участникам по личным вопросам</li>
                        <li>Содержат ссылки на сторонние ресурсы</li>
                        <li>Содержат призывы к нарушению действующего законодательства (Уголовного и Административного кодекса)</li>
                        <li>Содержат реплики оскорбляющие других участников проекта</li>
                        <li>Содержат нецензурные слова, идиоматические выражения, призывы к межнациональной и межконфессиональной розни</li>
                        <li>Содержат обсуждения наркотических веществ и способов их применения</li>
                        <li>Содержат не нормированное количество знаков препинания и смайликов</li>
                        <li>Содержат постоянные обращения к конкретным участникам по личным вопросам</li>
                        <li>Содержат ссылки на сторонние ресурсы</li>
                        <li>Содержат призывы к нарушению действующего законодательства (Уголовного и Административного кодекса)</li>
                    </ul>
                </div>

           </div>







            <div class="order order-waiting">

                <div class="waiting spoiler-title">
                        <i class="icon icon-arrow"></i>
                        <div class="myriad">
                            27227
                        </div>
                        <div class="data semi">
                            01.09.2015
                        </div>
                        <div class="payment semi">
                            Ожидается оплата
                        </div>
                        <div class="money semi">
                            254 798 грн.
                        </div>
               </div>
                <div class="pr">
                    <div class="print semi">
                        <i class="icon icon-print"></i>
                    </div>
                    <div class="history semi">
                        <a>История</a>
                    </div>
                    <div class="reorder semi">
                        <a>Повторить заказ</a>
                    </div>
                </div>

                <div class="spoiler-body" style="display: none;">
                    <div class="">




                    </div>
                </div>

            </div>
            <div class="order order-complete">
                <i class="icon icon-arrow"></i>
                <div class="myriad">
                    26587
                </div>
                <div class="data semi">
                    15.08.2015
                </div>
                <div class="payment semi">
                    Выполнен
                </div>
                <div class="money semi">
                    1300 грн.
                </div>
                <div class="print semi">
                    <i class="icon icon-print"></i>
                </div>
                <div class="history semi">
                    <a>История</a>
                </div>
                <div class="reorder semi">
                    <a>Повторить заказ</a>
                </div>
            </div>
            <div class="order order-canceled">
                <i class="icon icon-arrow"></i>
                <div class="myriad">
                    26213
                </div>
                <div class="data semi">
                    03.08.2015
                </div>
                <div class="payment semi">
                    Отменен
                </div>
                <div class="money semi">
                    254 586 798 грн.
                </div>
                <div class="print semi">
                    <i class="icon icon-print"></i>
                </div>
                <div class="history semi">
                    <a>История</a>
                </div>
                <div class="reorder semi">
                    <a>Повторить заказ</a>
                </div>
            </div>

    </div>
</div>
</div>