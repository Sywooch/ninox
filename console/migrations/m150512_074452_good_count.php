<?php

use yii\db\Schema;
use yii\db\Migration;

class m150512_074452_good_count extends Migration
{
    public function up()
    {
        $this->db->createCommand("ALTER TABLE `".\app\models\Good::tableName()."` ADD (
            `count` mediumint(255) DEFAULT 0,
            `isUnlimited` tinyint(1) DEFAULT NULL
        )")->execute();

        $this->db->createCommand("UPDATE `store` `a`, `".\app\models\Good::tableName()."` `b`
        SET `b`.`count` = `a`.`Qtty`, `b`.`isUnlimited` = `a`.`isUnlimited`
        WHERE `b`.`ID` = `a`.`GoodID` AND `a`.`ObjectID` = '1' AND  `a`.`LotID` = '1'")->execute();
    }

    public function down()
    {
        echo "m150512_074452_good_count cannot be reverted.\n";

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
