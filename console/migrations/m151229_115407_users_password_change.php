<?php

use yii\db\Migration;
use yii\db\Schema;

class m151229_115407_users_password_change extends Migration
{
    public function up()
    {
        $this->addColumn('siteusers', 'changePassword', Schema::TYPE_SMALLINT.' UNSIGNED NOT NULL DEFAULT 0');
        $this->addColumn('siteusers', 'workingFrom', Schema::TYPE_TIME);
        $this->addColumn('siteusers', 'workingTo', Schema::TYPE_TIME);
    }

    public function down()
    {
        $this->dropColumn('siteusers', 'changePassword');
        $this->dropColumn('siteusers', 'workingFrom');
        $this->dropColumn('siteusers', 'workingTo');

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
