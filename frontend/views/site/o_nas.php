<?php
use yii\bootstrap\Html;
use yii\jui\Accordion;
/**
 * Created by PhpStorm.
 * User: hellios
 * Date: 08.02.16
 * Time: 14:07
 */
   ?>

<div class="content">
    <div class="left-side">
        <div class="left-side-menu">
            <div class="left-side-menu-item" href="#about-as-header">
                Как мы работаем
            </div>
            <div class="left-side-menu-item" href="#about-delivery-header">
                Доставка
            </div>
            <div class="left-side-menu-item" href="#about-payment-header">
                Оплата
            </div>
            <div class="left-side-menu-item" href="#about-return-header">
                Возврат товара
            </div>
            <div class="left-side-menu-item" href="#about-TermOfUse-header">
                Условия использования сайта
            </div>
        </div>
    </div>
    <div class="about">
        <div class="about-as">
            <div class="about-as-header about-header semi-bold">

                <a name="about-as-header">Как мы работаем</a>
            </div>
            <div class="bold about-as-center">
                На сайте krasota-style.ua действует 2 типа цен
            </div>
            <div class="about-as-price">
                <div class="about-as-price-rules">
                    <div class="about-as-price-rules-img wholesale-prices"></div>
                    <div class="about-as-price-rules-text">
                        <span class="bold">ОПТОВЫЕ ЦЕНЫ</span>
                        При заказе на суммуот 1000 грн.
                    </div>
                </div>
                <div class="about-as-price-rules">
                    <div class="about-as-price-rules-img retail-prices"></div>
                    <div class="about-as-price-rules-text">
                        <span class="bold">РОЗНИЧНЫЕ ЦЕНЫ</span>
                        При заказе на сумму от 500 до 1000 грн.
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
                            <span class="bold">ОФОРМЛЕНИЕ</span>
                            ЧТО-ТО ДА ПИСАТЬ
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
        <div class="about-delivery">
            <div class="about-delivery-header about-header semi-bold">
                <a name="about-delivery-header">Доставка</a>
            </div>
            <div class="about-delivery-content">
                <div class="about-delivery-content-img"></div>
                <div class="about-delivery-content-info">
                    <div class="about-delivery-content-info-delivery">
                        <div class="about-delivery-content-info-delivery-time">
                            <span>24 часа</span>
                            ДОСТАВКА В ОБЛАСНЫЕ ЦЕНТРЫ
                        </div>
                        <div class="about-delivery-content-info-delivery-time">
                            <span>48 часов</span>
                            ДОСТАВКА В РЕГИОНЫ
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
        <div class="about-payment">
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
                <div class="about-payment-inform-button yellow-button">
                    Сообщить об оплате
                </div>
            </div>
            <span>
                При оплате на карту взымается комиссия в размере 1% от суммы заказа (она уже входит в стоимость, указанную в смс).
                В отделении «Новой Почты», Вам нужно оплатить лишь стоимость пересылки.
            </span>
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
        <div class="about-return">
            <div class="about-return-header about-header semi-bold">
                <a name="about-return-header">Возврат</a>
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
                • на банковскую карту (возврат денежных средств осуществляется в течение 10 рабочих дней после получения товара)
                • на личный счет в нашем магазине для новых покупок
            </span>
            <span class="bold">
                Товар не подлежит возврату, если:
            </span>
            <span>
                • сумма возврата меньше 5 гривен
                • прошло более 14 дней со дня получения заказа
            </span>
            <div class="about-return-inform about-inform">
                <span>
                    После того как Вы отправили нам посылку, для того, чтобы
                    оформить возврат, необходимо заполнить форму возврата.
                </span>
                <div class="about-return-inform-button yellow-button">
                    Оформить возврат
                </div>
            </div>
            <div class="about-return-accordion">
                <?=Accordion::widget([
                    'items' => [
                    [
                    'header'    => Html::tag('div', Html::tag('span', 'Гарантийные обязательства (техника)'),
                    ['class' =>  'content-data-first_1']
                    ),
                    'content' =>'о технике',
                    'headerOptions' => [
                    'tag'   =>  'div',
                    'onclick' => 'return false;'
                    ],
                    ],
                    [
                    'header' => Html::tag('div', Html::tag('span', 'Гарантийные обязательства (бижутерия)'), [
                    'class' =>  'content-data-first_1',

                    ]),

                    'headerOptions' => ['tag' => 'div',

                    ],
                    'content' => 'о бижутерии',
                    'options' => ['tag' => 'div'],
                    ],
                    ],
                    'options' => [
                    'tag'   =>  'div',
                    'id'    =>  'accordion'
                    ],
                    'itemOptions' => ['tag' => 'div'],
                    'headerOptions' => ['tag' => 'div'],
                    'clientOptions' => ['collapsible' => false, 'icons' => false, 'heightStyle' => 'content', 'event' => false],
                ]);?>
            </div>
        </div>
        <div class="about-TermsOfUse">
            <div class="about-TermsOfUse-header about-header semi-bold">
                <a name="about-TermOfUse-header">Условия использования сайта</a>
            </div>
            <span>
                Внимание! Перед просмотром этого сайта внимательно прочитайте данные условия.
                Если вы не согласны с этими условиями, не используйте этот сайт.
            </span>
            <span class="bold">
                Использование сайта
            </span>
            <span>
                Сайт Rozetka.ua (далее «Розетка») разрешает вам просматривать и загружать материалы этого сайта (далее «Сайт»)
                только для личного некоммерческого использования, при условии сохранения вами всей информации об авторском праве
                и других сведений о праве собственности, содержащихся в исходных материалах и любых их копиях.
                Запрещается изменять материалы этого Сайта, а также распространять или демонстрировать их в любом виде
                или использовать их любым другим образом для общественных или коммерческих целей. Любое использование
                этих материалов на других сайтах или в компьютерных сетях запрещается.
            </span>
            <span class="bold">
                Oтказ от ответственности
            </span>
            <span>
                Материалы и услуги этого сайта предоставляются «как есть» без каких-либо гарантий.
                Розетка не гарантирует точности и полноты материалов, программ и услуг, предоставляемых на этом Сайте.
                Розетка в любое время без уведомления может вносить изменения в материалы и услуги, предоставляемые на этом Сайте,
                а также в упомянутые в них продукты и цены. В случае устаревания материалов и услуг на этом Сайте
                Розетка не обязуется обновлять их. Розетка ни при каких обстоятельствах не несет ответственности за любой ущерб
                (включая, но не ограничиваясь ущербом от потери прибыли, данных или от прерывания деловой активности),
                возникший вследствие использования, невозможности использования или результатов использования этого сайта.
            </span>
            <span class="bold">
                Регистрация на сайте
            </span>
            <span>
                Регистрируясь на Сайте, вы соглашаетесь предоставить достоверную и точную информацию о себе и своих контактных данных.

                В результате регистрации вы получаете логин и пароль, за безопасность которых вы несете ответственность.
                Вы также несете ответственность за все действия под вашим логином и паролем на Сайте. В случае утери регистрационных данных вы обязуетесь сообщить нам об этом.
            </span>
            <span class="bold">
                Обратная связь и комментарии
            </span>
            <span>
                Обращаясь к нам или оставляя комментарии на сайте, вы несете ответственность, что данное сообщение не является незаконным,
                вредоносным, угрожающим, клеветническим, оскорбляет нравственность, нарушает авторские права, пропагандирует ненависть
                и/или дискриминацию людей по расовому, этническому, половому, религиозному, социальному признакам, содержит оскорбления
                в адрес конкретных лиц или организаций, а также каким либо иным образом нарушает действующее законодательство Украины.
                Вы соглашаетесь, что любое ваше сообщение Розетка может удалять без вашего на то согласия, а также безвозмездно использовать
                по своему усмотрению. Розетка не несет ответственности за любую информацию размещенную пользователями Сайта.
            </span>
            <span class="bold">
                Использование персональных данных
            </span>
            <span>
                Мы используем различные технологии для сбора и хранения информации, когда вы посещаете сайт Розетка
                (подробнее о защите и обработке персональных данных). Это может включать в себя запись одного или нескольких
                куки или анонимных идентификаторов. Мы также используем куки и анонимные идентификаторы, когда вы взаимодействуете с услугами,
                предложенными нашими партнерами, такими как рекламные услуги, например, которые могут появиться на других сайтах.
            </span>
        </div>
    </div>
</div>


