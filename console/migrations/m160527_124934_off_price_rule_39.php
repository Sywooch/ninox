<?php

use yii\db\Migration;

class m160527_124934_off_price_rule_39 extends Migration
{
    public function up()
    {
        \common\models\Pricerule::updateAll(['Enabled' => 0], ['ID' => 39]);
    }

    public function down()
    {
        \common\models\Pricerule::updateAll(['Enabled' => 1], ['ID' => 39]);

        echo "m160527_124934_off_price_rule_39 was successfully reverted.\n";
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
