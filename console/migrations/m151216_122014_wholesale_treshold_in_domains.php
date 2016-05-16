<?php

use yii\db\Migration;

class m151216_122014_wholesale_treshold_in_domains extends Migration
{
    public function up()
    {
        $this->addColumn('domains', 'wholesaleThreshold', \yii\db\Schema::TYPE_BIGINT.' UNSIGNED DEFAULT 1000');
        $this->addColumn('domains', 'minimalOrderSum', \yii\db\Schema::TYPE_INTEGER.' UNSIGNED DEFAULT 100');
    }

    public function down()
    {
        $this->dropColumn('domains', 'wholesaleThreshold');
        $this->dropColumn('domains', 'minimalOrderSum');

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
