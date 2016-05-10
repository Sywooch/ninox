<?php
$I = new AcceptanceTester($scenario);
$I->wantTo('perform actions and see result');
/*$I->amOnPage('/');
$I->click('');
$I->see('0 800 508 208');
$I->see('бесплатно со стационарных');
$I->see('Киев');
$I->see('044 578 20 16');
$I->see('моб. МТС');
$I->see('050 677 54 56');
$I->see('моб. Киевстар');
$I->see('067 507 87 73');
$I->see('моб. Life');
$I->see('063 578 20 16');
$I->see('Время работы call-центра:');
$I->see('вт.-вс: с 9.00 до 18.00');
$I->see('пн: с 9.00 до 15.00');
$I->click('//*[@id="w8"]/div[4]/a');
$I->canSeeInCurrentUrl('/#callbackModal');


$I->fillField('CallbackForm[name]', '');
$I->see('Необходимо заполнить «Имя и фамилия».');
$I->fillField('CallbackForm[phone]', '');
$I->see('Необходимо заполнить «Ваш телефон».');
$I->fillField('CallbackForm[question]', '');
$I->see('Необходимо заполнить «Сообщение».');
$I->fillField('CallbackForm[captcha]', '');
$I->see('Неправильный проверочный код.');
*/
$I->amOnPage('/');
$I->click('О компании');
$I->canSeeInCurrentUrl('/o-nas');
$I->click('Доставка и оплата');
$I->click('Сообщить об оплате');
$I->canSeeInCurrentUrl('/o-nas#payment-confirm-form');
$I->fillField('PaymentConfirmForm[orderNumber]', ' ');
$I->see('Необходимо заполнить «№ заказа».');
$I->fillField('PaymentConfirmForm[sum]', ' ');
$I->see('Необходимо заполнить «Сумма оплаты».');
$I->fillField('PaymentConfirmForm[paymentDate]', ' ');
$I->see('Необходимо заполнить «Дата оплаты».');
$I->fillField('PaymentConfirmForm[orderNumber]', '1');
$I->fillField('PaymentConfirmForm[sum]', '1');
$I->fillField('PaymentConfirmForm[paymentDate]', '5-12-2014');
$I->click('Отправить');
$I->canSeeInCurrentUrl('/o-nas');
$I->click('Гарантии и возврат');
$I->click('Оформить возврат');
$I->canSeeInCurrentUrl('/o-nas#return-form');
$I->fillField('ReturnForm[orderNumber]', ' ');
$I->see('Необходимо заполнить «№ заказа».');
$I->fillField('ReturnForm[customerPhone]', ' ');
$I->see('Необходимо заполнить «Тел. отправителя».');
$I->fillField('ReturnForm[sendDate]', ' ');
$I->see('Необходимо заполнить «Дата отправки».');
$I->fillField('ReturnForm[nakladna]', ' ');
$I->see('Необходимо заполнить «№ ТТН».');
$I->fillField('ReturnForm[cardNumber]', ' ');
$I->see('Необходимо заполнить «Номер карты для возврата денег*».');

$I->fillField('ReturnForm[orderNumber]', '1');
$I->fillField('ReturnForm[customerPhone]', '1');
$I->fillField('ReturnForm[sendDate]', '1');
$I->fillField('ReturnForm[nakladna]', '1');
$I->fillField('ReturnForm[cardNumber]', '1');
$I->click('Отправить');
$I->canSeeInCurrentUrl('/o-nas');

$I->fillField('UsersInterestsForm[name]', ' ');
$I->see('Необходимо заполнить «Ваше имя».');
$I->fillField('UsersInterestsForm[email]', ' ');
$I->see('Необходимо заполнить «Email».');
$I->fillField('UsersInterestsForm[text]', ' ');
$I->see('Необходимо заполнить «Что вас интересует?».');
$I->fillField('UsersInterestsForm[name]', '1');
$I->fillField('UsersInterestsForm[email]', '1');
$I->fillField('UsersInterestsForm[text]', '1');
$I->click('Отправить');

$I->amOnPage('/#callbackModal');
$I->fillField('CallbackForm[name]', '');
$I->see('Необходимо заполнить «Имя и фамилия».');
$I->fillField('CallbackForm[phone]', '');
$I->see('Необходимо заполнить «Ваш телефон».');
$I->fillField('CallbackForm[question]', '');
$I->see('Необходимо заполнить «Сообщение».');
$I->fillField('CallbackForm[captcha]', '');
$I->see('Неправильный проверочный код.');











