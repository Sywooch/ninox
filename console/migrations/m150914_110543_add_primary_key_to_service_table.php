<?php

use yii\db\Migration;

class m150914_110543_add_primary_key_to_service_table extends Migration
{
    public function up()
    {
        $this->addPrimaryKey('key', 'service', 'key');
    }

    public function down()
    {
        $this->dropPrimaryKey('key', 'service');

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
