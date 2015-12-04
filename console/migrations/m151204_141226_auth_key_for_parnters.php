<?php

use yii\db\Migration;

class m151204_141226_auth_key_for_parnters extends Migration
{
    public function up()
    {
        $this->addColumn('partners', 'auth_key', \yii\db\Schema::TYPE_STRING.' DEFAULT NULL');
    }

    public function down()
    {
        $this->dropColumn('partners', 'auth_key');
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
