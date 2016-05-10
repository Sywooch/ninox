<?php
use bobroid\remodal\Remodal;
use frontend\models\ReturnForm;
use yii\bootstrap\Html;
use yii\jui\Accordion;

/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 08.02.16
 * Time: 14:07
 */

$model = new \frontend\models\UsersInterestsForm();
$js = <<<'JS'
    function scrollToAnchor(aid){
       $('html,body').animate({scrollTop: ($("a[name='"+ aid +"']").offset().top - 100)},2000);
    }

    $("body").on('click', '#link', function(e){
        e.preventDefault();
        scrollToAnchor($(this).prop('href').replace(/(.*)\#/, ''));
    });
JS;
$this->registerJs($js);
?>
<!--<script type="text/javascript">
    function scrollToAnchor(aid){
        var aTag = $("a[name='"+ aid +"']");
        $('html,body').animate({scrollTop: aTag.offset().top},'slow');
    }

    $("#link").click(function() {
        scrollToAnchor('#');
    });

</script>-->
<div class="content" xmlns="http://www.w3.org/1999/html">
    <div class="left-side">
        <div class="left-side-menu">
            <div class="left-side-menu-item" >
                <a id="link" href="#about-work-header"><?=\Yii::t('shop', 'Как мы работаем')?></a>
            </div>
            <div class="left-side-menu-item" >
                <a id="link" href="#about-delivery-payment-header"><?=\Yii::t('shop', 'Доставка и оплата')?></a>
            </div>
            <div class="left-side-menu-item" >
                <a id="link" href="#about-return-header"><?=\Yii::t('shop', 'Гарантии и возврат')?></a>
            </div>
            <div class="left-side-menu-item" href="#about-TermOfUse-header">
                <a id="link" href="#about-TermOfUse-header"><?=\Yii::t('shop', 'Условия исп. сайта')?></a>
            </div>
            <div class="left-side-menu-item" >
                <a href="/kontakty" style="text-decoration: underline;"><?=\Yii::t('shop', 'Контакты')?></a>
            </div>
            <div class="left-side-menu-item" >
                <a href="/pomoshch" style="text-decoration: underline;"><?=\Yii::t('shop', 'Вопросы и ответы')?></a>
            </div>
 <!--           <div class="left-side-menu-item">
                <a id="link" href="#about-as-header"><?/*=\Yii::t('shop', 'О нас')*/?></a>
            </div>
            <div class="left-side-menu-item" href="">
                <a id="link" href=""><?/*=\Yii::t('shop', 'Сотрудничество с нами')*/?></a>
            </div>
            <div class="left-side-menu-item" href="">
                <a id="link" href=""><?/*=\Yii::t('shop', 'Вакансии')*/?></a>
            </div>-->
        </div>
    </div>
    <div class="about">
        <div class="about-as padding-bottom">
            <div class="about-as-header about-header semi-bold">
                <a name="about-work-header">Как мы работаем</a>
            </div>
            <div class="bold about-as-center">
                На сайте krasota-style.ua в некоторых разделах действует 2 типа цен
            </div>
            <div class="about-as-price">
                <div class="about-as-price-rules">
                    <div class="about-as-price-rules-img wholesale-prices"></div>
                    <div class="about-as-price-rules-text">
                        <span class="bold">ОПТОВЫЕ ЦЕНЫ</span>
                        При заказе на сумму от 1000 грн.
                    </div>
                </div>
                <div class="about-as-price-rules">
                    <div class="about-as-price-rules-img retail-prices"></div>
                    <div class="about-as-price-rules-text">
                        <span class="bold">РОЗНИЧНЫЕ ЦЕНЫ</span>
                        При заказе на сумму от 100 до 1000 грн.
                    </div>
                </div>
            </div>
            <div class="about-as-order">
                <div class="about-as-order-head bold">
                    Сколько времени обрабатываеться заказ?
                </div>
                <div class="about-as-order-infogramma">
                    <div class="about-as-order-infogramma-item">
                        <div class="about-as-order-infogramma-item-img decor"></div>
                        <div class="about-as-order-infogramma-item-text">
                            <span class="bold">ОФОРМЛЕНИЕ ЗАКАЗА</span>
                        </div>
                    </div>
                    <div class="arrow-right"></div>
                    <div class="about-as-order-infogramma-item">
                        <div class="about-as-order-infogramma-item-img assembly-of-goods"></div>
                        <div class="about-as-order-infogramma-item-text">
                            <span class="bold">СБОРКА ТОВАРОВ</span>
                            1-10 ЧАСОВ
                        </div>
                    </div>
                    <div class="arrow-right"></div>
                    <div class="about-as-order-infogramma-item">
                        <div class="about-as-order-infogramma-item-img order-shipping"></div>
                        <div class="about-as-order-infogramma-item-text">
                            <span class="bold">ОТПРАВКА ЗАКАЗА</span>
                            ЕЖЕДНЕВНО
                        </div>
                    </div>
                </div>
                <div class="about-as-order-text">
                    Наш интернет-магазин занимается оптовой продажей бижутерии, поэтому обращаем Ваше внимание на то,
                    что в некоторых позициях указана цена за упаковку, которая не распаковывается и не делится.
                    Также количество цветов в пачке может отличаться от образца, представленного на фото.
                    Детали предварительно уточняйте у консультанта. Благодарим за понимание!
                </div>
            </div>
        </div>
        <div class="about-delivery padding-bottom">
            <div class="about-delivery-header about-header semi-bold">
                <a name="about-delivery-payment-header">Доставка</a>
            </div>
            <div class="about-delivery-content">
                <div class="about-delivery-content-img"></div>
                <div class="about-delivery-content-info">
                    <div class="about-delivery-content-info-delivery">
                        <div class="about-delivery-content-info-delivery-time">
                            <span class="time">24 часа</span>
                            <span>ДОСТАВКА В ОБЛАСНЫЕ ЦЕНТРЫ</span>
                        </div>
                        <div class="about-delivery-content-info-delivery-time">
                            <span class="time">48 часов</span>
                            <span>ДОСТАВКА В РЕГИОНЫ</span>
                        </div>
                    </div>
                    <div class="about-delivery-content-info-text">
                            Доставка заказа по Украине осуществляется от 1 до 5 дней  транспортной организацией «Новая Почта».
                            Стоимость доставки определяется тарифами транспортной организации, не входит в сумму заказа,
                            и оплачивается заказчиком отдельно, при получении посылки.

                            Посылка хранится в отделении «Новая Почта» в течении 5 дней, по истечении этого времени,
                            она автоматически отправляется назад. Повторная отправка заказа оплачивается клиентом отдельно.
                    </div>
                </div>
            </div>
        </div>
        <div class="about-payment padding-bottom">
            <div class="about-payment-header about-header semi-bold">
                <a name="about-payment-header">Оплата</a>
            </div>
            <span class="bold">
                Оплата на карту «Приват Банка»
            </span>
            <span>
                После того, как заказ был собран и готов к отправке, Вам на телефон приходит смс с номером расчетного счета и суммой к оплате.
                Как правило, деньги поступают на счет сразу после отправки, но банком также предусмотрен срок на пересылку денег до 3-х рабочих дней.
            </span>
            <div class="about-payment-inform about-inform">
                <span class="semi-bold">
                    Для того, чтобы Ваш заказ был отправлен как можно скорее,
                    перезвоните нам или заполните форму после перевода денег.
                </span>
                <?php  echo Remodal::widget([
                    'cancelButton'		=>	false,
                    'confirmButton'		=>	false,
                    'addRandomToID'		=>	false,
                    'id'            =>  'payment-confirm-form',
                    'buttonOptions' =>  [
                        'label' =>  'Сообщить об оплате',
                        'class' =>  'about-inform-button yellow-button large-button'
                    ],
                    'content'   =>  $this->render('_payment_confirm'),

                ])?>
            </div>
            <!--<span>
                При оплате на карту взымается комиссия в размере 1% от суммы заказа (она уже входит в стоимость, указанную в смс).
                В отделении «Новой Почты», Вам нужно оплатить лишь стоимость пересылки.
            </span>-->
            <span class="bold">
                Оплата при получении
            </span>
            <span>
                Заказ отправляется сразу после сборки. Покупатель оплачивает его в момент получения посылки в отделении «Новая Почта».
            </span>
            <span>
                Стоимость перевода денег также оплачивается покупателем (составляет 2% от суммы заказа + 20 грн.).
            </span>
            <span>
                Доставка оплачивается покупателем отдельно при получении посылки в отделении «Новая Почта».
            </span>
        </div>
        <div class="about-return padding-bottom">
            <div class="about-return-header about-header semi-bold">
                <a name="about-return-header">Гарантия и возврат</a>
            </div>
            <span>
                Товары, приобретенные в интернет-магазине krasota-style.ua, можно вернуть в течении 14 дней с момента получения заказа.
            </span>
            <span>
                Вопросы о несоответствии товара заказу принимаются в течении 3-х дней с момента получения посылки.
                По истечении 3-х дней претензии принимаются только при обнаружении фабричного брака.
            </span>
            <span>
                В случае брака или несоответствия товара заказанному, возврат происходит за счет интернет-магазина, в других случаях возврат оплачивает покупатель.
            </span>
            <span>
                Если возврат отправлен наложенным платежом, мы не сможем забрать посылку, и она будет возвращена отправителю.
            </span>
            <span class="bold">
                Варианты возврата денежных средств:
            </span>
            <span>
                <p> • на банковскую карту (возврат денежных средств осуществляется в течение 7 рабочих дней после
                    получения товара) </p>
                 <p>• на личный счет в нашем магазине для новых покупок </p>
            </span>
            <span class="bold">
                Товар не подлежит возврату, если:
            </span>
            <span>
                <p>• сумма возврата меньше 5 гривен</p>
                <p>• прошло более 14 дней со дня получения заказа </p>
            </span>
            <div class="about-return-inform about-inform">
                <span>
                    После того как Вы отправили нам посылку, для того, чтобы
                    оформить возврат, необходимо заполнить форму возврата.
                </span>
                <?php
               echo Remodal::widget([
                   'cancelButton'		=>	false,
                   'confirmButton'		=>	false,
                   'addRandomToID'		=>	false,
                    'id'            =>  'return-form',
                    'buttonOptions' =>  [
                        'label' =>  'Оформить возврат',
                        'class' =>  'about-inform-button yellow-button large-button'
                    ],
                    'content'   =>  $this->render('_return')
                ])
           ?>
            </div>
            <div class="about-return-accordion">
                <?=Accordion::widget([
                    'items' => [
                    [
                    'header' => Html::tag('span', 'Гарантийные обязательства (техника)', ['class' => 'content-data-first_1']),
                    'content' =>$this->render('_guarantee_equipment'),
                    ],
                    ],
                    'clientOptions' => ['collapsible' => true, 'active' => false, 'heightStyle' => 'content'],
                ]);?>
            </div>
        </div>
        <div class="about-TermsOfUse padding-bottom">
            <div class="about-TermsOfUse-header about-header semi-bold">
                <a name="about-TermOfUse-header">Условия использования сайта</a>
            </div>
            <span>
                Внимание! Перед просмотром сайта внимательно прочитайте данные условия.
                Если вы не согласны с условиями, не используйте сайт.
            </span>
            <span class="bold">
                Использование сайта
            </span>
            <span>
                Сайт Krasota-Style (далее «Krasota-Style») разрешает вам просматривать и загружать
                материалы сайта (далее «Сайт») только для личного некоммерческого=
                использования, при условии сохранения вами всей информации об авторском праве и
                других сведений о праве собственности, содержащихся в исходных материалах и
                любых их копиях. Запрещается изменять материалы Сайта, а также
                распространять или демонстрировать их в любом виде или использовать их любым
                другим образом для общественных или коммерческих целей. Любое использование
                материалов на других сайтах или в компьютерных сетях запрещается.
            </span>
            <span class="bold">
                Oтказ от ответственности
            </span>
            <span>
                Материалы и услуги сайта предоставляются «как есть» без каких-либо гарантий.
                Krasota-Style не гарантирует точности и полноты материалов, программ и
                услуг, предоставляемых на Сайте. Krasota-Style в любое время без уведомления
                может вносить изменения в материалы и услуги, предоставляемые на Сайте, а
                также в упомянутые в них продукты и цены. В случае устаревания материалов и услуг
                на Сайте не обязуется обновлять их. Krasota-Style ни при каких
                обстоятельствах не несет ответственности за любой ущерб (включая, но не
                ограничиваясь ущербом от потери прибыли, данных или от прерывания деловой
                активности), возникший вследствие использования, невозможности использования
                или результатов использования Сайта.
            </span>
            <span class="bold">
                Регистрация на сайте
            </span>
            <span>
                Регистрируясь на Сайте, вы соглашаетесь предоставить достоверную и точную
                информацию о себе и своих контактных данных.
                В результате регистрации вы получаете логин и пароль, за безопасность которых вы
                несете ответственность. Вы также несете ответственность за все действия под вашим
                логином и паролем на Сайте. В случае утери регистрационных данных вы обязуетесь
                сообщить нам об этом.
            </span>
            <span class="bold">
                Обратная связь и комментарии
            </span>
            <span>
                Обращаясь к нам или оставляя комментарии на сайте, вы несете ответственность,
                что данное сообщение не является незаконным, вредоносным, угрожающим,
                клеветническим, оскорбляет нравственность, нарушает авторские права,
                пропагандирует ненависть и/или дискриминацию людей по расовому, этническому,
                половому, религиозному, социальному признакам, содержит оскорбления в адрес
                конкретных лиц или организаций, а также каким-либо иным образом нарушает
                действующее законодательство Украины. Вы соглашаетесь, что любое ваше
                сообщение может быть удалено без вашего на то согласия, а также
                безвозмездно использовать по своему усмотрению. Krasota-Style не несет
                ответственности за любую информацию размещенную пользователями Сайта.
            </span>
            <span class="bold">
                Использование персональных данных
            </span>
            <span>
                Мы используем различные технологии для сбора и хранения информации, когда вы
                посещаете Сайт. Это может включать в себя запись одного или
                нескольких куки или анонимных идентификаторов. Мы также используем куки и
                анонимные идентификаторы, когда вы взаимодействуете с услугами, предложенными
                нашими партнерами, такими как рекламные услуги, например, которые могут
                появиться на других сайтах.
            </span>
        </div>
<!--        <div class="about-contacts padding-bottom">
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
                        044 578 20 16
                        </span>
                    </div>
                    <!--<span>
                        044 232 82 20
                    </span>-->
<!--                </div>
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
            <div class="map">-->
                <?/*=Accordion::widget([
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
<!--            </div>
            <div class="your-wish">-->
                <?php /*   $form = \yii\bootstrap\ActiveForm::begin([
                    'id'            =>  'users-interests-form'
                ]);
                */?>
                <?/*= $form->field($model, 'name')*/?>
                <?/*= $form->field($model, 'email')*/?>
                <?/*= $form->field($model, 'text')->textarea()*/?>
                <?/*= Html::submitButton('Отправить', ['class' => 'about-inform-button yellow-button large-button', 'name' =>
                    'contact-button']) */?>
                <?php /*$form->end(); */?>
<!--            </div>
        </div>-->
    </div>
</div>
