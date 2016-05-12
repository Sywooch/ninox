<?php

use yii\db\Migration;

class m150920_074105_reviews extends Migration
{
    public function up()
    {
        $this->dropColumn('reviews', 'question2');
        $this->dropColumn('reviews', 'client_face');
        $this->dropColumn('reviews', 'position');
        $this->renameColumn('reviews', 'parentId', 'target');
        $this->execute("UPDATE `reviews` SET `target` = 0 WHERE `target` IS NULL");
        $this->alterColumn('reviews', 'target', \yii\db\Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0');
    }

    public function down()
    {
        echo "m150920_074105_reviews cannot be reverted.\n";

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
