<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('perform actions and see result');
$I->amOnPage('/');
//бижа
$I->click('Бижутерия');
$I->canSeeInCurrentUrl('/bizhuteriya');
//кольца
$I->click('Кольца');
$I->canSeeInCurrentUrl('/bizhuteriya/kolca');
$I->amOnPage('/bizhuteriya');
$I->click('Браслеты');
$I->canSeeInCurrentUrl('/bizhuteriya/braslety');
$I->click('Браслеты разные');
$I->canSeeInCurrentUrl('/bizhuteriya/braslety/braslety-raznye');
$I->click('Браслеты');
$I->click('Браслеты пластик');
$I->canSeeInCurrentUrl('/bizhuteriya/braslety/braslety-plastik');
$I->click('Браслеты');
$I->click('Браслеты шамбала');
$I->canSeeInCurrentUrl('/bizhuteriya/braslety/braslety-shambala');
$I->click('Браслеты');
$I->click('Браслеты из камней');
$I->canSeeInCurrentUrl('/bizhuteriya/braslety/braslety-iz-kamney');
$I->click('Браслеты');
$I->click('Браслеты с цирконием');
$I->canSeeInCurrentUrl('/bizhuteriya/braslety/braslety-s-cirkoniem');
$I->click('Браслеты');
$I->click('Браслет на ногу');
$I->canSeeInCurrentUrl('/bizhuteriya/braslety/braslet-na-nogu');
$I->click('Браслеты');
$I->click('Пандора');
$I->canSeeInCurrentUrl('/bizhuteriya/braslety/pandora');
//резинки
$I->click('Резинки');
$I->canSeeInCurrentUrl('/bizhuteriya/rezinki');
$I->click('Резинки');
$I->click('Резинки с камнями');
$I->canSeeInCurrentUrl('/bizhuteriya/rezinki/rezinki-s-kamnyami');
$I->click('Резинки');
$I->click('Резинки обычные');
$I->canSeeInCurrentUrl('/bizhuteriya/rezinki/rezinki-obychnye');
$I->click('Резинки');
$I->click('Резинки силиконовые');
$I->canSeeInCurrentUrl('/bizhuteriya/rezinki/rezinki-silikonovye');
$I->click('Резинки');
$I->click('Бублик для волос');
$I->canSeeInCurrentUrl('/bizhuteriya/rezinki/bublik-dlya-volos');
$I->click('Резинки');
$I->click('Резинки крупные');
$I->canSeeInCurrentUrl('/bizhuteriya/rezinki/rezinki-krupnye');
$I->click('Резинки');
$I->click('Резинки Калуш');
$I->canSeeInCurrentUrl('/bizhuteriya/rezinki/rezinki-kalush');


