<?php

use yii\db\Migration;

class m160531_112405_clock_transfer extends Migration
{
    public function up()
    {
        \common\models\Good::updateAll(['GroupID' => 683], ['AND', ['GroupID' => 178], ['<', 'PriceOut1', 1000]]);
    }

    public function down()
    {
        \common\models\Good::updateAll(['GroupID' => 178], ['GroupID' => 683]);
        echo "m160531_112405_clock_transfer was successfully reverted.\n";
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
