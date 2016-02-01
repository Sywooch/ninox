<?php

use yii\db\Migration;

class m160201_140920_pricelists_improvments extends Migration
{
    public function up()
    {
        $this->addColumn('priceListFeeds', 'options', \yii\db\Schema::TYPE_TEXT);
    }

    public function down()
    {
        $this->dropColumn('priceListFeeds', 'options');

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
