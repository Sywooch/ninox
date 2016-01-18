<?php

use yii\db\Migration;
use yii\db\Schema;

class m151126_153014_chat extends Migration
{
    public function up()
    {
        $this->createTable('chats', [
            'id'        =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'creator'   =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL',
            'name'      =>  Schema::TYPE_STRING.' NOT NULL',
            'avatar'    =>  Schema::TYPE_STRING.' NOT NULL',
            'timestamp' =>  Schema::TYPE_TIMESTAMP.' NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ]);

        $this->createTable('chatMessages', [
            'id'        =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'author'    =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL',
            'chat'      =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL',
            'timestamp' =>  Schema::TYPE_TIMESTAMP.' NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'text'      =>  Schema::TYPE_TEXT,
        ]);

        $this->createTable('chatReceivers', [
            'chat'      =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL',
            'user'      =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL',
        ]);
    }

    public function down()
    {
        $this->dropTable('chats');
        $this->dropTable('chatMessages');
        $this->dropTable('chatReceivers');

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
