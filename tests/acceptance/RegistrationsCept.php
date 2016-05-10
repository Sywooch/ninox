<?php
$I = new AcceptanceTester($scenario);

$I->amOnPage('#loginModal');
$I->click('Регистрация');
$I->see('Ваше Имя');
$I->fillField('SignupForm[name]', '');
$I->see('Необходимо заполнить «Ваше Имя».');
$I->fillField('SignupForm[surname]', '');
$I->see('Необходимо заполнить «Ваша Фамилия».');
$I->fillField('SignupForm[email]', '');
$I->see('Необходимо заполнить «Ваш email».');
$I->fillField('SignupForm[phone]', '');
$I->see('Необходимо заполнить «Ваш телефон».');
$I->fillField('SignupForm[password]', '');
$I->see('Необходимо заполнить «Ваш пароль».');
$I->fillField('SignupForm[captcha]', '');
$I->see('Необходимо заполнить «Капча».');

//Регистрация1

$I->fillField('SignupForm[name]', '1');
$I->see('Значение «Ваше Имя» должно содержать минимум 2 символа.');
$I->fillField('SignupForm[surname]', '1');
$I->see('Значение «Ваша Фамилия» должно содержать минимум 2 символа.');
$I->fillField('SignupForm[email]', '1');
$I->see('Значение «Ваш email» не является правильным email адресом.');
$I->fillField('SignupForm[phone]', '300000000000');
$I->see('Ваш телефон');
$I->fillField('SignupForm[password]', '1');
$I->see('Значение «Ваш пароль» должно содержать минимум 6 символа.');
$I->fillField('SignupForm[captcha]', '2145127654327453746345634536414');
$I->see('Неправильный проверочный код.');
$I->amOnPage('#loginModal');
$I->fillField('SignupForm[email]', 'Awaychick@gmail.com');
$I->click('Регистрация');
$I->dontSee('', 'signupform-email');
//не закончено
