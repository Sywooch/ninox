<?php

use yii\db\Migration;

class m150920_074105_reviews extends Migration
{
    public function up()
    {
	    $this->execute("ALTER TABLE `reviews`
            DROP COLUMN `question2`,
			DROP COLUMN `client_face`,
			DROP COLUMN `position`,
			CHANGE COLUMN `parentId` `target`  int(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `customerType`;");
    }

    public function down()
    {
        echo "m150920_074105_reviews cannot be reverted.\n";

        return false;
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
