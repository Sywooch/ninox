<?php

use yii\db\Migration;

class m150909_140200_siteuser_phone extends Migration
{
    public function up()
    {
        $this->addColumn('siteusers', 'phone', 'VARCHAR(10) DEFAULT NULL');
    }

    public function down()
    {
        $this->dropColumn('siteusers', 'phone');

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
