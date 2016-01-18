<?php

use yii\db\Migration;
use yii\db\Schema;

class m160118_122839_uml_feeds extends Migration
{
    public function up()
    {
        $this->createTable('priceListFeeds', [
            'id'            =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'name'          =>  Schema::TYPE_STRING,
            'categories'    =>  Schema::TYPE_TEXT,
            'format'        =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0',
            'creator'       =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0',
            'published'     =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0',
        ]);
    }

    public function down()
    {
        $this->dropTable('priceListFeeds');

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
