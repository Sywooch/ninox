<?php

use yii\db\Migration;

class m160504_122027_search_index_fix extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `item_translations` ADD FULLTEXT INDEX `Name` (`name`)");
    }

    public function down()
    {
        $this->dropIndex('Name', 'item_translations');
        echo "m160504_122027_search_index_fix was successfully reverted.\n";
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
