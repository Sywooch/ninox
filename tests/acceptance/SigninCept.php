<?php
$I = new AcceptanceTester($scenario);
//Логинизация

$I->wantToTest("login form");
$I->wantTo('log in as regular user');
$I->amOnPage('/#loginModal');
$I->fillField('#loginform-phone', ' ');
$I->see('Необходимо заполнить «Номер телефона».');
$I->fillField('#loginform-password', ' ');
$I->see('Необходимо заполнить «Пароль».');
$I->fillField('#loginform-phone', '+380930200251');
$I->fillField('#loginform-password', '123456789');
$I->click('Войти');
$I->see('Пароль');
$I->fillField('#loginform-phone', '+380930200251');
$I->fillField('#loginform-password', 'Sekret123');
$I->click('Войти');
$I->canSeeInCurrentUrl('/');

//Восставоление пароля

$I->amOnPage('#loginModal');
$I->click('Восстановить пароль');
$I->canSeeInCurrentUrl('/request-password-reset');
$I->amOnPage('/request-password-reset');
$I->fillField('PasswordResetRequestForm[email]', '');
$I->see('Необходимо заполнить «Ваш email».');
$I->fillField('PasswordResetRequestForm[email]', '1');
$I->see('Значение «Ваш email» не является правильным email адресом.');
$I->fillField('PasswordResetRequestForm[email]', 'Awaychick@gmail.com');
$I->click('Отправить');
$I->seeLink('', '/');


