<?php

use yii\db\Schema;
use yii\db\Migration;

class m150618_145101_history_update extends Migration
{
    public function up()
    {
        $this->execute("UPDATE `history` SET `dostavka` = '2' WHERE `dostavka` = 'Новая почта';
UPDATE `history` SET `plateg` = '1' WHERE `plateg` = 'наложенный платеж' OR `plateg` = 'наложенным платежом' OR `plateg` = 'РЅР°Р»РѕР¶РµРЅРЅС‹Рј РїР»Р°С‚РµР¶РѕРј';
UPDATE `history` SET `plateg` = '2' WHERE `plateg` = 'на банковскую карту' OR `plateg` = 'РЅР° Р±Р°РЅРєРѕРІСЃРєСѓСЋ РєР°СЂС‚Сѓ';
UPDATE `history` SET `plateg` = '0' WHERE `plateg` = '' OR `plateg` = 'способ платежа не выбран';
ALTER TABLE `history` DROP COLUMN `callState`;");

        $this->execute("ALTER TABLE `history`
            CHANGE `email` `customerEmail` VARCHAR(255),
            CHANGE `name` `customerName` VARCHAR(64),
            CHANGE `surname` `customerSurname` VARCHAR(64),
            CHANGE `fathername` `customerFathername` VARCHAR(64),
            CHANGE `phone` `customerPhone` VARCHAR(64),
            CHANGE `adress` `deliveryAddress` VARCHAR(255),
            CHANGE `oblast` `deliveryRegion` VARCHAR(255),
            CHANGE `displayorder` `added` INT(11),
            CHANGE `dostavka` `deliveryType` INT(2) NOT NULL DEFAULT 0,
            CHANGE `city` `deliveryCity` VARCHAR(255),
            CHANGE `text` `customerComment` TEXT,
            CHANGE `userid` `customerID` INT(11) NOT NULL DEFAULT 0,
            CHANGE `novaposhta` `deliveryInfo` VARCHAR(255),
            CHANGE `kupon` `coupon` VARCHAR(255),
            CHANGE `plateg` `paymentType` INT(11) NOT NULL DEFAULT 0,
            CHANGE `plateg_privat` `paymentInfo` VARCHAR(255),
            CHANGE `perezvon` `callback` INT(2) NOT NULL DEFAULT 0,
            CHANGE `zamena` `canChangeItems` INT(2) NOT NULL DEFAULT 0,
            CHANGE `fakt_summ` `actualAmount` INT(2) NOT NULL DEFAULT 0,
            CHANGE `sendnakladna` `nakladnaSendState` INT(2) NOT NULL DEFAULT 0,
            CHANGE `done_userid` `responsibleUserID` INT(11) NOT NULL DEFAULT 0,
            CHANGE `confirm` `confirmed` INT(1) NOT NULL DEFAULT 0,
            CHANGE `confirm_money` `moneyConfirmed` INT(2),
            CHANGE `confirm_money_date` `moneyConfirmedDate` DATETIME,
            CHANGE `date_done` `doneDate` DATETIME,
            CHANGE `date_send` `sendDate` DATETIME,
            CHANGE `date_recieved_np` `receivedDate` DATETIME,
            CHANGE `sendsms` `smsState` INT(1) NOT NULL DEFAULT 0,
            CHANGE `trash` `deleted` INT(1) NOT NULL DEFAULT 0,

            ADD (
                `confirmedDate` DATETIME,
                `smsSendDate` DATETIME,
                `callsCount` INT(11) NOT NULL DEFAULT 0,
                `originalSum` DOUBLE(24, 2) NOT NULL DEFAULT 0
            ),
            DROP COLUMN `discount`,
            DROP COLUMN `send_cart2`,
            DROP COLUMN `vozvrat`,
            DROP COLUMN `globalMoneyConfirmTime`,
            DROP COLUMN `ttn_date`,
            DROP COLUMN `ttn_receiver_warehouse`,
            DROP COLUMN `ttn_date_desired`,
            DROP COLUMN `ttn_order_cont`,
            DROP COLUMN `ttn_weight`,
            DROP COLUMN `ttn_pack_type`,
            DROP COLUMN `ttn_pack_number`,
            DROP COLUMN `ttn_description`,
            DROP COLUMN `ttn_saturday`,
            DROP COLUMN `ttn_documents`,
            DROP COLUMN `ttn_additional_info`,
            DROP COLUMN `ttn_payer`,
            DROP COLUMN `ttn_pay_type`,
            DROP COLUMN `ttn_floor_count`,
            DROP COLUMN `ttn_original`");

        $this->execute("UPDATE `history` SET `originalSum` = (SELECT SUM(`originalPrice` * `originalCount`) FROM `sborka` WHERE `sborka`.`orderID` = `history`.`id` GROUP BY `orderID`)");
    }

    public function down()
    {
        echo "m150618_145101_history_update cannot be reverted.\n";

        return true;
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
