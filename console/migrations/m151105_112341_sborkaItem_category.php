<?php

use yii\db\Migration;

class m151105_112341_sborkaItem_category extends Migration
{
    public function up()
    {
        $this->addColumn('sborka', 'category', \yii\db\mysql\Schema::TYPE_STRING.' NOT NULL');
        $this->execute("UPDATE `sborka` `a`, `goods` `b`, `goodsgroups` `c` SET `a`.`category` = `c`.`Code` WHERE `a`.`itemID` = `b`.`ID` AND `b`.`GroupID` = `c`.`ID`");
    }

    public function down()
    {
        $this->dropColumn('sborka', 'category');

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
