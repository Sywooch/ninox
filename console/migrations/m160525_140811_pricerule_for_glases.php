<?php

use yii\db\Migration;

class m160525_140811_pricerule_for_glases extends Migration
{
    public function up()
    {
        \common\models\Pricerule::updateAll([
            'Formula' => 'IF (DocumentSum >= 1000) AND (GoodGroup >= AACAAW) AND (GoodGroup != AACAAWAAC) THEN Discount = 55'
        ], ['ID' => 60]);
    }

    public function down()
    {
        \common\models\Pricerule::updateAll([
            'Formula' => 'IF (DocumentSum >= 1000) AND (GoodGroup >= AACAAW) THEN Discount = 55'
        ], ['ID' => 60]);
        echo "m160525_140811_pricerule_for_glases was successfully reverted.\n";

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
