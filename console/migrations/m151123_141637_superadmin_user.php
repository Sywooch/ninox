<?php

use yii\db\Migration;

class m151123_141637_superadmin_user extends Migration
{
    public function up()
    {
        $this->addColumn('siteusers', 'superAdmin', \yii\db\Schema::TYPE_SMALLINT.' NOT NULL DEFAULT 0');
    }

    public function down()
    {
        return $this->dropColumn('siteusers', 'superAdmin');
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
