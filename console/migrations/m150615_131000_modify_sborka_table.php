<?php

use yii\db\Schema;
use yii\db\Migration;

class m150615_131000_modify_sborka_table extends Migration
{
    public function up()
    {
	    $this->addColumn('sborka', 'originalCount', Schema::TYPE_INTEGER.' NOT NULL DEFAULT 0');
	    $this->dropColumn('sborka', 'updated');
	    $this->dropColumn('sborka', 'updatedUser');
	    $this->dropColumn('sborka', 'discountPrice');
        $this->execute("ALTER TABLE `sborka`
            CHANGE `realyCountInOrder` `realyCount` INT(10),
            CHANGE `itemid` `itemID` INT(10),
            CHANGE `historyid` `orderID` BIGINT(32),
            CHANGE `priceid` `price` DOUBLE(24,2),
            CHANGE `sborka_tov_name` `name` TEXT(255),
            CHANGE `displayorder` `added` INT(10),
            CHANGE `realPrice` `originalPrice` DOUBLE(24, 2) NOT NULL DEFAULT 0");

        $this->execute("DROP INDEX `historyid` ON `sborka`; CREATE INDEX `orderID` ON `sborka` (`orderID`, `added`) USING BTREE;");
	    $this->execute("DELETE FROM `sborka` WHERE `itemID` = 0");
        $this->execute("UPDATE `sborka`,`goods` SET `sborka`.`itemID` = `goods`.`ID` WHERE `sborka`.`itemID` = `goods`.`Code`");
        $this->execute("UPDATE `sborka` SET `originalPrice` = `price`");
        $this->dropColumn('sborka', 'price');
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
