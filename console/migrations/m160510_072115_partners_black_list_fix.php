<?php

use yii\db\Migration;

class m160510_072115_partners_black_list_fix extends Migration
{
    public function up()
    {
        $this->alterColumn('partners', 'blackList', \yii\db\Schema::TYPE_SMALLINT.' UNSIGNED NOT NULL DEFAULT 0');
    }

    public function down()
    {
        echo "m160510_072115_partners_black_list_fix cannot be reverted.\n";

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
