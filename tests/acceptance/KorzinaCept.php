<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('perform actions and see result');
$I->amOnPage('/');
$I->click('//*[@id="modal-cart"]');
$I->see('Предварительная сумма к оплате');
