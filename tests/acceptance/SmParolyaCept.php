<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('perform actions and see result');
//$I->amOnPage('/account#changePassword');
//$I->fillField('#loginform-phone', '+380930200251');
//$I->fillField('#loginform-password', 'Sekret123');
//$I->click('Войти');
//$I->canSeeInCurrentUrl('/');
$I->amOnPage('/account#changePassword');
$I->fillField('ChangePasswordForm[oldPassword]', ' ');
$I->see('Необходимо заполнить «Текущий пароль».');
$I->fillField('ChangePasswordForm[newPassword]', ' ');
$I->see('Необходимо заполнить «Новый пароль».');
$I->fillField('ChangePasswordForm[newPassword_repeat]', ' ');
$I->see('Необходимо заполнить «Новый пароль ещё раз».');

