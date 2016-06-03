<?php

use yii\db\Migration;

class m160603_131808_add_price_rule_for_sale extends Migration
{
    public function up()
    {
        $priority = \common\models\Pricerule::find()
                ->select('Priority')
                ->orderBy(['Priority' => SORT_DESC])
                ->limit(1)->scalar() + 1;
        $priceRule = new \common\models\Pricerule([
            'Name'      =>   'Распродажа Солоха 20%',
            'Formula'   =>   'IF ((GoodGroup >= AABAAH) OR (GoodGroup >= AABABC) OR (GoodGroup >= AABABD)) AND (GoodGroup != AABABDAAF) AND (DocumentSum >= 2000) AND (Date >= 04.06.2016) AND (Date <= 08.06.2016) THEN Discount = 20',
            'Enabled'   =>   1,
            'Priority'  =>   $priority,
        ]);

        $priceRule->save();
    }

    public function down()
    {
        echo "m160603_131808_add_price_rule_for_sale cannot be reverted.\n";
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
