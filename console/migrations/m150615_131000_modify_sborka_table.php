<?php

use yii\db\Schema;
use yii\db\Migration;

class m150615_131000_modify_sborka_table extends Migration
{
    public function up()
    {
	    $this->execute("SET @sql = (SELECT IF (
			(SELECT COUNT(*)
                FROM INFORMATION_SCHEMA.COLUMNS WHERE
				`table_name` = 'sborka'
				AND `column_name` = 'originalCount'
				) > 0,
				\"SELECT 0\",
				\"ALTER TABLE `sborka` ADD COLUMN `originalCount` INT(10) NOT NULL DEFAULT 0;\"
			));

			PREPARE stmt FROM @sql;
			EXECUTE stmt;
			DEALLOCATE PREPARE stmt;");
	    $this->execute("SET @sql = (SELECT IF (
			(SELECT COUNT(*)
                FROM INFORMATION_SCHEMA.COLUMNS WHERE
				`table_name` = 'sborka'
				AND `column_name` = 'discountSize'
				) > 0,
				\"SELECT 0\",
				\"ALTER TABLE `sborka` ADD COLUMN `discountSize` INT(10) NOT NULL DEFAULT 0;\"
			));

			PREPARE stmt FROM @sql;
			EXECUTE stmt;
			DEALLOCATE PREPARE stmt;");
	    $this->execute("SET @sql = (SELECT IF (
			(SELECT COUNT(*)
                FROM INFORMATION_SCHEMA.COLUMNS WHERE
				`table_name` = 'sborka'
				AND `column_name` = 'discountType'
				) > 0,
				\"SELECT 0\",
				\"ALTER TABLE `sborka` ADD COLUMN `discountType` INT(1) NOT NULL DEFAULT 0;\"
			));

			PREPARE stmt FROM @sql;
			EXECUTE stmt;
			DEALLOCATE PREPARE stmt;");

	    $this->execute("SET @sql = (SELECT IF (
			(SELECT COUNT(*)
                FROM INFORMATION_SCHEMA.COLUMNS WHERE
				`table_name` = 'sborka'
				AND `column_name` = 'updated'
				) > 0,
				\"ALTER TABLE `sborka` DROP COLUMN `updated`;\",
				\"SELECT 0\"
			));

			PREPARE stmt FROM @sql;
			EXECUTE stmt;
			DEALLOCATE PREPARE stmt;");

	    $this->execute("SET @sql = (SELECT IF (
			(SELECT COUNT(*)
                FROM INFORMATION_SCHEMA.COLUMNS WHERE
				`table_name` = 'sborka'
				AND `column_name` = 'updatedUser'
				) > 0,
				\"ALTER TABLE `sborka` DROP COLUMN `updatedUser`;\",
				\"SELECT 0\"
			));

			PREPARE stmt FROM @sql;
			EXECUTE stmt;
			DEALLOCATE PREPARE stmt;");

	    $this->execute("SET @sql = (SELECT IF (
			(SELECT COUNT(*)
                FROM INFORMATION_SCHEMA.COLUMNS WHERE
				`table_name` = 'sborka'
				AND `column_name` = 'discountPrice'
				) > 0,
				\"ALTER TABLE `sborka` DROP COLUMN `discountPrice`;\",
				\"SELECT 0\"
			));

			PREPARE stmt FROM @sql;
			EXECUTE stmt;
			DEALLOCATE PREPARE stmt;");

        $this->execute("ALTER TABLE `sborka`
            CHANGE `realyCountInOrder` `realyCount` INT(10),
            CHANGE `itemid` `itemID` INT(10),
            CHANGE `historyid` `orderID` BIGINT(32),
            CHANGE `priceid` `price` DOUBLE(24,2),
            CHANGE `sborka_tov_name` `name` TEXT(255),
            CHANGE `displayorder` `added` INT(10),
            CHANGE `realPrice` `originalPrice` DOUBLE(24, 2) NOT NULL DEFAULT 0");

        $this->execute("DROP INDEX `historyid` ON `sborka`; CREATE INDEX `orderID` ON `sborka` (`orderID`, `added`) USING BTREE;");
        $this->execute("UPDATE `sborka`,`goods` SET `sborka`.`itemID` = `goods`.`ID` WHERE `sborka`.`itemID` = `goods`.`Code`"); //Выполнился на тестовом сервере за 40к секунд, мб можно как-то оптимизировать запрос?
        $this->execute("UPDATE `sborka` SET `originalPrice` = `price`");
        $this->execute("ALTER TABLE `sborka` DROP COLUMN `price`");
        $this->execute("UPDATE `sborka`, `operations` SET `sborka`.`originalCount` = `operations`.`Qtty` WHERE `sborka`.`orderID` = `operations`.`Acct` AND `sborka`.`itemID` = `operations`.`GoodID`");
    }



    public function down()
    {
        echo "m150615_131000_modify_sborka_table cannot be reverted.\n";
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
