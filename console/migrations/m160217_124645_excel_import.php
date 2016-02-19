<?php

use yii\db\Migration;
use yii\db\Schema;

class m160217_124645_excel_import extends Migration
{
    public function up()
    {
        $this->createTable('priceListsImport', [
            'id'            =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'name'          =>  Schema::TYPE_STRING,
            'file'          =>  Schema::TYPE_STRING,
            'format'        =>  Schema::TYPE_STRING,
            'created'       =>  Schema::TYPE_DATETIME,
            'creator'       =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0',
            'imported'      =>  Schema::TYPE_INTEGER.' UNSIGNED NOT NULL DEFAULT 0',
            'importedDate'  =>  Schema::TYPE_DATETIME,
            'configuration' =>  Schema::TYPE_TEXT,
        ]);
    }

    public function down()
    {
        $this->dropTable('priceListsImport');

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
